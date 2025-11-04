<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create([
            'title' => 'Harry Potter and the Sorcerer\'s Stone',
            'author_id' => 1,
            'category' => 'Fantasy',
            'isbn' => '9780747532699',
            'publisher' => 'Bloomsbury',
            'publication_year' => 1997,
            'status' => 'available',
            'store_location' => 'A1'
        ]);

        Book::create([
            'title' => 'A Game of Thrones',
            'author_id' => 2,
            'category' => 'Fantasy',
            'isbn' => '9780553103540',
            'publisher' => 'Bantam Books',
            'publication_year' => 1996,
            'status' => 'available',
            'store_location' => 'A2'
        ]);

        Book::create([
            'title' => 'Norwegian Wood',
            'author_id' => 3,
            'category' => 'Romance',
            'isbn' => '9780375704024',
            'publisher' => 'Vintage',
            'publication_year' => 1987,
            'status' => 'rented',
            'store_location' => 'B1'
        ]);
    }
}
