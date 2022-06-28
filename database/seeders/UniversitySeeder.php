<?php

namespace Database\Seeders;

use App\Models\Universities;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::unprepared('ALTER TABLE cv DROP CONSTRAINT cv_university_id_foreign');
        // DB::table('universities')->truncate();
        DB::table('universities')->insert([
            [
                'id' => '1',
                'name' => 'Others',
            ],
            [
                'id' => '2',
                'name' => 'Danang University of Technology (DUT)',
            ],
            [
                'id' => '3',
                'name' => 'Duy Tan University (DTU)',
            ],
            [
                'id' => '4',
                'name' => 'FPT University (FPT)',
            ],
            [
                'id' => '5',
                'name' => 'Dong A University (DAU)',
            ],
            [
                'id' => '6',
                'name' => 'Danang University of economics (DUE)',
            ],
            [
                'id' => '7',
                'name' => 'Danang University of foreign languages (UFL)',
            ],
            [
                'id' => '8',
                'name' => 'Danang University of education (UED)',
            ],
            [
                'id' => '9',
                'name' => 'Vietnam Korea University (VKU)',
            ],
            [
                'id' => '10',
                'name' => 'College of Information Technology (CIT)',
            ],
            [
                'id' => '11',
                'name' => 'Danang University of Technology and Education (UTE)',
            ],
        ]);
        // DB::unprepared('ALTER TABLE cv ADD CONSTRAINT cv_university_id_foreign FOREIGN KEY (university_id) REFERENCES universities (id)');
    }
}
