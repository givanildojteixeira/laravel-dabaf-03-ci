<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Tem a função de criar dados fake para auxiliar nos testes 
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //nesse caso vamos criar registros com nros aleatorios entre 1 e 100
            //e com reserva aleatoria entre true e false
            "number" => fake()->numberBetween(1,100),
            "isReserved" => fake()->boolean()
        ];
    }
}
