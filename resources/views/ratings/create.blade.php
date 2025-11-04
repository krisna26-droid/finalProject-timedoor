@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Input Rating Buku</h2>

    <!-- Pesan sukses -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Form Input Rating -->
    <form action="{{ route('ratings.store') }}" method="POST" class="card p-4 shadow-sm">
        @csrf

        <!-- Pilih Penulis -->
        <div class="mb-3">
            <label for="author" class="form-label">Pilih Penulis</label>
            <select id="author" name="author_id" class="form-select" required>
                <option value="">-- Pilih Penulis --</option>
                @foreach ($authors as $author)
                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Pilih Buku -->
        <div class="mb-3">
            <label for="book" class="form-label">Pilih Buku</label>
            <select id="book" name="book_id" class="form-select" required>
                <option value="">-- Pilih Buku --</option>
                @foreach ($books as $book)
                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                @endforeach
            </select>
        </div>

        <!-- Input Rating -->
        <div class="mb-3">
            <label for="rating" class="form-label">Rating (1–10)</label>
            <select id="rating" name="rating" class="form-select" required>
                <option value="">-- Pilih Nilai --</option>
                @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>

        <!-- Input Nama Voter -->
        <div class="mb-3">
            <label for="voter_name" class="form-label">Nama Pemberi Rating</label>
            <input type="text" id="voter_name" name="voter_name" class="form-control" placeholder="Masukkan nama Anda" required>
        </div>

        <!-- Tombol -->
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Simpan Rating</button>
        </div>
    </form>
</div>
@endsection
