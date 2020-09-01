<?php
return [
    'merchant_code' => env('FAWRY_MERCHANT_CODE',''),
    'security_key' => env('FAWRY_SECURITY_KEY',''),
    'settings' => array(
      
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        // 'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR'
    ),
];