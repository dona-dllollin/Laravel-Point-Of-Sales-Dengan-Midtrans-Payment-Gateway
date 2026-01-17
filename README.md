# Sistem Point of Sales (POS) Terpadu

Sistem POS ini adalah aplikasi berbasis web yang dirancang untuk membantu pengelolaan transaksi penjualan, stok barang, manajemen karyawan, serta pelaporan keuangan untuk toko atau usaha retail. Aplikasi ini dibangun menggunakan framework **Laravel** dan modern asset bundler **Vite**.

## Fitur Utama

### 🛒 Transaksi & Kasir
- **Interface POS**: Antarmuka kasir yang responsif untuk proses checkout cepat.
- **Manajemen Keranjang**: Tambah, kurangi, dan hapus item dengan mudah.
- **Cetak Struk**: Dukungan cetak struk belanja menggunakan printer thermal (ESC/POS).
- **Pembayaran**: Mendukung pembayaran tunai dan digital (Gateway Midtrans).

### 📦 Manajemen Produk & Stok
- **CRUD Produk**: Tambah, ubah, dan hapus data produk.
- **Stok**: Manajemen stok masuk (Supply) dan monitoring ketersediaan barang.
- **Kategori & Satuan**: Pengelompokan produk berdasarkan kategori dan satuan unit.

### 👥 Manajemen Pengguna & Karyawan
- **Multi-Role**: Akses berbeda untuk **Admin** dan **Kasir**.
- **Manajemen Karyawan**: Kelola data karyawan dan akses toko.
- **Profil**: Pengaturan profil pengguna dan ubah password.

### 💰 Keuangan & Laporan
- **Laporan Transaksi**: Rekap transaksi harian dan bulanan.
- **Ekspor Data**: Unduh laporan dalam format **Excel** dan **PDF**.
- **Manajemen Hutang (Debt)**: Pencatatan dan histori pembayaran hutang pelanggan.

### 🏪 Manajemen Toko
- Mendukung pengelolaan informasi toko (Nama, Alamat, dll).

## Teknologi yang Digunakan

- **Bahasa Pemrograman**: PHP ^8.1
- **Framework Backend**: Laravel Framework 10.x
- **Frontend Assets**: Vite
- **Database**: MySQL
- **Templating Engine**: Blade
- **Library Pendukung**:
  - `midtrans/midtrans-php`: Payment Gateway
  - `mike42/escpos-php`: Thermal Printing
  - `maatwebsite/excel`: Export ke Excel
  - `barryvdh/laravel-dompdf`: Generate PDF

## Struktur Folder Utama

- `app/Http/Controllers`: Logika aplikasi (Transaksi, Produk, Laporan, dll).
- `app/Models`: Model database Eloquent.
- `database/migrations`: Skema database.
- `resources/views`: Tampilan antarmuka (Blade templates).
- `routes/web.php`: Definisi rute aplikasi.
- `public`: Aset publik (CSS, JS, Images).

## Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di komputer lokal Anda:

### Prasyarat
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL Database

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd Sistem-POS-FIX
   ```

2. **Install Dependencies PHP**
   ```bash
   composer install
   ```

3. **Install Dependencies Frontend**
   ```bash
   npm install
   ```

4. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` dan sesuaikan konfigurasi database Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Simulasi Data (Seeding)**
   Jalankan migrasi database dan seeder untuk data awal:
   ```bash
   php artisan migrate --seed
   ```

6. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

7. **Build Assets**
   ```bash
   npm run build
   ```
   Atau untuk mode pengembangan:
   ```bash
   npm run dev
   ```

## Menjalankan Aplikasi

Jalankan lokal development server Laravel:

```bash
php artisan serve
```

Akses aplikasi melalui browser di: `http://localhost:8000`

## Konfigurasi Penting

Beberapa variabel di `.env` yang perlu diperhatikan:

- **APP_URL**: URL aplikasi (default: `http://localhost`).
- **Midtrans**: Jika menggunakan fitur pembayaran online, tambahkan key berikut (sesuaikan dengan akun Midtrans Anda):
  ```env
  MIDTRANS_SERVER_KEY=your_server_key
  MIDTRANS_CLIENT_KEY=your_client_key
  MIDTRANS_IS_PRODUCTION=false
  ```
- **Printer**: Konfigurasi printer jika diperlukan untuk fitur cetak struk.

## Contoh Penggunaan (Akun Demo)

Jika Anda menjalankan `php artisan migrate --seed`, sistem akan membuat akun default (cek `database/seeders/UserSeeder.php` untuk detail pastinya). Biasanya:

- **Admin**:
  - Email: `admin@gmail.com` (Contoh asumsi, cek seeder)
  - Password: `password`
- **Kasir**:
  - Email: `kasir@gmail.com`
  - Password: `password`

## Catatan Tambahan

- Pastikan ekstensi PHP yang dibutuhkan Laravel (seperti `zip`, `xml`, `gd`) sudah aktif.
- Untuk fitur cetak struk, pastikan printer terhubung dan driver terinstal dengan benar jika berjalan di lingkungan lokal.
