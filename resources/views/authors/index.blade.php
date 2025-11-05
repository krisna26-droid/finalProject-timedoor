@extends('layouts.app')

@section('content')
    <h2 class="mb-4">Top Authors</h2>

    <!-- Filter / Sorting -->
    <form method="GET" action="{{ route('authors.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Cari penulis..."
                value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <select name="sort" class="form-select">
                <option value="">Urutkan Berdasarkan</option>
                <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Popularitas (voters > 5)</option>
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rata-rata Rating</option>
                <option value="trending" {{ request('sort') == 'trending' ? 'selected' : '' }}>Trending</option>
            </select>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
        </div>
    </form>

    <!-- Tabel daftar penulis -->
    <table class="table table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>Nama Penulis</th>
                <th>Jumlah Buku</th>
                <th>Rata-rata Rating</th>
                <th>Total Voters</th>
                <th>Trending Score</th>
                <th>Buku Terbaik</th>
                <th>Buku Terburuk</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($authors as $author)
                <tr>
                    <td>{{ $author->name }}</td>
                    <td>{{ $author->books_count }}</td>
                    <td>{{ number_format($author->average_rating ?? 0, 1) }}</td>
                    <td>{{ $author->total_voters ?? 0 }}</td>
                    <td>{{ number_format($author->trending_score ?? 0, 2) }}</td>
                    <td>{{ $author->best_book ?? '-' }}</td>
                    <td>{{ $author->worst_book ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Belum ada data penulis</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <!-- Pagination -->
    {{ $authors->links('pagination::bootstrap-5') }}
@endsection
