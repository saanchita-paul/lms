<?php

namespace Database\Seeders;

use App\Models\CrmStatus;
use Illuminate\Database\Seeder;

class CrmStatusTableSeeder extends Seeder
{
    public function run()
    {
        $crmStatuses = [
            [
                'id'         => 1,
                'name'       => 'New Lead',
                'created_at' => '2021-03-08 12:16:47',
                'updated_at' => '2021-03-08 12:16:47',
            ],
            [
                'id'         => 2,
                'name'       => 'Attempt One',
                'created_at' => '2021-03-08 12:16:47',
                'updated_at' => '2021-03-08 12:16:47',
            ],
            [
                'id'         => 3,
                'name'       => 'Attempt Two',
                'created_at' => '2021-03-08 12:16:47',
                'updated_at' => '2021-03-08 12:16:47',
            ],
            [
                'id'         => 4,
                'name'       => 'Attempt Three',
                'created_at' => '2021-03-08 12:16:47',
                'updated_at' => '2021-03-08 12:16:47',
            ],
             [
                'id'         => 10,
                'name'       => 'Attempt Four Plus',
                'created_at' => '2021-03-08 12:16:47',
                'updated_at' => '2021-03-08 12:16:47',
            ],
            [
                'id'         => 5,
                'name'       => 'In Discussion',
                'created_at' => '2021-03-08 12:16:47',
                'updated_at' => '2021-03-08 12:16:47',
            ],
            [
                'id'         => 6,
                'name'       => 'Doctor Follow-Up',
                'created_at' => '2021-03-08 12:16:47',
                'updated_at' => '2021-03-08 12:16:47',
            ],
            [
                'id'         => 7,
                'name'       => 'Consultation Booked',
                'created_at' => '2021-03-08 12:16:47',
                'updated_at' => '2021-03-08 12:16:47',
            ],
            [
                'id'         => 8,
                'name'       => 'No Showed',
                'created_at' => '2021-03-08 12:16:47',
                'updated_at' => '2021-03-08 12:16:47',
            ],
            [
                'id'         => 9,
                'name'       => 'Not Interested Or Did not schedule',
                'created_at' => '2021-03-08 12:16:47',
                'updated_at' => '2021-03-08 12:16:47',
            ],
            [
                'id'         => 11,
                'name'       => 'Cancellation',
                'created_at' => '2021-03-08 12:16:47',
                'updated_at' => '2021-03-08 12:16:47',
            ]
        ];

        CrmStatus::insert($crmStatuses);
    }
}
