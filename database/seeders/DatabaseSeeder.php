<?php

namespace Database\Seeders;

use Database\Factories\FilesFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserRoleSeeder::class,
            UserSeeder::class,
            JobStatusSeeder::class,
            BranchSeeder::class,
            ProfileStatusGroupSeeder::class,
            ProfileStatusSeeder::class,
            JobSeeder::class,
            ProfileSeeder::class,
            // FileSeeder::class,
        ]);
    }
}
