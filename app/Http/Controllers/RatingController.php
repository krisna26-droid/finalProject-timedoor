<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function create()
    {
        // nanti diisi dropdown penulis & buku
        return view('ratings.create');
    }

    public function store(Request $request)
    {
        // nanti diisi logika simpan rating
        return redirect()->route('books.index')->with('success', 'Rating berhasil ditambahkan!');
    }
}
