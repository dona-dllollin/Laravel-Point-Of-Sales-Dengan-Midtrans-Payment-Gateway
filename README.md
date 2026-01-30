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

    ## ScreenShot Aplikasi

| Description                  | Image                                                                         |
| ---------------------------- | ----------------------------------------------------------------------------- |
| Login Form                   | ![Login Form](./images/1._form_login.png)                                     |
| Registration Form            | ![Registration Form](./images/2._form_registrasi.png)                         |
| Dashboard                    | ![Dashboard](./images/3._dashboard.png)                                       |
| Manage Employees             | ![Manage Employees](./images/4._kelola_karyawan.png)                          |
| Add Employee                 | ![Add Employee](./images/4.1_tambah_karyawan.png)                             |
| Edit Employee Data           | ![Edit Employee Data](./images/4.2_edit_data_karyawan.png)                    |
| Employee Details             | ![Employee Details](./images/4.3_detail_data_karyawan.png)                    |
| Manage Users                 | ![Manage Users](./images/5._kelola_user.png)                                  |
| Add User                     | ![Add User](./images/5.1_tambah_user.png)                                     |
| Edit User                    | ![Edit User](./images/5.2_edit_user.png)                                      |
| Manage Categories            | ![Manage Categories](./images/6._kelola_kategori.png)                         |
| Add Category                 | ![Add Category](./images/6.1_tambah_kategori.png)                             |
| Edit Category                | ![Edit Category](./images/6.2_edit_kategori.png)                              |
| Manage Units                 | ![Manage Units](./images/7._kelola_satuan.png)                                |
| Add Unit                     | ![Add Unit](./images/7.1_tambah_satuan.png)                                   |
| Edit Unit                    | ![Edit Unit](./images/7.2_edit_satuan.png)                                    |
| Manage Products              | ![Manage Products](./images/8._kelola_barang.png)                             |
| Add Product                  | ![Add Product](./images/8.1_tambah_barang.png)                                |
| Edit Product                 | ![Edit Product](./images/8.2_edit_barang.png)                                 |
| Product Details              | ![Product Details](./images/8.3_detail_barang.png)                            |
| Add Stock                    | ![Add Stock](./images/9._tambah_stok.png)                                     |
| Add Stock Form               | ![Add Stock Form](./images/9.1._form_tambah_stok.png)                         |
| Export Expenditure Report    | ![Export Expenditure Report](./images/9.2._export_laporan_pengeluaran.png)    |
| PDF Expenditure Report       | ![PDF Expenditure Report](./images/9.3_pdf_laporan_pengeluaran.png)           |
| Debt List                    | ![Debt List](./images/10._daftar_utang.png)                                   |
| Export Debt Report           | ![Export Debt Report](./images/10.1._export_laporan_utang.png)                |
| Debt Payment                 | ![Debt Payment](./images/10.2_angsur_utang.png)                               |
| Delete Debt Data             | ![Delete Debt Data](./images/10.3_hapus_data_utang.png)                       |
| Debt Details                 | ![Debt Details](./images/10.4_detail_utang.png)                               |
| PDF Debt Report              | ![PDF Debt Report](./images/10.5_pdf_laporan_utang.png)                       |
| Payment History              | ![Payment History](./images/11._histori_angsuran.png)                         |
| PDF Payment History Report   | ![PDF Payment History Report](./images/11.1_pdf_laporan_histori_angsuran.png) |
| POS Transaction              | ![POS Transaction](./images/12._transaksi_POS.png)                            |
| Transaction with Debt Method | ![Transaction with Debt Method](./images/12.1._transaksi_metode_utang.png)    |
| Midtrans Snap                | ![Midtrans Snap](./images/12.2._snap_midtrans.png)                            |
| Midtrans Success             | ![Midtrans Success](./images/12.3._midtrans_sukses.png)                       |
| Transaction Success          | ![Transaction Success](./images/12.4._transaksi_sukses.png)                   |
| Scan Barcode                 | ![Scan Barcode](./images/12.5._scan_barcode.png)                              |
| Scan Barcode Success         | ![Scan Barcode Success](./images/12.6_scan_barcode_berhasil.png)              |
| Manage Store                 | ![Manage Store](./images/13._kelola_toko.png)                                 |
| Edit Store                   | ![Edit Store](./images/13.1._edit_toko.png)                                   |
| Store Details                | ![Store Details](./images/13.2._detail_toko.png)                              |
| Transaction Report           | ![Transaction Report](./images/14._laporan_transaksi.png)                     |
| Print Receipt                | ![Print Receipt](./images/14.1_cetak_struk.png)                               |
| Print Debt Receipt           | ![Print Debt Receipt](./images/14.2_cetak_struk_utang.png)                    |
| Transaction Details          | ![Transaction Details](./images/14.2_detail_transaksi.png)                    |
| PDF Transaction Report       | ![PDF Transaction Report](./images/14.3_pdf_laporan_transaksi.png)            |
| Profile Page                 | ![Profile Page](./images/15._halaman_profil.png)                              |
| Change Password Form         | ![Change Password Form](./images/15.1._form_ubah_password.png)                |
| Logout                       | ![Logout](./images/16._logout.png)                                            |
| Screenshot                   | ![Screenshot](<./images/Screenshot_(89).png>)                                 |
| Sales Transaction            | ![Sales Transaction](./images/transasksi_penjualan.png)                       |

## Catatan Tambahan

- Pastikan ekstensi PHP yang dibutuhkan Laravel (seperti `zip`, `xml`, `gd`) sudah aktif.
- Untuk fitur cetak struk, pastikan printer terhubung dan driver terinstal dengan benar jika berjalan di lingkungan lokal.
