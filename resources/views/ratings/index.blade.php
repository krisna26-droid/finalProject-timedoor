@extends('layouts.app')

@section('content')
    <h2 class="mb-4">Input Rating Buku</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('ratings.store') }}">
                @csrf

                <!-- Pilih Penulis -->
                <div class="mb-3">
                    <label for="author_id" class="form-label">Pilih Penulis</label>
                    <select name="author_id" id="author_id" class="form-select">
                        <option value="">-- Pilih Penulis --</option>
                        @foreach ($authors as $author)
                            <option value="{{ $author->id }}">{{ $author->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pilih Buku -->
                <div class="mb-3">
                    <label for="book_id" class="form-label">Pilih Buku</label>
                    <select name="book_id" id="book_id" class="form-select">
                        <option value="">-- Pilih Buku --</option>
                        @foreach ($books as $book)
                            <option value="{{ $book->id }}">{{ $book->title }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Daftar buku akan disesuaikan dengan penulis yang dipilih.</small>
                </div>

                <!-- Nilai Rating -->
                <div class="mb-3">
                    <label for="rating" class="form-label">Nilai Rating</label>
                    <select name="rating" id="rating" class="form-select">
                        <option value="">-- Pilih Nilai --</option>
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Tombol Submit -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Kirim Rating</button>
                </div>
            </form>
        </div>
    </div>
@endsection
