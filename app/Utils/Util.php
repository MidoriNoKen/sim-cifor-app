<?php

namespace App\Utils;

use DateTime;

class Util
{
    public static function formatDate($date)
    {
        return (new DateTime($date))->format('d M Y H:i');
    }

    public static function defaultDate($date)
    {
        return (new DateTime($date))->format('d M Y');
    }

    public static function getDateTimeDifference($start, $end)
    {
        if (Util::defaultDate($start) === (Util::defaultDate($end)))
            return (date_diff(date_create($start), date_create($end))->format('%h') . ' Jam');

        return (date_diff(date_create($start), date_create($end))->format('%a') + 1) . ' Hari';
    }
}