<?php

use App\Models\Holiday;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LeaveDayCalculator
{
    public static function getWorkingDays(string $startDate, string $endDate, bool $isHalfDay = false): float
    {
        $start = Carbon::createFromFormat('Y-m-d', $startDate);
        $end = Carbon::createFromFormat('Y-m-d', $endDate);

        $period = CarbonPeriod::create($start, $end);
        $holidays = Holiday::pluck('date')->toArray();

        $count = 0;

        // Filters out weekends and holidays from the applied leave date range and counts only valid working days
        foreach ($period as $date) {
            if ($date->isWeekend()) continue;
            if (in_array($date->format('Y-m-d'), $holidays)) continue;

            $count++;
        }

        return $isHalfDay ? 0.5 : $count;
    }
}
