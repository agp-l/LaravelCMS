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
        $activities = \App\Models\Activity::all(); // Načte aktivity z databáze
        return view('reservation', compact('activities')); // Pošle je do šablony
    }

public function store(Request $request)
    {
        // 1. Validace prichozich dat
        $validated = $request->validate([
            'activity_id'      => 'required|integer|exists:activities,id', // Opraveno: Čekáme ID aktivity
            'reservation_date' => 'required|date',
            'slot'             => 'required|array',
            'child_name'       => 'required|string|max:255',
            'kidsCount'        => 'required|integer|min:1',
            'parent_name'      => 'required|string|max:255',
            'contact'          => 'required|string|max:255',
            'pricing'          => 'required|string',
            'sharing'          => 'required|string'
            // Poznámka: 'activities' pole jsme zrušili
        ]);

        // 2. Načtení vybrané aktivity z databáze (kvůli zjištění přesné ceny)
        $activity = Activity::findOrFail($validated['activity_id']);

        // 3. Výpočet ceny
        $pocet_hodin = count($validated['slot']);
        
        if ($validated['pricing'] === 'Celodenní parťák') {
            $celkova_cena = 1500;
        } else {
            // Cena se teď počítá přesně podle toho, jakou má vybraná aktivita nastavenou hodinovou sazbu
            $celkova_cena = $pocet_hodin * $activity->price_per_hour; 
        }

        // 4. Ulozeni do databaze
        try {
            $reservation = Reservation::create([
                'date'           => $validated['reservation_date'],
                'slots'          => $validated['slot'],
                'child_name'     => $validated['child_name'],
                'kids_count'     => $validated['kidsCount'],
                'child_info'     => '', 
                'parent_name'    => $validated['parent_name'],
                'contact'        => $validated['contact'],
                'note'           => '', 
                'pricing_model'  => $validated['pricing'],
                'sharing_type'   => $validated['sharing'],
                'total_price'    => $celkova_cena,
                'payment_status' => 'pending',
                'activity_id'    => $activity->id // Ukládáme správné ID
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Chyba databáze: ' . $e->getMessage()]);
        }

       // 5. Generovani QR kodu pro platbu
        $iban = "CZ6830300000001004823033"; 
        $amountFormat = number_format($celkova_cena, 2, '.', '');
        
        $msgClean = iconv('UTF-8', 'ASCII//TRANSLIT', $validated['parent_name']);
        $msgClean = preg_replace('/[^a-zA-Z0-9 \-]/', '', $msgClean);
        $msgEncoded = rawurlencode(trim($msgClean)); 
        
        $spaydString = "SPD*1.0*ACC:{$iban}*AM:{$amountFormat}*CC:CZK*MSG:{$msgEncoded}";

        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
        $writer = new \BaconQrCode\Writer($renderer);
        $qrSvg = $writer->writeString($spaydString);
        
        $qrBase64 = base64_encode($qrSvg);
        $qr_data_uri = "data:image/svg+xml;base64," . $qrBase64;

        // --- PŘÍPRAVA PROMĚNNÝCH PRO TVOU PŘEDLOHU ---
        $jmeno_rodice = $validated['parent_name'];
        $datum_format = \Carbon\Carbon::parse($validated['reservation_date'])->format('d. m. Y');
        $hodiny_text = implode(', ', $validated['slot']);
        
        // Vytvoříme přehledný text pro řádek "Aktivita"
        $vypocet_text = "{$activity->name} ({$datum_format}, {$hodiny_text})";

        // --- TVÁ GENIÁLNÍ PŘEDLOHU PRO ÚČTENKU ---
       $success_msg = "
            <div style='max-width: 420px; background: #ffffff; padding: 35px 30px; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); border: 1px solid #f1f5f9; font-family: \"Inter\", sans-serif; margin: 20px auto; text-align: center;'>
                
                <div style='margin-bottom: 25px;'>
                    <div style='background: #08b3cd; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 4px 15px rgba(8, 179, 205, 0.3);'>
                        <i class='fa-solid fa-check' style='color: white; font-size: 28px;'></i>
                    </div>
                    <h2 style='font-family: \"Poppins\", sans-serif; font-size: 1.6rem; color: #1e293b; margin: 0; font-weight: 700;'>Skvělé, je to vaše!</h2>
                    <p style='font-size: 0.9rem; color: #64748b; margin-top: 8px;'>Termín je zarezervovaný. Nyní už chybí jen platba.</p>
                </div>

                <div style='background: #f8fafc; border-radius: 16px; padding: 20px; text-align: left; margin-bottom: 25px; border: 1px solid #e2e8f0;'>
                    <div style='margin-bottom: 15px;'>
                        <div style='font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;'>Co nás čeká</div>
                        <div style='font-size: 1rem; font-weight: 600; color: #334155; margin-top: 2px; line-height: 1.4;'>{$vypocet_text}</div>
                    </div>
                    <div style='margin-bottom: 15px;'>
                        <div style='font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;'>Pro koho</div>
                        <div style='font-size: 1rem; font-weight: 600; color: #334155; margin-top: 2px;'>{$validated['child_name']} ({$validated['kidsCount']} d.)</div>
                    </div>
                    <div>
                        <div style='font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;'>Rezervoval/a</div>
                        <div style='font-size: 1rem; font-weight: 600; color: #334155; margin-top: 2px;'>{$jmeno_rodice}</div>
                    </div>
                </div>

                <div style='margin-bottom: 30px;'>
                    <div style='font-size: 0.8rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;'>Doporučený příspěvek</div>
                    <div style='font-family: \"Poppins\", sans-serif; font-weight: 800; font-size: 2.2rem; color: #08b3cd; line-height: 1.1; margin-top: 5px;'>{$celkova_cena} Kč</div>
                    
                    <div style='margin-top: 15px; font-size: 0.85rem; color: #64748b; line-height: 1.6; background: #f1f5f9; padding: 15px; border-radius: 12px; text-align: left;'>
                        Tato částka je orientační. Pokud vám dává smysl moji práci podpořit více, budu velmi vděčný. Pokud je to naopak přes váš rozpočet, s klidným srdcem si ji v bance upravte. <strong>Peníze nesmí být překážkou k radosti.</strong>
                    </div>
                </div>

                <div style='margin-bottom: 25px;'>
                    <img src='{$qr_data_uri}' alt='QR platba' style='width: 240px; height: 240px; border-radius: 16px; border: 1px solid #e2e8f0; padding: 10px; background: white; box-shadow: 0 8px 20px rgba(0,0,0,0.04); display: block; margin: 0 auto;'>
                    <p style='font-size: 0.85rem; color: #64748b; margin-top: 15px; font-weight: 500; margin-bottom: 0;'>Otevřete bankovní aplikaci a naskenujte QR kód.</p>
                </div>

                <div style='background: #fffbeb; border: 1px solid #fde68a; padding: 15px; border-radius: 12px; font-size: 0.9rem; color: #78350f;'>
                    <div style='margin-bottom: 5px;'>Účet: <strong>1004823033/3030</strong></div>
                    <div>Zpráva: <strong>{$jmeno_rodice}</strong></div>
                </div>

                <p style='font-size: 0.8rem; color: #94a3b8; margin-top: 25px; margin-bottom: 0;'>
                    Moc se těším na společné dobrodružství!
                </p>
            </div>
        ";
        
        
        return back()->with('success_msg', $success_msg);
    }
}