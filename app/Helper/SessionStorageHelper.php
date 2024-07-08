<?php
namespace App\Helper;

use Illuminate\Support\Facades\Session;

class SessionStorageHelper
{
    public static function setItemToSessionStorage($itemName, $value)
    {
        session([$itemName => $value]);
        return;
    }

    public static function getItemFromSessionStorage($itemName)
    {
        $value = session($itemName);
        return $value;
    }

    public static function destroyItemFromSessionStorage($itemName)
    {
        Session::pull($itemName);
        return;
    }
}