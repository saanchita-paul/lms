<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CrmStatusesSeeder extends Seeder {
    public function run() {
        DB::table('crm_statuses')->insert([
            'name'       => 'Treatments Completed',
            'board'      => 'NA',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}