<?php

namespace Database\Factories;

use Faker\Provider\Fakecar;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarcaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //$this->faker->addProvider(new Fakecar($this->faker));
        //$v = $this->faker->vehicleArray();

        return [
            'marca' => $this->faker->title(7), // $v['brand']
        ];
    }
}
