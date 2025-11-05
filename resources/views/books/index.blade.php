@extends('layouts.app')

@section('content')
<h2 class="mb-4">List of Books</h2>

<!-- Filter -->
<form method="GET" action="{{ route('books.index') }}" class="row g-3 mb-4">

    <!-- Pencarian -->
    <div class="col-md-3">
        <input type="text" name="search" class="form-control"
               placeholder="Cari judul, penulis, ISBN, atau penerbit"
               value="{{ request('search') }}">
    </div>

    <!-- Kategori -->
    <div class="col-md-2">
        <input type="text" name="category" class="form-control"
               placeholder="Kategori" value="{{ request('category') }}">
    </div>

    <!-- Penulis -->
    <div class="col-md-2">
        <input type="text" name="author_id" class="form-control"
               placeholder="ID Penulis" value="{{ request('author_id') }}">
    </div>

    <!-- Tahun terbit -->
    <div class="col-md-2 d-flex gap-2">
        <input type="number" name="year_from" class="form-control" placeholder="Dari" value="{{ request('year_from') }}">
        <input type="number" name="year_to" class="form-control" placeholder="Sampai" value="{{ request('year_to') }}">
    </div>

    <!-- Status -->
    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">Semua Status</option>
            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
            <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Rented</option>
            <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
        </select>
    </div>

    <!-- Lokasi Toko -->
    <div class="col-md-2">
        <input type="text" name="store_location" class="form-control"
               placeholder="Lokasi Toko" value="{{ request('store_location') }}">
    </div>

    <!-- Rentang Rating -->
    <div class="col-md-2 d-flex gap-2">
        <input type="number" name="rating_min" class="form-control" placeholder="Min" min="1" max="10"
               value="{{ request('rating_min') }}">
        <input type="number" name="rating_max" class="form-control" placeholder="Max" min="1" max="10"
               value="{{ request('rating_max') }}">
    </div>

    <!-- Sorting -->
    <div class="col-md-2">
        <select name="sort" class="form-select">
            <option value="">Urutkan</option>
            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
            <option value="voters" {{ request('sort') == 'voters' ? 'selected' : '' }}>Jumlah Voter</option>
            <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Terpopuler (30 Hari)</option>
            <option value="alphabet" {{ request('sort') == 'alphabet' ? 'selected' : '' }}>A-Z</option>
        </select>
    </div>

    <!-- Tombol Terapkan -->
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Terapkan</button>
    </div>
</form>

<!-- Tabel Buku -->
<table class="table table-striped table-bordered align-middle">
    <thead class="table-dark">
        <tr>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Kategori</th>
            <th>ISBN</th>
            <th>Rata-rata Rating</th>
            <th>Total Voter</th>
            <th>Tren</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($books as $book)
        <tr>
            <td>{{ $book->title }}</td>
            <td>{{ $book->author->name ?? '-' }}</td>
            <td>{{ $book->category->name ?? '-' }}</td>
            <td>{{ $book->isbn }}</td>
            <td>{{ number_format($book->average_rating ?? 0, 1) }}</td>
            <td>{{ $book->voters_count ?? 0 }}</td>
            <td>
                @if (!empty($book->trend) && $book->trend === 'up')
                    <span class="text-success">↑</span>
                @elseif (!empty($book->trend) && $book->trend === 'down')
                    <span class="text-danger">↓</span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                @switch($book->status)
                    @case('available') <span class="badge bg-success">Available</span> @break
                    @case('rented') <span class="badge bg-danger">Rented</span> @break
                    @case('reserved') <span class="badge bg-warning text-dark">Reserved</span> @break
                    @default <span class="badge bg-secondary">Unknown</span>
                @endswitch
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center text-muted">Belum ada data buku</td>
        </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination -->
{{ $books->links('pagination::bootstrap-5') }}
@endsection
