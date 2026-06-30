<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Activity;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class ReservationController extends Controller
{
    public function index()
    {
        $activities = \App\Models\Activity::where('is_active', true)->orderBy('name')->get();
        return view('reservation', compact('activities'));
    }

public function store(Request $request)
    {
        // 1. Prvotní zjištění aktivity
        $request->validate([
            'activity_id' => 'required|integer|exists:activities,id'
        ]);

        $activity = Activity::findOrFail($request->activity_id);

        // Dynamická příprava pravidla pro custom_field
        $customFieldRule = 'nullable|string|max:1000';
        if (!empty($activity->custom_field_label) && $activity->custom_field_required) {
            $customFieldRule = 'required|string|max:1000';
        }

        // 2. Kompletní flexibilní validace příchozích dat
        $validated = $request->validate([
            'activity_id'      => 'required|integer|exists:activities,id',
            'reservation_date' => 'required|date',
            'slot'             => 'required|array',
            'child_name'       => $activity->show_child_name ? 'required|string|max:255' : 'nullable|string|max:255',
            'kidsCount'        => $activity->show_kids_count ? 'required|integer|min:1' : 'nullable|integer',
            'child_info'       => $activity->show_child_info ? 'nullable|string|max:500' : 'nullable|string|max:500',
            'parent_name'      => 'required|string|max:255',
            'contact'          => 'required|string|max:255',
            'note'             => $activity->show_note ? 'nullable|string|max:1000' : 'nullable|string|max:1000',
            'pricing'          => 'required|string|in:hourly,daily,monthly',
            'sharing'          => 'required|string',
            'custom_field'     => $customFieldRule,
            'recurring_days'   => 'nullable|string'
        ]);

        // --- POJISTKA PROTI SOUKROMÉ REZERVACI UŽ OBSAZENÉHO ČASU ---
        if ($validated['sharing'] === 'Individuální čas') {
            $existujiciRezervace = Reservation::where('activity_id', $activity->id)
                ->where('payment_status', '!=', 'cancelled')
                ->where(function($q) use ($validated) {
                    $q->where('date', $validated['reservation_date'])
                      ->orWhere(function($sub) use ($validated) {
                          $sub->whereNotNull('date_end')
                              ->where('date', '<=', $validated['reservation_date'])
                              ->where('date_end', '>=', $validated['reservation_date']);
                      });
                })->get();

            $denVTydnu = \Carbon\Carbon::parse($validated['reservation_date'])->dayOfWeek;
            $kolizniRezervace = $existujiciRezervace->filter(function($res) use ($denVTydnu) {
                if ($res->date_end) {
                    $days = $res->recurring_days; // Používáme čisté pole z modelu
                    return is_array($days) && in_array($denVTydnu, $days);
                }
                return true;
            });

            foreach ($validated['slot'] as $pozadovanySlot) {
                foreach ($kolizniRezervace as $staraRezervace) {
                    $stareSloty = is_array($staraRezervace->slots) ? $staraRezervace->slots : json_decode($staraRezervace->slots, true);
                    if (is_array($stareSloty) && in_array($pozadovanySlot, $stareSloty)) {
                        return back()->withErrors(['slot' => 'Zvolený čas (' . $pozadovanySlot . ') už je částečně obsazen. Zvolte "Otevřenou partu".'])->withInput();
                    }
                }
            }
        }
        // --- KONEC POJISTKY ---

        // 3. VÝPOČET CENY A KONCE MĚSÍCE (Pro paušály)
        $pocet_hodin = count($validated['slot']);
        $celkova_cena = 0;
        $dateEnd = null;
        $recurringDays = null;
        
        if ($validated['pricing'] === 'monthly') {
            $celkova_cena = $activity->price_per_month; 
            $dateEnd = \Carbon\Carbon::parse($validated['reservation_date'])->endOfMonth()->format('Y-m-d');
            // Dekódujeme text z JS na čisté PHP pole, o uložení do DB se postará Model Cast
            $recurringDays = $request->filled('recurring_days') ? json_decode($request->input('recurring_days'), true) : null;
        } elseif ($validated['pricing'] === 'daily') {
            $celkova_cena = $activity->price_per_day; 
        } else {
            $celkova_cena = $pocet_hodin * $activity->price_per_hour; 
        }

        // 4. ČISTÉ ULOŽENÍ DO DATABÁZE
        try {
            $reservation = Reservation::create([
                'date'               => $validated['reservation_date'],
                'date_end'           => $dateEnd, 
                'recurring_days'     => $recurringDays, // Model to bezpečně uloží
                'slots'              => $validated['slot'],
                'child_name'         => $validated['child_name'] ?? 'Nezadáno',
                'kids_count'         => $validated['kidsCount'] ?? 1,
                'child_info'         => $validated['child_info'] ?? '', 
                'parent_name'        => $validated['parent_name'],
                'contact'            => $validated['contact'],
                'note'               => $validated['note'] ?? '', 
                'custom_field_value' => $request->input('custom_field'), 
                'pricing_model'      => $validated['pricing'],
                'sharing_type'       => $validated['sharing'],
                'total_price'        => $celkova_cena,
                'payment_status'     => 'pending',
                'activity_id'        => $activity->id
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Chyba databáze: ' . $e->getMessage()]);
        }

        // 5. Generování QR kódu pro platbu
        $iban = "CZ6830300000001004823033"; 
        $amountFormat = number_format($celkova_cena, 2, '.', '');
        
        $msgClean = iconv('UTF-8', 'ASCII//TRANSLIT', $validated['parent_name']);
        $msgClean = preg_replace('/[^a-zA-Z0-9 \-]/', '', $msgClean);
        $msgEncoded = rawurlencode(trim($msgClean)); 
        
        $spaydString = "SPD*1.0*ACC:{$iban}*AM:{$amountFormat}*CC:CZK*MSG:{$msgEncoded}";

        $renderer = new ImageRenderer(new RendererStyle(200), new SvgImageBackEnd());
        $writer = new Writer($renderer);
        $qrSvg = $writer->writeString($spaydString);
        
        $qrBase64 = base64_encode($qrSvg);
        $qr_data_uri = "data:image/svg+xml;base64," . $qrBase64;

        $jmeno_rodice = $validated['parent_name'];
        $datum_format = \Carbon\Carbon::parse($validated['reservation_date'])->format('d. m. Y');
        $hodiny_text = implode(', ', $validated['slot']);
        
        $zobrazeniTerminu = $dateEnd ? "{$datum_format} - " . \Carbon\Carbon::parse($dateEnd)->format('d. m. Y') : $datum_format;
        $vypocet_text = "{$activity->name} ({$zobrazeniTerminu}, {$hodiny_text})";

        $jmeno_ditete_tisk = $validated['child_name'] ?? 'Nezadáno';
        $pocet_deti_tisk = $validated['kidsCount'] ?? 1;
        $zobrazena_cena = number_format($celkova_cena, 0, ',', ' ');






// 6. Příprava dat pro zobrazení (Účtenka + Notifikace)
        $jmeno_rodice = $validated['parent_name'];
        $datum_format = \Carbon\Carbon::parse($validated['reservation_date'])->format('d. m. Y');
        
        // --- ZJEDNODUŠENÍ HODIN (Vytažení prvního a posledního času) ---
        $firstSlot = trim(explode('-', $validated['slot'][0])[0]);
        $lastSlot = trim(explode('-', end($validated['slot']))[1]);
        $zobrazenyCas = "od {$firstSlot} do {$lastSlot}";
        
        $zobrazeniTerminu = $dateEnd ? "{$datum_format} - " . \Carbon\Carbon::parse($dateEnd)->format('d. m. Y') : $datum_format;
        
        // Zde aplikujeme náš hezký čas na výpočet textu pro webovou účtenku
        $vypocet_text = "{$activity->name} ({$zobrazeniTerminu}, {$zobrazenyCas})";

        $jmeno_ditete_tisk = $validated['child_name'] ?? 'Nezadáno';
        $pocet_deti_tisk = $validated['kidsCount'] ?? 1;
        $zobrazena_cena = number_format($celkova_cena, 0, ',', ' ');

        // --- ÚČTENKA BLADE ---
        $success_msg = "
            <div style='max-width: 440px; background: #ffffff; padding: 35px 30px; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); border: 1px solid #f1f5f9; font-family: \"Inter\", sans-serif; margin: 20px auto; text-align: center;'>
                
                <div style='margin-bottom: 25px;'>
                    <div style='background: #08b3cd; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 4px 15px rgba(8, 179, 205, 0.3);'>
                        <i class='fa-solid fa-hourglass-half' style='color: white; font-size: 26px;'></i>
                    </div>
                    <h2 style='font-family: \"Poppins\", sans-serif; font-size: 1.5rem; color: #1e293b; margin: 0; font-weight: 700;'>Žádost je na cestě ke mně!</h2>
                    <p style='font-size: 0.95rem; color: #64748b; margin-top: 12px; line-height: 1.5;'>
                        Rezervaci právě zpracovávám. Jakmile vše osobně zkontroluji, ozvu se vám s potvrzením přes zadaný kontakt.
                    </p>
                </div>

                <div style='background: #f8fafc; border-radius: 16px; padding: 20px; text-align: left; margin-bottom: 25px; border: 1px solid #e2e8f0;'>
                    <ul style='list-style: none; padding: 0; margin: 0; font-size: 0.95rem; color: #334155;'>
                        <li style='padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0;'>
                            <span style='display: block; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;'>Aktivita a termín</span>
                            <span style='font-weight: 500;'>{$vypocet_text}</span>
                        </li>
                        <li style='padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0;'>
                            <span style='display: block; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;'>Pro koho</span>
                            <span style='font-weight: 500;'>{$jmeno_ditete_tisk} ({$pocet_deti_tisk} d.)</span>
                        </li>
                        <li style='padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0;'>
                            <span style='display: block; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;'>Rezervoval/a</span>
                            <span style='font-weight: 500;'>{$jmeno_rodice}</span>
                        </li>
                        <li>
                            <span style='display: block; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;'>Kontakt</span>
                            <span style='font-weight: 500;'>{$validated['contact']}</span>
                        </li>
                    </ul>
                </div>

                <div style='margin-bottom: 30px;'>
                    <div style='font-size: 0.8rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;'>Doporučený příspěvek</div>
                    <div style='font-family: \"Poppins\", sans-serif; font-weight: 800; font-size: 2.2rem; color: #08b3cd; line-height: 1.1; margin-top: 5px;'>{$zobrazena_cena} Kč</div>
                    <div style='margin-top: 15px; font-size: 0.85rem; color: #64748b; line-height: 1.6; background: #f1f5f9; padding: 15px; border-radius: 12px; text-align: left;'>
                         Tato částka je orientační. Pokud vám dává smysl moji práci podpořit více, budu velmi vděčný. Pokud je to naopak přes váš rozpočet, s klidným srdcem si ji v bance upravte. Peníze nesmí být překážkou k radosti. <strong>Platba předem není nutná</strong>, můžeme vyřešit až na místě. 
                    </div>
                </div>

                <!-- BANKOVNÍ PLATBA -->
                <div style='margin-bottom: 25px;'>
                    <img src='{$qr_data_uri}' alt='QR platba' style='width: 200px; height: 200px; border-radius: 16px; border: 1px solid #e2e8f0; padding: 10px; background: white; box-shadow: 0 8px 20px rgba(0,0,0,0.04); display: block; margin: 0 auto;'>
                    <div style='background: #fffbeb; border: 1px solid #fde68a; padding: 12px; border-radius: 12px; font-size: 0.85rem; color: #78350f; margin-top: 15px;'>
                        <div>Účet: <strong>1004823033/3030</strong></div>
                        <div>Zpráva: <strong>{$jmeno_rodice}</strong></div>
                    </div>
                </div>

                <!-- KRYPTOMĚNOVÁ PLATBA -->
                <div style='margin-top: 35px; padding-top: 30px; border-top: 1px dashed #cbd5e1;'>
                    <div style='font-size: 0.85rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px;'>Alternativní způsoby platby</div>
                    
                    <!-- Lightning -->
                    <div style='background: #f8fafc; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 15px;'>
                        <div style='font-size: 0.9rem; font-weight: 700; color: #334155; margin-bottom: 15px;'>
                            <i class='fa-solid fa-bolt' style='color: #f59e0b; margin-right: 6px;'></i> Bitcoin Lightning
                        </div>
                        <img src='/qr-generator?size=300&address=lightning:lno1pgqppmsrse80qf0aara4slvcjxrvu6j2rp5ftmjy4yntlsmsutpkvkt6878s9qs9azm3pgw6qxk2av9srszs8zx25sjt05uy6s6tp69sdwu5ujkvqgpfw80aurytvyptzje2hnw5uypsa0j729tvxmvw7m0tn3endqh6rpcqxvgtghksg6h4v20g0lut65rnxvmlg6wkpnpnlkt28eg5scwtdvx4nfwhaqd5spv8m7pvapxumkdmxjw5j5cq92su5djy75wd2r488gsyh3czugqlzv9j33wsvxz8eyy483p40qpxqqezsfd6s5znhlplwpp5syzzu7839a47rqh2pqy885v0qr0z434yu55zv59jjhtpdz4z5qfd59lglv3tr6ls' alt='Lightning LNURL' style='width: 100%; max-width: 180px; border-radius: 12px; border: 1px solid #cbd5e1; padding: 6px; background: white; display: block; margin: 0 auto;'>
                    </div>

                    <!-- Onchain -->
                    <div style='background: #f8fafc; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0;'>
                        <div style='font-size: 0.9rem; font-weight: 700; color: #334155; margin-bottom: 15px;'>
                            <i class='fa-brands fa-bitcoin' style='color: #f59e0b; margin-right: 6px;'></i> Bitcoin Onchain
                        </div>
                        <img src='/qr-generator?size=300&address=bitcoin:bc1p8p5quw4s8t2ugspr2lf4mz5hqypw52az4hexp9a4nt80kyjxuayqqde2d7' alt='BTC Onchain' style='width: 100%; max-width: 180px; border-radius: 12px; border: 1px solid #cbd5e1; padding: 6px; background: white; display: block; margin: 0 auto;'>
                    </div>
                </div>
            </div>
        ";

        // --- ODESLÁNÍ KOMPLETNÍ NOTIFIKACE NA MOBIL (NTFY) ---
        try {
            $ntfyTag = 'calendar'; 
            if (str_contains($activity->icon, 'compass')) { $ntfyTag = 'compass'; }
            elseif (str_contains($activity->icon, 'map')) { $ntfyTag = 'world_map'; }
            elseif (str_contains($activity->icon, 'laptop') || str_contains($activity->icon, 'code')) { $ntfyTag = 'computer'; }
            elseif (str_contains($activity->icon, 'car')) { $ntfyTag = 'red_car'; }
            elseif (str_contains($activity->icon, 'bow') || str_contains($activity->icon, 'bullseye')) { $ntfyTag = 'dart'; }

            $txtPricing = 'Hodinová sazba';
            if ($validated['pricing'] === 'daily') { $txtPricing = 'Denní paušál'; }
            elseif ($validated['pricing'] === 'monthly') { $txtPricing = 'Měsíční paušál'; }

            $ntfyMessage = "Aktivita: {$activity->name}\n";
            // NTFY teď také rovnou použije náš hezký $zobrazenyCas
            $ntfyMessage .= "Termín: {$zobrazeniTerminu} | {$zobrazenyCas}\n";
            $ntfyMessage .= "Model účtování: {$txtPricing}\n";
            $ntfyMessage .= "Podoba setkání: {$validated['sharing']}\n";
            $ntfyMessage .= "Jméno dítěte: " . ($validated['child_name'] ?? 'Nevyplněno/Skryto') . "\n";
            $ntfyMessage .= "Počet dětí: " . ($validated['kidsCount'] ?? '1 (Skryto)') . "\n";
            $ntfyMessage .= "Věk dětí: " . ($validated['child_info'] ?? 'Nevyplněno') . "\n";
            
            if (!empty($activity->custom_field_label)) {
                $ntfyMessage .= "{$activity->custom_field_label}: " . ($request->input('custom_field') ?? 'Nevyplněno') . "\n";
            }
            
            $ntfyMessage .= "Rodič: {$validated['parent_name']}\n";
            $ntfyMessage .= "Kontakt: {$validated['contact']}\n";
            $ntfyMessage .= "Celková cena: {$zobrazena_cena} Kč\n";
            $ntfyMessage .= "Poznámka: " . ($validated['note'] ?? 'Žádná') . "\n";

            // --- PŘÍPRAVA TLAČÍTEK (AKCÍ) ---
            $actionList = [];
            $googleCalUrl = "";

            if (!empty($validated['slot'])) {
                // Převod pro Google Kalendář používá naše $firstSlot a $lastSlot
                $calDate = \Carbon\Carbon::parse($validated['reservation_date'])->format('Y-m-d');
                $dtStart = \Carbon\Carbon::parse($calDate . ' ' . $firstSlot, 'Europe/Prague')->setTimezone('UTC')->format('Ymd\THis\Z');
                $dtEnd = \Carbon\Carbon::parse($calDate . ' ' . $lastSlot, 'Europe/Prague')->setTimezone('UTC')->format('Ymd\THis\Z');

                $title = urlencode($activity->name . ' - ' . ($validated['child_name'] ?? $validated['parent_name']));
                $details = urlencode("Účastník: " . ($validated['child_name'] ?? 'Nezadáno') . "\nRodič: {$validated['parent_name']}\nKontakt: {$validated['contact']}\nPoznámka: " . ($validated['note'] ?? ''));

                $googleCalUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$title}&dates={$dtStart}/{$dtEnd}&details={$details}";
                $actionList[] = "view, Do kalendáře, '{$googleCalUrl}'";
            }

            // TLAČÍTKO: RYCHLÁ A LIDSKÁ ODPOVĚĎ (Rozděleno na SMS a E-mail)
            $childNameDisplay = !empty($validated['child_name']) ? $validated['child_name'] : "Nezadáno";
            $contactClean = trim($validated['contact']);

            $replyTextEmail = "Dobrý den,\n\nmoc děkuji za rezervaci, kterou tímto potvrzuji. Vše mám poznačené v kalendáři a počítám s vámi. Budu se těšit!\n\nSHRNUTÍ REZERVACE:\n- Aktivita: {$activity->name}\n- Termín: {$zobrazeniTerminu}\n- Čas: {$zobrazenyCas}\n- Účastník: {$childNameDisplay}\n\nKdyby bylo potřeba cokoliv upřesnit, stačí mi napsat.\n\nS pozdravem,\nPetr Lízal\n https://dobrodruzi.cz";
            
            $replyTextSms = "Dobry den, potvrzuji rezervaci aktivity {$activity->name} na termín {$zobrazeniTerminu} ({$zobrazenyCas}). Pocitam s vami a tesim se! Petr Lizal, https://dobrodruzi.cz";
            
            if (filter_var($contactClean, FILTER_VALIDATE_EMAIL)) {
                $subject = rawurlencode("Potvrzení rezervace: {$activity->name}");
                $mailtoUrl = "mailto:{$contactClean}?subject={$subject}&body=" . rawurlencode($replyTextEmail);
                $actionList[] = "view, Potvrdit e-mailem, '{$mailtoUrl}'";
            } else {
                $phoneClean = preg_replace('/[^0-9+]/', '', $contactClean);
                $smsUrl = "sms:{$phoneClean}?body=" . rawurlencode($replyTextSms);
                $actionList[] = "view, Potvrdit přes SMS, '{$smsUrl}'";
            }

            $headers = [
                "Content-Type: text/plain; charset=utf-8",
                "Title: Nová rezervace!",
                "Priority: high",
                "Tags: {$ntfyTag},tada" 
            ];

            if (!empty($actionList)) {
                $headers[] = "Actions: " . implode("; ", $actionList);
            }

            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => $headers,
                    'content' => $ntfyMessage
                ]
            ]);

            file_get_contents('https://ntfy.sh/smsky254', false, $context);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Nepodařilo se odeslat NTFY notifikaci: ' . $e->getMessage());
        }

        return back()->with('success_msg', $success_msg);
    }
    


   /**
     * Vlastní lokální generátor QR kódů (např. pro Bitcoin / LNURL)
     */
    public function generateBtcQr(Request $request)
    {
        $address = $request->query('address');
        $size = (int) $request->query('size', 200);

        // Pokud adresa chybí, vrátíme prázdný obrázek nebo chybu
        if (empty($address)) {
            abort(400, 'Adresa chybí.');
        }

        // Vygenerování SVG
        $renderer = new ImageRenderer(
            new RendererStyle($size),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrSvg = $writer->writeString($address);

        // Vrácení vygenerovaného SVG kódu jako obrázek
        return response($qrSvg)->header('Content-Type', 'image/svg+xml');
    }
}