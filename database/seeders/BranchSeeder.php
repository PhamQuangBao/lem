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
                'name' => 'Marketing',
            ], 
            [
                'id' => '11',
                'name' => 'Automation Test',
            ],
            [
                'id' => '12',
                'name' => 'Translator',
            ],
            [
                'id' => '13',
                'name' => 'Security',
            ], 
            [
                'id' => '14',
                'name' => 'Designer',
            ],
            [
                'id' => '15',
                'name' => 'HR recruiter',
            ], 
            [
                'id' => '16',
                'name' => 'Finance Manager',
            ],
            [
                'id' => '17',
                'name' => 'Unknown',
            ],
        ]);
    }
}
