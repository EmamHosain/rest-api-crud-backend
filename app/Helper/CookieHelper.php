<?php
namespace App\Helper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CookieHelper
{
    public static function setCookie($cookieName, $value)
    {
        // set cookie
        Cookie::queue($cookieName, $value, 60*24*30);
        return;
    }

    public static function cookieDestroy($cookieName)
    {
        // cookie destroy
        Cookie::queue($cookieName, '', -1);
        return;
    }
    public static function getCookieByName($name)
    {
        $result = request()->cookie($name);
        return $result;

    }

}