<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->index('name');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->index('name');
        });
        Schema::table('books', function (Blueprint $table) {
            $table->index('title');
            $table->index('author_id');
            $table->index('category_id');
            $table->index('isbn');
            $table->index('publisher');
            $table->index('publication_year');
            $table->index('status');
            $table->index('store_location');
        });
        Schema::table('ratings', function (Blueprint $table) {
            $table->index('book_id');
            $table->index('rating');
            $table->index('voter_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex(['title']);
            $table->dropIndex(['author_id']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['isbn']);
            $table->dropIndex(['publisher']);
            $table->dropIndex(['publication_year']);
            $table->dropIndex(['status']);
            $table->dropIndex(['store_location']);
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->dropIndex(['book_id']);
            $table->dropIndex(['rating']);
            $table->dropIndex(['voter_name']);
        });
    }
};
