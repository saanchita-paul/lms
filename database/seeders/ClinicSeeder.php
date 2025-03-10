<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Clinic;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            '1' => 'false',
            '2' => 'false',
            '3' => 'false',
            '4' => 'false',
            '5' => 'false',
            '6' => 'false',
            '7' => 'false',
            '8' => 'false',
            '9' => 'false',
            '11' => 'false',
            '12' => 'false',
            '13' => 'false',
            '14' => 'false',
            '15' => 'false',
            '16' => 'false',
            '17' => 'false',
        ];

        // Update the automation_campaign field for each clinic
        Clinic::all()->each(function ($clinic) use ($data) {
            $clinic->update(['automation_campaign' => json_encode($data)]);
        });
    }
}
