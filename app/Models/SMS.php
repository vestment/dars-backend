<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;





class SMS {

    protected static function buildHttpClient()
    {
        return new Client([
            'base_uri' => "https://smsvas.vlserv.com/KannelSending/service.asmx/SendSMS",
        ]);
    }
    /**
     * @param string $message
     * @param string $to
     * @param string|null $from
     * @return \Psr\Http\Message\ResponseInterface
     */






    public static function send(string $message ,string  $to , string $username ,string $password , string $sms_provider)
    {
        
        
        $client = self::buildHttpClient();
     
       
        $response = $client->request('POST', [
            'query' => [
                'username' => $username,
                'password' => $password,
                'sender' => $sms_provider,
                'language' => "en",
                'message' => $message,
                'to' => $to,
               
            ]
        ]);
       
       
        $array = json_decode($response->getBody(), true) ;
        return $array;
    }
}