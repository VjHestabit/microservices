<?php

namespace App\Helpers;

class CustomHelper {
    const ADMIN = 999;
    const STUDENT = 100;
    const TEACHER = 101;

    public static $userType = [
        self::STUDENT => 'Student',
        self::TEACHER => 'Teacher',
    ];

    const ASSIGNED = 1;
    const UNASSIGNED = 2;

    public static $studentStatus = [
        self::ASSIGNED => 'Assigned',
        self::UNASSIGNED => 'Un Assigned'
    ];

    public static $studentBtnStatus = [
        self::ASSIGNED => 'btn-success',
        self::UNASSIGNED => 'btn-danger'
    ];

    const APPROVE = 1;
    const NOTAPPROVE = 2;

    public static $getUserStatus = [
        self::APPROVE => 'Approved',
        self::NOTAPPROVE => 'Not Approved'
    ];

    public static $getUserBtnStatus = [
        self::APPROVE => 'btn-success',
        self::NOTAPPROVE => 'btn-danger'
    ];

    const LESSTHAN1= 1;
    const ONETOTWO = 2;
    const TWOTOTHREE = 3;
    const THREETOFOUR = 4;
    const MORETHAN5 = 5;

    public static $getTeacherExp = [
        self::LESSTHAN1 => "Less than 1 Year",
        self::ONETOTWO => "1 to 2 Year",
        self::TWOTOTHREE => "2 to 3 Year",
        self::THREETOFOUR => "3 to 4 Year",
        self::MORETHAN5 => "More than 5 Year"
    ];

    const MATH = 1;
    const SCIENCE = 2;
    const ENGLISH = 3;
    const HISTORY = 4;
    const COMPUTER = 5;

    public static $getSubjectList = [
        self::MATH => 'Maths',
        self::SCIENCE => 'Science',
        self::ENGLISH => 'English',
        self::HISTORY => 'History',
        self::COMPUTER => 'Computers'
    ];

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

        $result = curl_exec($curl);

        curl_close($curl);

        return ($result);
    }
}

?>
