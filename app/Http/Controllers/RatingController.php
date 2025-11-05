<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\Book;
use App\Models\Rating;

class RatingController extends Controller
{
    public function create(Request $request)
    {
        $authors = Author::all();
        
        // Ambil penulis yang dipilih (dari GET)
        $author_id = $request->get('author_id');

        // Ambil buku milik penulis yang dipilih, kalau tidak ada penulis pilih, kosong
        $books = $author_id ? Book::where('author_id', $author_id)->get() : collect();

        return view('ratings.create', compact('authors', 'books', 'author_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'author_id' => 'required|exists:authors,id',
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:10',
            'voter_name' => 'required|string|max:255',
        ]);

        Rating::create([
            'book_id' => $request->book_id,
            'rating' => $request->rating,
            'voter_name' => $request->voter_name,
        ]);

        return redirect()->route('ratings.create')->with('success', 'Rating berhasil disimpan!');
    }
}
