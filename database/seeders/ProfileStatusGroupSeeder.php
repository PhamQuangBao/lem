<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileStatusGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profile_status_groups')->insert([
            [
                'id' => '1',
                'name' => 'New application',
            ],
            [
                'id' => '2',
                'name' => 'In progress',
            ],
            [
                'id' => '3',
                'name' => 'Unqualified',
            ],
            [
                'id' => '4',
                'name' => 'Qualified',
            ],
        ]);
    }
}
