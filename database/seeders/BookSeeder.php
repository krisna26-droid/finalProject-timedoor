<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use Faker\Factory as Faker;
use App\Models\Category;
use App\Models\Author;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Book::create([
        //     'title' => 'Harry Potter and the Sorcerer\'s Stone',
        //     'author_id' => 1,
        //     'category' => 'Fantasy',
        //     'isbn' => '9780747532699',
        //     'publisher' => 'Bloomsbury',
        //     'publication_year' => 1997,
        //     'status' => 'available',
        //     'store_location' => 'A1'
        // ]);

        // Book::create([
        //     'title' => 'A Game of Thrones',
        //     'author_id' => 2,
        //     'category' => 'Fantasy',
        //     'isbn' => '9780553103540',
        //     'publisher' => 'Bantam Books',
        //     'publication_year' => 1996,
        //     'status' => 'available',
        //     'store_location' => 'A2'
        // ]);


        // Book::factory()->count(10)->create();

        $faker =Faker::create();
        $authors = Author::pluck('id')->toArray();
        $categories = Category::pluck('id')->toArray();

        $total = 100000;
        for ($i = 0; $i < $total; $i++) {
            Book::create([
                'title' => $faker->sentence(),
                'author_id' => $faker->randomElement($authors),
                'category_id' => $faker->randomElement($categories),
                'isbn' => $faker->unique()->isbn13(),
                'publisher' => $faker->company(),
                'publication_year' => $faker->numberBetween(1950, 2025),
                'status' => $faker->randomElement(['available', 'rented', 'reserved']),
                'store_location' => $faker->randomElement(['Singaraja', 'Denpasar', 'Banyuwangi', 'Situbondo']),
                'average_rating' => 0,
                'voters_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
