finalProject - Backend Timedoor 2025
Tentang Proyek

finalProject adalah backend Laravel 10 untuk manajemen toko buku.
Fitur utama:
1. Daftar buku dengan filter, pencarian, dan sorting
2. Ranking 20 penulis teratas
3. Input rating buku

Proyek menggunakan blade view untuk tampilan halaman, dioptimalkan untuk performa tinggi dengan MySQL tanpa caching.
Dataset:
1. 1000 penulis
2. 3000 kategori buku
3. 100.000 buku
4. 500.000 rating

Teknologi: PHP 8.2, Laravel 10, MySQL

Instalasi
1.  Clone repository
    git clone https://github.com/krisna26-droid/finalProject-timedoor.git
    cd finalProject-timedoor

2.  Install dependencies
    composer install

3.  Salin file environment
    cp .env.example .env

4.  Konfigurasi database di file .env
    DB_DATABASE=finalProject
    DB_USERNAME=your_db_user
    DB_PASSWORD=your_db_password

5.  Generate key Laravel
    php artisan key:generate

6.  Jalankan migrasi dan seeder
    php artisan migrate --seed

7.  Jalankan aplikasi
    php artisan serve

Halaman Akses
1.  Daftar buku: http://localhost:8000/books
2.  Daftar penulis: http://localhost:8000/authors
3.  Input rating: http://localhost:8000/ratings/create

Seeder & Faker
Untuk generate data fake:
1.  php artisan db:seed --class=AuthorSeeder
2.  php artisan db:seed --class=CategorySeeder
3.  php artisan db:seed --class=BookSeeder
4.  php artisan db:seed --class=RatingSeeder

Catatan

Tidak menggunakan caching

Query dioptimalkan untuk dataset besar

Gunakan eager loading dan index untuk kolom penting

Pagination efisien untuk daftar buku