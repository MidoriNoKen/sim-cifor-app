<?php

namespace App\Utils;

use DateTime;

class Util
{
    private static $months = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    public static function formatDate($date)
    {
        return (new DateTime($date))->format('d M Y H:i');
    }

    public static function defaultDate($date)
    {
        return (new DateTime($date))->format('d M Y');
    }

    public static function formatDateToIndonesian($date)
    {

        $dateTime = new DateTime($date);
        $day = $dateTime->format('d');
        $month = self::$months[(int)$dateTime->format('m')];
        $year = $dateTime->format('Y');

        return "{$day} {$month} {$year}";
    }

    public static function getDateTimeDifference($start, $end)
    {
        $startDateTime = date_create($start);
        $endDateTime = date_create($end);

        $startDate = $startDateTime->format('Y-m-d');
        $endDate = $endDateTime->format('Y-m-d');

        $startTime = $startDateTime->format('H:i');
        $endTime = $endDateTime->format('H:i');

        if ($startDate === $endDate) {
            if ($startTime === $endTime) return '1 Hari';
            return date_diff($startDateTime, $endDateTime)->format('%h Jam %i Menit');
        }
        return (date_diff($startDateTime, $endDateTime)->format('%a') + 1) . ' Hari';
    }

    public static function getPagination($request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 5);
        return [$page, $perPage];
    }
}