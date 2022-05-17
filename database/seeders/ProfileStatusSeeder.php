<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profile_statuses')->insert([
            [
                'id' => '1',
                'name' => 'Wait for CV screening',
                'profile_status_group_id' => '1',
            ],
            [
                'id' => '2',
                'name' => 'Wait for interview arrangement',
                'profile_status_group_id' => '2',
            ],
            [
                'id' => '3',
                'name' => 'Wait for interview',
                'profile_status_group_id' => '2',
            ],
            [
                'id' => '4',
                'name' => 'Wait for interview result',
                'profile_status_group_id' => '2',
            ],
            [
                'id' => '5',
                'name' => 'Can not contact',
                'profile_status_group_id' => '3',
            ], 
            [
                'id' => '6',
                'name' => 'Candidate rejected interview',
                'profile_status_group_id' => '3',
            ], 
            [
                'id' => '7',
                'name' => 'Failed the Interview',
                'profile_status_group_id' => '3',
            ],
            [
                'id' => '8',
                'name' => 'Pass',
                'profile_status_group_id' => '4',
            ]
        ]);
    }
}
