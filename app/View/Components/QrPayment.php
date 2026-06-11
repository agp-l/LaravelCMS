<?php

namespace App\View\Components;

use Illuminate\View\Component;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;

class QrPayment extends Component
{
    public $qrDataUri;

    public function __construct($vs = '1050', $msg = 'dar', $size = 200, $iban = null, $amount = null)
    {
        // 1. Zpracování IBANu (pokud chybí, použije se tvůj reálný)
        $resolvedIban = $iban ?? "CZ6830300000001004823033";
        $msgEncoded = rawurlencode($msg);

        // 2. Základní SPAYD řetězec
        $spaydString = "SPD*1.0*ACC:{$resolvedIban}*CC:CZK*X-VS:{$vs}*MSG:{$msgEncoded}";
        
        // 3. Přidání částky (AM) s přesným bankovním formátováním na 2 desetinná místa
        if (!empty($amount)) {
            // Převede např. "1500" na "1500.00"
            $formattedAmount = number_format((float)$amount, 2, '.', '');
            $spaydString .= "*AM:{$formattedAmount}";
        }

        // 4. Vykreslení přes BaconQrCode
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle($size),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
        $writer = new \BaconQrCode\Writer($renderer);
        $qrSvg = $writer->writeString($spaydString);

        $this->qrDataUri = "data:image/svg+xml;base64," . base64_encode($qrSvg);
    }

    public function render()
    {
        return view('components.qr-payment');
    }
}