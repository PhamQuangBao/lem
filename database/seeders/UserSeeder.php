<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Super Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('123456'),
            ],
            [
                'name' => 'Bao Pham',
                'email' => 'bao.pham@gmail.com',
                'password' => bcrypt('123456'),
            ],
        ]);

        DB::table('user_role_users')->insert([
            [
                // 'id' => '1',
                'user_id' => '1',
                'role_id' => '1',
            ],
            [
                // 'id' => '2',
                'user_id' => '2',
                'role_id' => '1',
            ],
        ]);
    }
}
