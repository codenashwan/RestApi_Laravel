<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{User,categories,cities};
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class propertiesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id,
            'category_id' => categories::all()->random()->id,
            'city_id' => cities::all()->random()->id,
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(2),
            'price' => fake()->numberBetween(100000, 1000000),
            'area' => fake()->numberBetween(100, 1000)." sqft",
            'bedrooms' => fake()->numberBetween(1, 5),
            'bathrooms' => fake()->numberBetween(1, 5),
            'garages' => fake()->numberBetween(1, 5),
            'kitchens' => fake()->numberBetween(1, 5),
            'address' => [
                'lat' => fake()->latitude,
                'lng' => fake()->longitude,
            ],
            'images' => ['1.jpg', '2.jpg', '3.jpg']
        ];
    }
}
