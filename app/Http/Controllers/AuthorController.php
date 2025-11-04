<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data penulis dengan jumlah buku dan rating rata-rata
        $authors = Author::withCount('books')
            ->with(['books.ratings'])
            ->paginate(10);

        // Tambahkan kolom tambahan agar view bisa pakai
        $authors->transform(function ($author) {
            // Hitung total voters dan rata-rata rating
            $ratings = $author->books->flatMap->ratings;
            $totalVoters = $ratings->count();
            $averageRating = $ratings->avg('rating') ?? 0;

            // Ambil buku terbaik dan terburuk
            $bestBook = $author->books->sortByDesc(function ($book) {
                return $book->ratings->avg('rating');
            })->first();

            $worstBook = $author->books->sortBy(function ($book) {
                return $book->ratings->avg('rating');
            })->first();

            // Tambahkan properti ke author
            $author->total_voters = $totalVoters;
            $author->average_rating = $averageRating;
            $author->best_book = $bestBook->title ?? '-';
            $author->worst_book = $worstBook->title ?? '-';

            return $author;
        });

        return view('authors.index', compact('authors'));
    }
}
