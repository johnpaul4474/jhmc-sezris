<?php
namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Locator\Holiday;


class PermitHelper
{
    public static function computeValidity(int $days)
    {
        $date = Carbon::now();
        $added = 0;

        // Get all holiday dates (format to Y-m-d for easy comparison)
        $holidays = Holiday::pluck('date')->map(function ($d) {
            return Carbon::parse($d)->format('Y-m-d');
        })->toArray();

        while ($added < $days) {
            $date->addDay();

            // Skip weekends
            if (in_array($date->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                continue;
            }

            // Skip holidays
            if (in_array($date->format('Y-m-d'), $holidays)) {
                continue;
            }

            $added++;
        }

        return $date;
    }
    
}
