<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->insert([
            [
                //'id' => '1',
                'name' => 'Super Admin',
            ],
            [
                //'id' => '2',
                'name' => 'Admin',
            ],
        ]);
    }
}
