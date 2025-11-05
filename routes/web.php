<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\RatingController;

Route::get('/', [BookController::class, 'index'])->name('books.index');

Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');

Route::get('/ratings', [RatingController::class, 'create'])->name('ratings.create');
Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');




