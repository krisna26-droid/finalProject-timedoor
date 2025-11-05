@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Input Rating Buku</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form GET untuk pilih penulis agar bisa filter buku --}}
    <form method="GET" action="{{ route('ratings.create') }}">
        <div class="form-group mb-3">
            <label for="author">Pilih Penulis:</label>
            <select name="author_id" id="author" class="form-control" onchange="this.form.submit()">
                <option value="">-- Pilih Penulis --</option>
                @foreach($authors as $author)
                    <option value="{{ $author->id }}" {{ (isset($author_id) && $author_id == $author->id) ? 'selected' : '' }}>
                        {{ $author->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    {{-- Form POST untuk submit rating --}}
    <form method="POST" action="{{ route('ratings.store') }}">
        @csrf
        <input type="hidden" name="author_id" value="{{ $author_id }}">

        <div class="form-group mb-3">
            <label for="book">Pilih Buku:</label>
            <select name="book_id" id="book" class="form-control" required>
                <option value="">-- Pilih Buku --</option>
                @foreach($books as $book)
                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="rating">Rating (1–10):</label>
            <select name="rating" id="rating" class="form-control" required>
                @for($i=1; $i<=10; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="voter_name">Nama Voter:</label>
            <input type="text" name="voter_name" id="voter_name" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Submit Rating</button>
    </form>
</div>
@endsection
