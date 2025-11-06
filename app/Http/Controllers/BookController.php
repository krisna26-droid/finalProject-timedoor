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
        $authorId = $request->input('author_id');

        $yearFrom = $request->input('year_from');
        $yearTo = $request->input('year_to');

        $status = $request->input('status');

        $store_location =  $request->input('store_location');

        // $minRating = $request->input('min_rating');
        // $maxRating = $request->input('max_rating');

        
        // Mulai query dengan relasi
        $books = Book::with(['author', 'category', 'ratings']);

        // Filter search
        if ($search) {
            $books->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('isbn', 'like', "%{$search}%")
                      ->orWhere('publisher', 'like', "%{$search}%")
                      ->orWhereHas('author', function ($query) use ($search) {
                          $query->where('name', 'like', "%{$search}%");
                      });
            });
        }

        // Filter kategori
        if ($category) {
            $categories = array_map('trim', explode(',', $category));
            $books->whereHas('category', function ($query) use ($categories) {
                $query->whereIn('name', $categories);
            });
        }

        // Filter Id Penulis
        if ($authorId) {
            $books->where('author_id', $authorId);
        }

        // Filter berdasarkan rentang tahun
        if ($yearFrom && $yearTo) {
            $books->whereBetween('publication_year', [$yearFrom, $yearTo]);
        } elseif ($yearFrom) {
            $books->where('publication_year', '>=', $yearFrom);
        } elseif ($yearTo) {
            $books->where('publication_year', '<=', $yearTo);
        }

        // Filter by statusnya
        if ($status) {
            $books->where('status', $status);
        }

        // Filter lokasi toko
        if ($store_location) {
            $books->where('store_location', $store_location);
        }

        // Filter berdasarkan rentang rating
        

        // Pagination 100 
        $books = $books->paginate(100);
        if ($books->isEmpty()) {
            return view('books.index', compact('books'));
        } 

        // Hitung average rating dan voters count
        foreach ($books as $book) {
            $book->average_rating = $book->ratings->avg('rating') ?? 0;
            $book->voters_count = $book->ratings->count();
        }

        //tren Naik turun
        $now = Carbon::now();
        $currentMonth = $now->month;
        $lastMonth = $now->copy()->subMonth()->month;

        foreach ($books as $book) {
            $avgRecent = Rating::where('book_id', $book->id)
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $now->year)
                ->avg('rating');

            $avgLast = Rating::where('book_id', $book->id)
                ->whereMonth('created_at', $lastMonth)
                ->whereYear('created_at', $now->copy()->subMonth()->year)
                ->avg('rating');

            if ($avgRecent > $avgLast) {
                $book->trend = 'up';
            } elseif ($avgRecent < $avgLast) {
                $book->trend = 'down';
            } else {
                $book->trend = null;
            }
        }
        return view('books.index', compact('books'));
    }
}
