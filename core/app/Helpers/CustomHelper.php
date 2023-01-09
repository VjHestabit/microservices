<?php

namespace App\Helpers;

class CustomHelper {

    const ADMIN = 999;
    const STUDENT = 100;
    const TEACHER = 101;

    const USERAPI = 'http://127.0.0.1:8001/api/';
    const NOTIFYAPI = 'http://127.0.0.1:8002/api/';

    public static function Call_Api($method  =  'GET', $url  =  '', $body  =  '')
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . config('constant.HASH_KEY')));

        if ($method  ==  'GET') {
            curl_setopt($curl, CURLOPT_POST, false);
            // curl_setopt($curl, CURLOPT_SSLVERSION,3);
        }

        if ($method  ==  'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }

        if ($method  ==  'PUT') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }

        $result = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($result,false);
        // echo "<pre>";
        // print_r($data);
        // die;
        return ($data);
    }
}
