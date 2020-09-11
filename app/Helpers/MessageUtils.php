<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class MessageUtils
{
    public static function clear()
    {
        Session::remove('message');
        Session::remove('messageType');
    }

    public static function success($message)
    {
        Session::flash('message', $message);
        Session::flash('messageType', 'success');
    }

    public static function warning($message)
    {
        Session::flash('message', $message);
        Session::flash('messageType', 'warning');
    }

    public static function error($message)
    {
        Session::flash('message', $message);
        Session::flash('messageType', 'danger');
    }

    public static function info($message)
    {
        Session::flash('message', $message);
        Session::flash('messageType', 'info');
    }
}
