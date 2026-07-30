<?php

namespace Database\Factories;

use App\Models\Kost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kost>
 */
class KostFactory extends Factory
{
    protected $model = Kost::class;

    public function definition(): array
    {
        return [
            'owner_id' => User::factory()->owner(),

            'name' => fake()->company() . ' Kost',

            'description' => fake()->paragraph(),

            'location' => fake()->city(),

            'price' => fake()->numberBetween(
                500000,
                3000000
            ),
        ];
    }
}