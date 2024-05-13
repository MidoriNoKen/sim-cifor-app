<?php

namespace App\Utils;

use DateTime;

class Util
{
    public static function formatDate($date)
    {
        return (new DateTime($date))->format('d-m-Y H:i:s');
    }
}