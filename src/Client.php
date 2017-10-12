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
                'ContentT-ype' => 'text/xml; charset=UTF8',
            ],
            'body' => $xml,
            'http_errors' => false
        ];

        $client = new \GuzzleHttp\Client();

        $res = $client->request($method, $url, $request);

        $response = false;

        if ($res->getStatusCode() === 200) {
            //libxml_use_internal_errors(true);
            $xmlObject = simplexml_load_string((string)$res->getBody());
            json_decode(json_encode($xmlObject));
        }

        return $response;
    }

    public static function mockRequest($url, $xml, $method = 'POST') {
        $xmlResult = '<?xml version="1.0" encoding="utf-8"?><API3G><Result>000</Result><ResultExplanation>Transaction created</ResultExplanation><TransToken>3BC4A4F8-3EF2-4E2C-89EE-6939F1E83AE8</TransToken><TransRef>8403BC4A</TransRef></API3G>';

        $xmlObject = simplexml_load_string($xmlResult);

        return json_decode(json_encode($xmlObject));
    }

    private function getCleanedResponse($dpoResponse) {

    }
}
