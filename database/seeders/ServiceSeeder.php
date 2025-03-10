<?php

namespace Database\Seeders;

use App\Models\Services;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Services::truncate();

        DB::table('services')->insert([
            [
                'name' => 'Routine Check-Up',
            ],
            [
                'name' => 'Hygiene',
            ],
            [
                'name' => 'Tooth Extractions',
            ],
            [
                'name' => 'Emergency',
            ],
            [
                'name' => 'Implants',
            ],
            [
                'name' => 'Sedation',
            ],
            [
                'name' => 'Cosmetic',
            ],
            [
                'name' => 'Orthodontics',
            ],
            [
                'name' => 'Other',
            ]
        ]);
    }
}
