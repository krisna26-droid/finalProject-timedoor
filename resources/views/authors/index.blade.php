@extends('layouts.app')

@section('content')
    <h2 class="mb-4">Top Authors</h2>

    <!-- Filter -->
    <form method="GET" action="{{ route('authors.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Cari penulis..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="sort" class="form-select">
                <option value="">Urutkan Berdasarkan</option>
                <option value="popularity">Popularitas (voters)</option>
                <option value="rating">Rata-rata Rating</option>
                <option value="trending">Trending</option>
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
                <th>Buku Terbaik</th>
                <th>Buku Terburuk</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($authors as $author)
                <tr>
                    <td>{{ $author->name }}</td>
                    <td>{{ $author->books_count }}</td>
                    <td>{{ number_format($author->average_rating, 1) }}</td>
                    <td>{{ $author->total_voters }}</td>
                    <td>{{ $author->best_book }}</td>
                    <td>{{ $author->worst_book }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada data penulis</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $authors->links() }}
    </div>
@endsection
