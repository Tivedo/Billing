<?php

return[
    'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-IDLrh9ymddLTiP82IAJtUKAs'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-roUsax0itR_rQsxo'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true)
];