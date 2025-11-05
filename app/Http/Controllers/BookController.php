<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

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
            $books->whereHas('category', function ($query) use  ($category) {
                $query->where('name','like', '%' . $category . '%');
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

        // Pagination 100 
        $books = $books->paginate(100);

        // Hitung average rating dan voters count
        foreach ($books as $book) {
            $book->average_rating = $book->ratings->avg('rating') ?? 0;
            $book->voters_count = $book->ratings->count();
        }

        return view('books.index', compact('books'));
    }
}
