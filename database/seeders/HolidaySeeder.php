<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $holidays = [
            ['date' => '2025-01-01', 'name' => 'New Year Day'],
            ['date' => '2025-12-24', 'name' => 'Christmas Eve Day'],
            ['date' => '2025-12-25', 'name' => 'Christmas Day'],
        ];

        foreach ($holidays as $holiday) {
            Holiday::firstOrCreate($holiday);
        }
    }
}
