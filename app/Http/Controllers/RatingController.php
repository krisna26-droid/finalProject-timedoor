<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\Book;
use App\Models\Rating;

class RatingController extends Controller
{
    public function create()
    {
        $authors = Author::all();
        $books = Book::all();

        return view('ratings.create', compact('authors', 'books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'author_id' => 'required|exists:authors,id',
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:10',
            'voter_name' => 'required|string|max:100',
        ]);

        Rating::create([
            'book_id' => $request->book_id,
            'rating' => $request->rating,
            'voter_name' => $request->voter_name,
        ]);

        return redirect('/')
            ->with('success', 'Rating berhasil ditambahkan!');
    }
}
