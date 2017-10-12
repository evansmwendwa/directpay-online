<?php

namespace Evans\Directpay;

/**
 * Client Class
 *
 * @author Evans Mwendwa
 */
class Client
{
    public static function sendXMLRequest($url, $xml, $method = 'POST') {

        $request = [
            'headers' => [
                'ContentT-ype' => 'text/xml',
            ],
            'body' => $xml,
            'http_errors' => false
        ];

        $client = new \GuzzleHttp\Client();

        $res = $client->request($method, $url, $request);

        $response = false;

        if ($res->getStatusCode() === 200) {
            $xmlObject = simplexml_load_string((string)$res->getBody());

            if(false !== $xmlObject) {
                $response =  json_decode(json_encode($xmlObject));
            }

        }

        return $response;
    }
}
