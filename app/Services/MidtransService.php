<?php

namespace App\Services;

use Midtrans\Midtrans;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        // Set the Midtrans configuration
        $this->configureMidtrans();
    }

    public function configureMidtrans()
    {
        
        $environment = 'sandbox'; // Set to 'production' for production environment
        $serverKey =  'SB-Mid-server-IDLrh9ymddLTiP82IAJtUKAs';
        $clientKey = 'SB-Mid-client-roUsax0itR_rQsxo';

        // Configure the Midtrans SDK
        Config::$serverKey = $serverKey;
        Config::$clientKey = $clientKey;
        Config::$isProduction = ($environment === 'production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createSnapToken(array $param) {
        try {
            return Snap::getSnapToken($param);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}