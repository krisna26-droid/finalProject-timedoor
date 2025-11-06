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
        $sort = request('sort'); // null jika user belum memilih
        $now = Carbon::now();

        $recentStart = $now->copy()->subDays(30);
        $previousStart = $now->copy()->subDays(60);
        $previousEnd = $now->copy()->subDays(31);

        // Ambil semua statistik dasar via subquery
        $query = Author::query()
            ->select('authors.*')
            ->selectSub(function ($q) {
                $q->from('books')
                  ->whereColumn('books.author_id', 'authors.id')
                  ->selectRaw('COUNT(*)');
            }, 'books_count')
            ->selectSub(function ($q) {
                $q->from('ratings')
                  ->join('books', 'books.id', '=', 'ratings.book_id')
                  ->whereColumn('books.author_id', 'authors.id')
                  ->selectRaw('AVG(ratings.rating)');
            }, 'average_rating')
            ->selectSub(function ($q) {
                $q->from('ratings')
                  ->join('books', 'books.id', '=', 'ratings.book_id')
                  ->whereColumn('books.author_id', 'authors.id')
                  ->selectRaw('COUNT(*)');
            }, 'total_voters')
            ->selectSub(function ($q) {
                $q->from('ratings')
                  ->join('books', 'books.id', '=', 'ratings.book_id')
                  ->whereColumn('books.author_id', 'authors.id')
                  ->where('ratings.rating', '>', 5)
                  ->selectRaw('COUNT(*)');
            }, 'popularity');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $authors = $query->get();

        // Ambil semua ratings 60 hari terakhir sekaligus
        $ratings60 = Rating::select('ratings.id','ratings.rating','books.author_id','ratings.book_id','ratings.created_at')
            ->join('books','books.id','=','ratings.book_id')
            ->where('ratings.created_at', '>=', $previousStart)
            ->get()
            ->groupBy('author_id');

        // Ambil semua avg per book untuk best/worst book sekaligus
        $bookAvg = Book::select('books.author_id','books.id','books.title',DB::raw('AVG(ratings.rating) as avg_rating'))
            ->leftJoin('ratings','ratings.book_id','=','books.id')
            ->groupBy('books.id','books.author_id','books.title')
            ->get()
            ->groupBy('author_id');

        foreach ($authors as $author) {
            $authorRatings = $ratings60->get($author->id, collect());

            $avgRecent = $authorRatings->where('created_at','>=',$recentStart)->avg('rating') ?? 0;
            $avgPrevious = $authorRatings->whereBetween('created_at', [$previousStart, $previousEnd])->avg('rating') ?? 0;

            $diff = $avgRecent - $avgPrevious;
            $weight = $author->total_voters > 0 ? log(1 + $author->total_voters) : 0;
            $author->trending_score = $diff * $weight;

            // Best & Worst book
            $booksAuthor = $bookAvg->get($author->id, collect());
            if($booksAuthor->isEmpty()) {
                $author->best_book = '-';
                $author->worst_book = '-';
            } else {
                $author->best_book = $booksAuthor->sortByDesc('avg_rating')->first()->title;
                $author->worst_book = $booksAuthor->sortBy('avg_rating')->first()->title;
            }
        }

        // Sorting
        if($sort){
            $authors = match($sort) {
                'popularity' => $authors->sortByDesc('popularity'),
                'rating' => $authors->sortByDesc('average_rating'),
                'trending' => $authors->sortByDesc('trending_score'),
                default => $authors
            };
        }

        // Ambil top 20
        $authors = $authors->values()->take(20);

        return view('authors.index', compact('authors','search','sort'));
    }
}
