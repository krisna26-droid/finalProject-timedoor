<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthorController extends Controller
{
    public function index()
    {
        $search = request('search');
        $sort = request('sort');
        $now = Carbon::now();

        $recentStart = $now->copy()->subDays(30);
        $previousStart = $now->copy()->subDays(60);
        $previousEnd = $now->copy()->subDays(31);

        // Ambil semua stats
        $query = Author::query()
            ->leftJoin('books', 'books.author_id', '=', 'authors.id')
            ->leftJoin('ratings', 'ratings.book_id', '=', 'books.id')
            ->select('authors.*',
                DB::raw('COUNT(DISTINCT books.id) as books_count'),
                DB::raw('AVG(ratings.rating) as average_rating'),
                DB::raw('COUNT(ratings.id) as total_voters'),
                DB::raw('SUM(CASE WHEN ratings.rating > 5 THEN 1 ELSE 0 END) as popularity')
            )
            ->groupBy('authors.id');

        if ($search) {
            $query->where('authors.name', 'like', "%{$search}%");
        }

        // ambil lebih sedikit dulu (misal top 500) biar cepet sebelum disort nanti
        $authors = $query->take(500)->get();

        // Hitung rata-rata recent & previous rating (untuk trending)
        $recentRatings = Rating::join('books', 'books.id', '=', 'ratings.book_id')
            ->where('ratings.created_at', '>=', $recentStart)
            ->groupBy('books.author_id')
            ->select('books.author_id', DB::raw('AVG(ratings.rating) as avg_recent'))
            ->pluck('avg_recent', 'author_id');

        $previousRatings = Rating::join('books', 'books.id', '=', 'ratings.book_id')
            ->whereBetween('ratings.created_at', [$previousStart, $previousEnd])
            ->groupBy('books.author_id')
            ->select('books.author_id', DB::raw('AVG(ratings.rating) as avg_previous'))
            ->pluck('avg_previous', 'author_id');

        // Ambil rata-rata rating per buku (untuk best & worst)
        $bookAvg = Book::select(
                'books.author_id',
                'books.id',
                'books.title',
                DB::raw('AVG(ratings.rating) as avg_rating')
            )
            ->leftJoin('ratings', 'ratings.book_id', '=', 'books.id')
            ->groupBy('books.id', 'books.author_id', 'books.title')
            ->get()
            ->groupBy('author_id');

        //(trending, best, worst)
        foreach ($authors as $author) {
            $avgRecent = $recentRatings[$author->id] ?? 0;
            $avgPrevious = $previousRatings[$author->id] ?? 0;

            $diff = $avgRecent - $avgPrevious;
            $weight = $author->total_voters > 0 ? log(1 + $author->total_voters) : 0;
            $author->trending_score = $diff * $weight;

            // Best & Worst book
            $booksAuthor = $bookAvg->get($author->id, collect());
            if ($booksAuthor->isEmpty()) {
                $author->best_book = '-';
                $author->worst_book = '-';
            } else {
                $author->best_book = $booksAuthor->sortByDesc('avg_rating')->first()->title;
                $author->worst_book = $booksAuthor->sortBy('avg_rating')->first()->title;
            }
        }

        // Sorting
        if ($sort) {
            $authors = match ($sort) {
                'popularity' => $authors->sortByDesc('popularity'),
                'rating' => $authors->sortByDesc('average_rating'),
                'trending' => $authors->sortByDesc('trending_score'),
                default => $authors,
            };
        }
        //Ambil top 20
        $authors = $authors->values()->take(20);

        return view('authors.index', compact('authors', 'search', 'sort'));
    }
}
