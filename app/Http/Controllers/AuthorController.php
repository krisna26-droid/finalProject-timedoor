<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Rating;
use Illuminate\Http\Request;


class AuthorController extends Controller
{
    public function index()
    {
        $search = request('search');
        $query = Author::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $authors = $query->paginate(100);

        // Bikin data tambahan untuk tiap penulis
        foreach ($authors as $author) {
            // Ambil semua buku milik penulis ini
            $books = Book::where('author_id', $author->id)->get();

            // Hitung jumlah buku
            $author->books_count = $books->count();

            // Ambil semua rating dari buku-buku milik penulis ini
            $bookIds = $books->pluck('id');
            $ratings = Rating::whereIn('book_id', $bookIds)->get();

            // Hitung rata-rata rating dan jumlah voters
            $author->average_rating = $ratings->avg('rating') ?? 0;
            $author->total_voters = $ratings->count();

            // Cari buku terbaik & terburuk
            $bestBook = Book::whereIn('id', $bookIds)
                ->withAvg('ratings', 'rating')
                ->orderByDesc('ratings_avg_rating')
                ->first();

            $worstBook = Book::whereIn('id', $bookIds)
                ->withAvg('ratings', 'rating')
                ->orderBy('ratings_avg_rating')
                ->first();

            $author->best_book = $bestBook->title ?? '-';
            $author->worst_book = $worstBook->title ?? '-';
        }

        return view('authors.index', compact('authors', 'search'));
    }
}
