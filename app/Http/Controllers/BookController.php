<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index( Request $request){
        $books = Book::with('author', 'category')->paginate(100);

        foreach ($books as $book) {
            $book->average_rating = $book->ratings->avg('rating') ?? 0;
            $book->voters_count = $book->ratings->count();
        }
        return view('books.index', compact('books'));
    }
}
