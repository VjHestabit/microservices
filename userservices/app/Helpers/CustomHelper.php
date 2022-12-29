<?php

namespace App\Helpers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class CustomHelper{

    const ADMIN = 999;
    const STUDENT = 100;
    const TEACHER = 101;

    public static $userType = [
        self::ADMIN => 'Admin',
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
}
?>
