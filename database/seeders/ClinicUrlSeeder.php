<?php

namespace Database\Seeders;

use App\Models\Clinic;
use Illuminate\Database\Seeder;

class ClinicUrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $salesTrainingUrl = 'https://calendly.com/kim-middleton/discovery-call-clone';
//        $marketingDashboardUrl = 'https://calendly.com/lmoore-2020/30min';
//        $scheduleMeetingUrl = 'https://calendar.app.google/tHJraewsvXLWHPhU6';
        $successCoachUrl = 'https://calendar.app.google/mdy7S9c8V7KfoT7Q8';

        Clinic::query()->update([
//            'salestrainingurl' => $salesTrainingUrl,
//            'marketingdashboardurl' => $marketingDashboardUrl,
//            'schedulemeetingurl' => $scheduleMeetingUrl,
            'success_coach_url' => $successCoachUrl,
        ]);
    }
}
