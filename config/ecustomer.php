<?php
return [
    'companyName'    => 'KBPR Wedarijaksa',
    'tagLine'        => 'Solusi keuangan lokal yang terpercaya',
    'hide_dashboard' => [
        'tabungan'   => env('DASHBOARD_HIDE_TABUNGAN', false),
        'deposito'   => env('DASHBOARD_HIDE_DEPOSITO', false),
        'kredit'     => env('DASHBOARD_HIDE_KREDIT', false),
        'portofolio' => env('DASHBOARD_HIDE_PORTOFOLIO', false),
    ],

];
