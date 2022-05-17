<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'job_status_id' => $this->faker->numberBetween($min = 1, $max = 2),
        'key' => $this->faker->unique()->bothify('##-##-??????????'),
        // 'request_date' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
        'request_date' => ($this->faker->dateTimeBetween('-3 years'))->format('Y-m-d'),
        'branch_id' => $this->faker->numberBetween($min = 1, $max = 10),
        'description' => $this->faker->text($maxNbChars = 255),
        ];
    }
}
