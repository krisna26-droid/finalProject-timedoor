@extends('layouts.app')

@section('content')
    <h2 class="mb-4">List of Books</h2>

    <!-- Filter -->
    <form method="GET" action="{{ route('books.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Cari judul, penulis, atau ISBN" value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="sort" class="form-select">
                <option value="">Urutkan</option>
                <option value="rating">Rating Tertinggi</option>
                <option value="voters">Jumlah Voter</option>
                <option value="recent">Terpopuler (30 Hari Terakhir)</option>
                <option value="alphabet">A-Z</option>
            </select>
        </div>
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
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($books as $book)
                <tr>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author->name }}</td>
                    <td>
                        @foreach ($book->categories as $category)
                            <span class="badge bg-secondary">{{ $category->name }}</span>
                        @endforeach
                    </td>
                    <td>{{ $book->isbn }}</td>
                    <td>{{ number_format($book->average_rating, 1) }}</td>
                    <td>{{ $book->voters_count }}</td>
                    <td>
                        @if ($book->status === 'available')
                            <span class="badge bg-success">Available</span>
                        @elseif ($book->status === 'rented')
                            <span class="badge bg-danger">Rented</span>
                        @else
                            <span class="badge bg-warning">Reserved</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Belum ada data buku</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $books->links() }}
    </div>
@endsection
