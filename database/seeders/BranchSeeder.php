<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('branches')->insert([
            [
                'id' => '1',
                'name' => 'C#, .NET',
            ],
            [
                'id' => '2',
                'name' => 'Java',
            ],
            [
                'id' => '3',
                'name' => 'Front end',
            ],
            [
                'id' => '4',
                'name' => 'PHP',
            ],
            [
                'id' => '5',
                'name' => 'Python',
            ],
            [
                'id' => '6',
                'name' => 'Ruby On Rails',
            ],
            [
                'id' => '7',
                'name' => 'Android',
            ],
            [
                'id' => '8',
                'name' => 'React Native',
            ],
            [
                'id' => '9',
                'name' => 'Test',
            ], 
            [
                'id' => '10',
                'name' => 'Unknown',
            ],
        ]);
    }
}
