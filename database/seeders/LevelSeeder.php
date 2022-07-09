<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('levels')->insert([
            [
                'id' => '1',
                'name' => 'A1',
            ],
            [
                'id' => '2',
                'name' => 'A2',
            ],
            [
                'id' => '3',
                'name' => 'A3',
            ],
            [
                'id' => '4',
                'name' => 'B1',
            ],
            [
                'id' => '5',
                'name' => 'B2',
            ],
            [
                'id' => '6',
                'name' => 'B3',
            ],
            [
                'id' => '7',
                'name' => 'C1',
            ], 
            [
                'id' => '8',
                'name' => 'C2',
            ], 
            [
                'id' => '9',
                'name' => 'C3',
            ], 
            [
                'id' => '10',
                'name' => 'All',
            ], 
            [
                'id' => '11',
                'name' => 'Other',
            ],    
        ]);
    }
}
