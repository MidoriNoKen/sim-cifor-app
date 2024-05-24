<?php

namespace App\Console\Commands;

use App\Http\Services\HolidayService as ServicesHolidayService;
use Illuminate\Console\Command;
use App\Services\HolidayService;

class UpdateHolidays extends Command
{
    protected $signature = 'holidays:update';
    protected $description = 'Update national holidays data';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $holidayService = new ServicesHolidayService();
        try {
            $holidayService->updateHolidays();
            $this->info('National holidays updated successfully.');
        } catch (\Exception $e) {
            $this->error('Failed to update holidays: ' . $e->getMessage());
        }
    }
}
