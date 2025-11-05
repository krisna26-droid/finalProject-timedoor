<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Author;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'author_id' => Author::factory(),
            'category_id' => Category::factory(),
            'isbn' => $this->faker->unique()->isbn13(),
            'publisher' => $this->faker->company(),
            'publication_year' => $this->faker->numberBetween(1950, 2025),
            'status' => $this->faker->randomElement(['available', 'rented', 'reserved']),
            'store_location' => $this->faker->randomElement(['Singaraja', 'Denpasar', 'Banyuwangi', 'Situbondo']),
            'average_rating' => $this->faker->randomFloat(1, 1, 10),
            'voters_count' => 0,
        ];
    }
}
