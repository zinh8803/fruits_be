<?php

namespace Database\Factories;

use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardFactory extends Factory
{
    protected $model = Card::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word . ' Fruit',
            'stars' => $this->faker->numberBetween(1, 5),
            'description' => $this->faker->sentence,
            'rarity' => $this->faker->randomElement(['common', 'rare', 'epic', 'legendary']),
            'image_url' => null,
        ];
    }
}
