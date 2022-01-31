<?php

namespace Database\Seeders;

use App\Models\Marca;
use App\Models\Veiculo;
use Faker\Provider\CarData;
use Faker\Provider\Fakecar;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class VeiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 10;
        $brands = array_keys(Fakecar::getRandomElementsFromArray(CarData::getBrandsWithModels(), $count));

        foreach ($brands as $brandName) {
            $marca = Marca::factory()
                ->state([
                    'marca' => $brandName
                ]);

            Veiculo::factory()
                ->for($marca)
                ->count(3)
                ->state(new Sequence(
                    function ($sequence) use ($brandName) {
                        return [
                            'modelo' => Fakecar::vehicleModel($brandName)
                        ];
                    },
                ))
                ->create();
        }
    }
}
