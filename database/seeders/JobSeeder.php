<?php

namespace Database\Seeders;

use App\Models\Jobs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jobs')->insert([
            [
                // 'id' => '1',
                'job_status_id' => 1,
                'key' => '00-00',
                'request_date' => Carbon::now()->format('Y-m-d'),
                'branch_id' => 10,
                'description' => 'Job is unknown. No deleted!',
            ],
        ]);

        // Jobs::factory(20)->create();
    }
}
