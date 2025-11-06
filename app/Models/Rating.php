<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Book;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['book_id', 'rating', 'voter_name'];

    // Relasi ke buku (setiap rating dimiliki oleh 1 buku)
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
