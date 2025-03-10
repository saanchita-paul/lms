<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Seeder;

class SourcesTableSeeder extends Seeder
{
    public function run()
    {
        $sources = [
            [
                'id'                => 1,
                'source_name'       => 'Direct',
                'created_at'        => '2021-03-08 12:16:47',
                'updated_at'        => '2021-03-08 12:16:47',
            ],
            [
                'id'                => 2,
                'source_name'       => 'Google Ads',
                'created_at'        => '2021-03-08 12:16:47',
                'updated_at'        => '2021-03-08 12:16:47',
            ],
            [
                'id'                => 3,
                'source_name'       => 'Facebook',
                'created_at'        => '2021-03-08 12:16:47',
                'updated_at'        => '2021-03-08 12:16:47',
            ],
            [
                'id'                => 4,
                'source_name'       => 'Google Organic',
                'created_at'        => '2021-03-08 12:16:47',
                'updated_at'        => '2021-03-08 12:16:47',
            ],
             [
                'id'                => 5,
                'source_name'       => 'Web',
                'created_at'        => '2021-03-08 12:16:47',
                'updated_at'        => '2021-03-08 12:16:47',
            ],
            [
                'id'                => 6,
                'source_name'       => 'Youtube',
                'created_at'        => '2021-03-08 12:16:47',
                'updated_at'        => '2021-03-08 12:16:47',
            ],
            [
                'id'                => 7,
                'source_name'       => 'Email List',
                'created_at'        => '2021-03-08 12:16:47',
                'updated_at'        => '2021-03-08 12:16:47',
            ],
            [
                'id'                => 8,
                'source_name'       => 'Yahoo',
                'created_at'        => '2021-03-08 12:16:47',
                'updated_at'        => '2021-03-08 12:16:47',
            ],
            [
                'id'                => 9,
                'source_name'       => 'MSN',
                'created_at'        => '2021-03-08 12:16:47',
                'updated_at'        => '2021-03-08 12:16:47',
            ],
            [
                'id'                => 10,
                'source_name'       => 'Bing',
                'created_at'        => '2021-03-08 12:16:47',
                'updated_at'        => '2021-03-08 12:16:47',
            ],
            [
                'id'                => 11,
                'source_name'       => 'Other',
                'created_at'        => '2021-03-08 12:16:47',
                'updated_at'        => '2021-03-08 12:16:47',
            ],
            [
                'id'                => 12,
                'source_name'       => 'TV',
                'created_at'        => '2021-03-08 12:16:47',
                'updated_at'        => '2021-03-08 12:16:47',
            ],
            [
                'id'                => 13,
                'source_name'       => 'Infomercial',
                'created_at'        => '2021-03-08 12:16:47',
                'updated_at'        => '2021-03-08 12:16:47',
            ],
            [
                'id'                => 14,
                'source_name'       => 'Radio',
                'created_at'        => '2021-03-08 12:16:47',
                'updated_at'        => '2021-03-08 12:16:47',
            ]
        ];

        Source::insert($sources);
    }
}
