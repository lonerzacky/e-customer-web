<?php
return [
    'companyName'    => 'KBPR Wedarijaksa',
    'tagLine'        => 'Solusi keuangan lokal yang terpercaya',
    'hide_module' => [
        'tabungan'   => env('MODULE_HIDE_TABUNGAN', false),
        'deposito'   => env('MODULE_HIDE_DEPOSITO', false),
        'kredit'     => env('MODULE_HIDE_KREDIT', false),
        'portofolio' => env('MODULE_HIDE_PORTOFOLIO', false),
    ],

];
