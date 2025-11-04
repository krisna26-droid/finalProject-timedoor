<?php

use Illuminate\Support\Facades\Route;
//use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\BookController;

Route::get('/', [BookController::class, 'index'])->name('books.index');






















//INI UNTUK TEST
// Route::get('/', function () {
//     // paginator kosong supaya tidak error
//     $books = new LengthAwarePaginator([], 0, 10, 1, [
//         'path' => request()->url(),
//         'query' => request()->query(),
//     ]);

//     return view('books.index', compact('books'));
// })->name('books.index');

// Route::get('/authors', function () {
//     $authors = new LengthAwarePaginator([], 0, 10, 1, [
//         'path' => request()->url(),
//         'query' => request()->query(),
//     ]);

//     return view('authors.index', compact('authors'));
// })->name('authors.index');


// Route::get('/ratings', function () {
//     $authors = collect([]);
//     $books = collect([]);

//     return view('ratings.index', compact('authors', 'books'));
// })->name('ratings.index');

// Route::post('/ratings', function () {
//     return back()->with('success', 'Rating berhasil disimpan (contoh)');
// })->name('ratings.store');



