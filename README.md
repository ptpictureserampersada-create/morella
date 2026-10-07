# Negeri Morella — Portal Pariwisata & Program Pengabdian

Portal web resmi pariwisata **Negeri Morella** (Desa Morela, Kecamatan Leihitu, Kabupaten Maluku Tengah), dikelola bersama Program Pengabdian Mahasiswa **Universitas Darussalam Ambon (UNIDAR)**.

Aplikasi semula berupa React SPA, kini dikonversi penuh ke **Laravel 11 + Blade + Alpine.js** dengan penyimpanan data di **MySQL**.

Repositori: https://github.com/ptpictureserampersada-create/morella

## Versi

**Versi saat ini: 1.4.0** (2026-10-07)

| Versi | Tanggal | Commit | Perubahan |
|-------|---------|--------|-----------|
| 1.4.0 | 2026-10-07 | `a727271` | Rebranding lanjutan: teks "Morela / Desa Morela" → "Negeri Morella" di 5 halaman (beranda, peta, program, tiket, UMKM) |
| 1.3.0 | 2026-10-07 | `46eb96f` | Login admin server-side: autentikasi username + kata sandi dari database, middleware `admin.auth`, proteksi data & route admin, tombol logout |
| 1.2.0 | 2026-10-07 | `2e11c42` | Migrasi penyimpanan data dari file JSON ke MySQL: 11 tabel, model Eloquent, `MorelaStore` DB-backed, seeder default + impor data lama |
| 1.1.0 | 2026-10-07 | `a7eebab` | Rebranding tampilan "NEGERI MORELLA": hapus logo & nomor telepon, tambah logout admin |
| 1.0.0 | 2026-10-07 | `21b78ef` | Rilis awal: konversi React SPA → Laravel Blade untuk seluruh halaman publik & admin |

## Teknologi

| Komponen | Versi |
|----------|-------|
| PHP | ^8.2 |
| Laravel Framework | ^11.9 (terpasang 11.57.0) |
| MySQL | 8.x — database mis. `desa_morella` |
| Alpine.js | ^3.15 |
| Tailwind CSS | ^4.3 |
| Vite | ^8.3 |
| laravel-vite-plugin | ^3.2 |

## Fitur

**Halaman publik** — Beranda, Destinasi, Tiket, Budaya, UMKM, Peta, Agenda, Berita, Galeri, Program Pengabdian, Media Sosial.

- Pemesanan tiket online: kode booking `MOR-YYMMDD-XXXX` + QR untuk check-in di lokasi
- Statistik kunjungan bulanan dari pengunjung unik (cookie, tabel `visit_logs`)
- Dukungan dua bahasa (ID/EN) pada elemen antarmuka utama

**Panel admin** (`/admin`, wajib login):

- CRUD konten: destinasi, UMKM, berita, agenda (termasuk unggah video 2–500 MB), budaya, galeri, tim
- Kelola pesanan tiket: konfirmasi pembayaran & check-in pengunjung
- Pengaturan: metode pembayaran, slider hero, teks hero, info kontak
- Logout (server-side session)

## Menjalankan Secara Lokal

Prasyarat: PHP ≥ 8.2, Composer, Node.js, MySQL.

```bash
# 1. Dependensi PHP
composer install

# 2. Konfigurasi environment
cp .env.example .env
php artisan key:generate
#    → .env.example bernilai default PRODUKSI; untuk lokal ubah:
#      APP_ENV=local, APP_DEBUG=true, APP_URL=http://127.0.0.1:8099
#    → sesuaikan DB_DATABASE / DB_USERNAME / DB_PASSWORD di .env

# 3. Skema + data awal (11 tabel konten + akun admin)
php artisan migrate --seed

# 4. Aset frontend
npm install
npm run build        # produksi; gunakan `npm run dev` saat pengembangan

# 5. Jalankan server
php artisan serve --port=8099
```

- Aplikasi: http://127.0.0.1:8099
- Admin: http://127.0.0.1:8099/admin — akun default dibuat oleh `database/seeders/AdminSeeder.php` (username `admin`). **Wajib ganti kata sandi setelah deploy** (kredensial default ada di repo publik).

## Deploy ke Web Hosting

Prinsip utama: **document root mengarah ke folder `public/`**, `APP_DEBUG=false`, dan `.env` tidak pernah berada di dalam `public_html`.

1. **Syarat** — PHP ≥ 8.2 + MySQL; disarankan ada Terminal/SSH. Unggah video 500 MB butuh `upload_max_filesize` & `post_max_size` ≥ 500 MB.
2. **Upload** — ekstrak proyek di luar `public_html` (mis. `/home/USER/morella`), arahkan document root ke `morella/public`. Fallback tanpa akses document root: salin isi `public/` ke `public_html/` lalu sesuaikan 2 baris path di `public_html/index.php`.
3. **`.env`** — salin dari `.env.example`; isi APP_KEY (`php artisan key:generate --show`), kredensial DB cPanel, dan APP_URL. Pastikan `APP_ENV=production` + `APP_DEBUG=false`.
4. **Database** — impor dump SQL lokal via phpMyAdmin; alternatif: `php artisan migrate --seed`.
5. **Dependensi & storage** — `php composer.phar install --no-dev --optimize-autoloader` (atau unggah folder `vendor/`), lalu `php artisan storage:link`.
6. **Permission** — folder `storage/` dan `bootstrap/cache/` harus writable (755).
7. **Keamanan** — aktifkan HTTPS (AutoSSL); ganti kata sandi admin; jangan pernah mengunggah `.env` lokal, `storage/app/morela-data.json`, `.git/`, atau `BACKUP-react-spa-*.tar.gz`.

## Struktur Singkat

```
app/Http/Controllers/       PageController (halaman), ActionController (aksi admin & tiket),
                            AdminAuthController (login/logout)
app/Http/Middleware/        EnsureAdminAuthenticated (alias: admin.auth)
app/Support/MorelaStore.php façade akses data DB (API identik dengan era JSON)
app/Models/                 11 model Eloquent (Booking, Destination, Event, …)
resources/views/            seluruh tampilan Blade (+ partials footer/navbar/modal)
routes/web.php              route publik, tiket (publik), dan grup admin (admin.auth)
database/migrations/        skema 11 tabel konten + users + tabel bawaan Laravel
database/seeders/           MorelaSeeder, LegacyImportSeeder, AdminSeeder
```

Catatan skema: 11 tabel konten = destinations, umkm_products, news_articles, events, culture_items, gallery_items, team_members, map_markers, bookings, portal_settings, visit_logs — ditambah tabel bawaan Laravel (users, cache, jobs).

## Riwayat Data & Backup

- Data era JSON diamankan di `storage/app/morela-data.json` (tidak di-commit — memuat data pribadi pemesan) dan dapat diimpor ulang lewat `LegacyImportSeeder`.
- Sumber React lama yang sudah dihapus dapat dipulihkan dari `BACKUP-react-spa-2026-10-07.tar.gz`.

---

© 2026 Pemerintah Negeri Morella & Program Pengabdian Mahasiswa Universitas Darussalam Ambon.
