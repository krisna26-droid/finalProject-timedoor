@extends('layouts.app')

@section('content')
    <h2 class="mb-4">Top Authors</h2>

    <!-- Filter / Sorting -->
    <form method="GET" action="{{ route('authors.index') }}" class="row g-2 align-items-center mb-4">
        <div class="col-md-4 col-lg-3">
            <input type="text" 
                name="search" 
                class="form-control" 
                placeholder="Cari penulis..." 
                value="{{ request('search') }}">
        </div>

        <div class="col-md-4 col-lg-3">
            <select name="sort" class="form-select">
                <option value="" disabled {{ request('sort') ? '' : 'selected' }}>Urutkan berdasarkan</option>
                <option value="trending" {{ request('sort') == 'trending' ? 'selected' : '' }}>Trending</option>
                <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Popularitas (Rating > 5)</option>
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rata-rata Rating</option>
            </select>
        </div>

        <div class="col-md-2 col-lg-2 d-grid">
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </div>

        <div class="col-md-2 col-lg-2 d-grid">
            <a href="{{ route('authors.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    <!-- Tabel daftar penulis -->
    <table class="table table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Penulis</th>
                <th>Jumlah Buku</th>
                <th>Rata-rata Rating</th>
                <th>Total Voters</th>
                <th>Popularity (Rating > 5)</th>
                <th>Trending Score</th>
                <th>Buku Terbaik</th>
                <th>Buku Terburuk</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($authors as $index => $author)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $author->name }}</td>
                    <td>{{ $author->books_count }}</td>
                    <td>{{ number_format($author->average_rating, 1) }}</td>
                    <td>{{ $author->total_voters }}</td>
                    <td>{{ $author->popularity }}</td>
                    <td>{{ number_format($author->trending_score, 2) }}</td>
                    <td>{{ $author->best_book }}</td>
                    <td>{{ $author->worst_book }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">Belum ada data penulis</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection