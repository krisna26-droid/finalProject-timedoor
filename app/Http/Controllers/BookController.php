<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $books = Book::with('author', 'category')
                ->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%")
                        ->orWhere('publisher', 'like', "%{$search}%")
                        ->orWhereHas('author', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                })
                ->paginate(100);
        } else {
            $books = Book::with('author', 'category')->paginate(100);
        }

        foreach ($books as $book) {
            $book->average_rating = $book->ratings->avg('rating') ?? 0;
            $book->voters_count = $book->ratings->count();
        }

        return view('books.index', compact('books'));
    }
}
