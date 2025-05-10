<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

		{
			$times = [
				'08:00 AM',
				'10:00 AM',
				'12:00 PM',
				'02:00 PM',
				'04:00 PM',
				'06:00 PM'
			];
	
			foreach ($times as $time) {
				TimeSlot::create([
					'time' => $time,
					'active' => true
				]);
			}
		}
    }
}
