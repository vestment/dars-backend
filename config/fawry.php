<?php
return [
    'merchant_code' => env('PAYPAL_CLIENT_ID',''),
    'security_key' => env('PAYPAL_SECRET',''),
    'settings' => array(
      
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR'
    ),
];