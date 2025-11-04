<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index( Request $request){
        $books = Book::with('author', 'categories')->paginate(10);
        return view('books.index', compact('books'));
    }
}
