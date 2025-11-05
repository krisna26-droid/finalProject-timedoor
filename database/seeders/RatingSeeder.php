<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rating;
use Faker\Factory as Faker;
use App\Models\Book;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Rating::create(['book_id' => 1, 'rating' => 9, 'voter_name' => 'Krisna']);
        // Rating::create(['book_id' => 1, 'rating' => 8, 'voter_name' => 'Ngurah']);
        // Rating::create(['book_id' => 2, 'rating' => 10, 'voter_name' => 'Jon']);
        // Rating::create(['book_id' => 3, 'rating' => 7, 'voter_name' => 'Yuga']);

        // Rating::factory()->count(10)->create();

        $faker = Faker::create();
        $books = Book::pluck('id')->toArray();

        $total = 500000;
        
        for ($i = 0; $i < $total; $i++) {
            Rating::create([
                'book_id' => $faker->randomElement($books),
                'rating' => $faker->numberBetween(1, 10),
                'voter_name' => $faker->name(),
                'created_at' => $faker->dateTimeBetween('-2 years', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
