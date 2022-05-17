<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FilesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'profile_id' => $this->faker->numberBetween($min = 1, $max = 1000),
        'name' => $this->faker->name(),
        'file' => $this->faker->fileExtension(),
        ];
    }
}