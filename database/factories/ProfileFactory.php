<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'job_id' => $this->faker->numberBetween($min = 1, $max = 20),
            // 'submit_date' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'submit_date' => ($this->faker->dateTimeBetween('-3 years'))->format('Y-m-d'),
            'name' => $this->faker->name(),
            'profile_status_id' => $this->faker->numberBetween($min = 1, $max = 8),
            'phone_number' => $this->faker->phoneNumber(),
            'mail' => $this->faker->unique()->safeEmail(),
            // 'birthday' => ($this->faker->dateTimeBetween('-20 years'))->format('Y-m-d'),
            'birthday' => $this->faker->dateTimeBetween('1990-01-01', '2003-12-31')->format('Y-m-d'),
            'address' => $this->faker->address(),
            'year_of_experience' => $this->faker->numberBetween($min = 1, $max = 10),
            'note' => $this->faker->text($maxNbChars = 255),   
        ];
    }
}
