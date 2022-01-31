<?php

namespace Database\Factories;

use App\Models\Marca;
use Faker\Provider\Fakecar;
use Illuminate\Database\Eloquent\Factories\Factory;

class VeiculoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->faker->addProvider(new Fakecar($this->faker));
        $v = $this->faker->vehicleArray();

        return [
            'marca_id' => Marca::factory()->state(['marca' => $v['brand']]),
            'modelo' => $v['model'],
            'placa' => $this->faker->vehicleRegistration('[A-Z]{3}[0-9][0-9A-Z][0-9]{2}'),
            'ano' => $this->faker->biasedNumberBetween(1990, date('Y')),
            'ownerid' => 'chiquitto',
        ];
    }
}
