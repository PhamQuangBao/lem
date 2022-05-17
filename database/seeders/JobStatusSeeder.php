<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('job_statuses')->insert([
            [
                'id' => '1',
                'name' => 'Open',
            ],
            [
                'id' => '2',
                'name' => 'Closed',
            ],
        ]);
    }
}
