<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rating;

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

        Rating::factory()->count(10)->create();
    }
}
