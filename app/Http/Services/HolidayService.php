<?php

namespace App\Http\Services;

use App\Models\Holiday;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class HolidayService
{
    private $apiUrl;
    private $apiKey;

    public function __construct()
    {
        $this->apiUrl = env('HOLIDAY_API_URL');
        $this->apiKey = env('HOLIDAY_API_KEY');
    }

    public function updateHolidays()
    {
        try {
            $response = Http::get($this->apiUrl, [
                'key' => $this->apiKey,
                'timeMin' => '2024-01-01T00:00:00Z',
                'timeMax' => '2024-12-31T23:59:59Z',
                'q' => 'Hari Libur Nasional'
            ]);

            if ($response->successful()) {
                $events = $response->json()['items'];

                foreach ($events as $event) {
                    $startDate = $event['start']['date'];
                    $endDate = $event['end']['date'];

                    Holiday::updateOrCreate(
                        ['start_date' => $startDate, 'end_date' => $endDate],
                        ['name' => $event['summary'], 'description' => $event['description'] ?? 'Hari libur nasional']
                    );
                }
            } else {
                throw new \Exception('Failed to fetch holidays from Google Calendar API.');
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function isHoliday($date)
    {
        $holiday = Holiday::whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();

        return $holiday ? true : false;
    }

    public function getHolidaysInRange($start_date, $end_date)
    {
        $holidays = Holiday::whereBetween('start_date', [$start_date, $end_date])
            ->orWhereBetween('end_date', [$start_date, $end_date])
            ->get();

        return $holidays;
    }

    public function splitDateRange($start_date, $end_date, $holidays)
    {
        $dateRanges = [];
        $currentStartDate = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);

        foreach ($holidays as $holiday) {
            $holidayStartDate = Carbon::parse($holiday->start_date);
            $holidayEndDate = $holiday->end_date ? Carbon::parse($holiday->end_date) : $holidayStartDate;

            if ($currentStartDate->equalTo($end_date) && $currentStartDate->between($holidayStartDate, $holidayEndDate)) {
                throw new \Exception("Tanggal {$currentStartDate->format('d M Y')} adalah hari Libur Nasional");
            }

            if ($currentStartDate->lessThan($holidayStartDate)) {
                $dateRanges[] = [
                    'start_date' => $currentStartDate,
                    'end_date' => $holidayStartDate->copy()->subDay()
                ];
            }

            $currentStartDate = $holidayEndDate->copy()->addDay();
        }

        if ($currentStartDate->lessThanOrEqualTo($end_date)) {
            $dateRanges[] = [
                'start_date' => $currentStartDate,
                'end_date' => $end_date
            ];
        }

        return $dateRanges;
    }
}
