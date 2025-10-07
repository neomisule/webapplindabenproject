<?php

namespace Database\Seeders;

use App\Models\Ngo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class NgoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ngo::truncate();

        $ngos = [
            [
                'name' => 'LindaBen Foundation',
                'slug' => 'lindaben-foundation-1',
                'address' => '10739 Tucker St, ste 222, Beltsville, MD 20705',
                'date' => Carbon::parse('2025-12-15'),
                'start_time' => '09:00:00',
                'end_time' => '14:00:00',
                'volunteers_needed' => 20,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'LindaBen Foundation',
                'slug' => 'lindaben-foundation-2',
                'address' => '10739 Tucker St, ste 222, Beltsville, MD 20705',
                'date' => Carbon::parse('2025-12-18'),
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
                'volunteers_needed' => 15,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'LindaBen Foundation',
                'slug' => 'lindaben-foundation-3',
                'address' => '9770 Patuxent Woods Dr, Ste 333 Columbia, MD 21046',
                'date' => Carbon::parse('2025-07-26'),
                'start_time' => '10:00:00',
                'end_time' => '16:00:00',
                'volunteers_needed' => 25,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'LindaBen Foundation',
                'slug' => 'lindaben-foundation-4',
                'address' => '9770 Patuxent Woods Dr, Ste 333 Columbia, MD 21046',
                'date' => Carbon::parse('2025-07-22'),
                'start_time' => '07:30:00',
                'end_time' => '17:00:00',
                'volunteers_needed' => 30,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Ngo::insert($ngos);
    }
}
