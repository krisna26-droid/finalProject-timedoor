<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Rating;
use Carbon\Carbon;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $category = $request->input('category');

        $authorName = $request->input('author_id');

        $yearFrom = $request->input('year_from');
        $yearTo = $request->input('year_to');

        $status = $request->input('status');

        $store_location = $request->input('store_location');

        $minRating = $request->input('min_rating');
        $maxRating = $request->input('max_rating');

        $sort = $request->input('sort','weighted');

        // Base query with eager loading & aggregate
        $books = Book::with(['author', 'category'])
            ->withCount('ratings')
            ->withAvg('ratings', 'rating');

        // Filter: search
        if ($search) {
            $books->where(function($q) use ($search) {
                $q->where('title','like',"%{$search}%")
                  ->orWhere('isbn','like',"%{$search}%")
                  ->orWhere('publisher','like',"%{$search}%")
                  ->orWhereHas('author', function($q) use ($search){
                      $q->where('name','like',"%{$search}%");
                  });
            });
        }

        // Filter kategori
        if ($category) {
            $categories = array_map('trim', explode(',', $category));
            $books->whereHas('category', function($q) use ($categories){
                $q->whereIn('name', $categories);
            });
        }

        // Filter author
        if ($authorName) {
            $books->whereHas('author', function($q) use ($authorName) {
                $q->where('name', 'like', "%{$authorName}%");
            });
        }

        // Filter tahun
        if ($yearFrom && $yearTo) {
            $books->whereBetween('publication_year', [$yearFrom, $yearTo]);
        } elseif ($yearFrom) {
            $books->where('publication_year','>=',$yearFrom);
        } elseif ($yearTo) {
            $books->where('publication_year','<=',$yearTo);
        }

        // Filter status
        if ($status) $books->where('status', $status);

        // Filter lokasi toko
        if ($store_location) $books->where('store_location', $store_location);

        // Filter rating 
        if ($minRating || $maxRating) {
            if ($minRating) $books->having('ratings_avg_rating', '>=', $minRating);
            if ($maxRating) $books->having('ratings_avg_rating', '<=', $maxRating);
        }

        // Sorting
        if ($sort) {
            switch($sort){
                case 'votes':
                    $books->orderBy('ratings_count','desc');
                    break;
                case 'recent':
                    $books->withCount(['ratings as recent_ratings_count' => function($q){
                        $q->where('created_at','>=', now()->subDays(30));
                    }])->orderBy('recent_ratings_count','desc');
                    break;
                case 'alphabet':
                    $books->orderBy('title','asc');
                    break;
                case 'weighted':
                    $books->orderBy('ratings_avg_rating','desc');
                    break;
            }
        }


        // Paginate
        $books = $books->paginate(100)->appends(request()->query());


        // Hitung tren naik/turun (30 hari vs 30 hari sebelumnya)
        $now = Carbon::now();
        foreach($books as $book){
            $avgRecent = Rating::where('book_id', $book->id)
                        ->where('created_at','>=', $now->copy()->subDays(30))
                        ->avg('rating');

            $avgLast = Rating::where('book_id', $book->id)
                        ->where('created_at','<', $now->copy()->subDays(30))
                        ->where('created_at','>=', $now->copy()->subDays(60))
                        ->avg('rating');

            $book->trend = $avgRecent > $avgLast ? 'up' : ($avgRecent < $avgLast ? 'down' : null);
        }

        return view('books.index', compact('books'));
    }
}
