<h2>Restoku POS - By Ahmad Aminudin</h2>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

![restokuDb](https://github.com/user-attachments/assets/ec87add6-36d4-459a-8748-38e0c018b279)


# Restoku

Restoku adalah aplikasi web point of sales yang saya rancang khusus untuk usaha restoran. Aplikasi ini memiliki antarmuka sederhana namun elegan, sehingga mudah dioperasikan oleh owner atau admin restoran. 

Restoku saya kembangkan menggunakan:
- **Backend:** Laravel 11 (PHP)
- **Frontend:** Bootstrap framework

## Fitur Utama

### 1. Fitur Login dan Register
- Sistem autentikasi yang aman untuk admin restoran.

### 2. Manajemen Menu
- **CRUD Menu:** Tambah, ubah, hapus, dan lihat daftar menu.
- **Kategori Menu:** Mengelompokkan menu berdasarkan kategori (contoh: Minuman, Hidangan Utama, Dessert).
- **Harga Dinamis:** Mendukung variasi harga berdasarkan ukuran porsi atau tipe menu.
- **Foto Menu:** Menambahkan gambar untuk setiap item menu.

### 3. Sistem Pemesanan
- **Nomor Meja:** Melacak pesanan berdasarkan nomor meja.
- **Catatan Khusus:** Menambahkan catatan pada pesanan (contoh: "Tanpa gula", "Tambah pedas").

### 4. Sistem Transaksi
- **Metode Pembayaran:** Mendukung pembayaran tunai.
- **Cetak Struk:** Mencetak struk pembayaran langsung dari sistem.
- **Histori Transaksi:** Menyimpan data transaksi untuk keperluan audit.
- **Pajak dan Diskon:** Menghitung pajak otomatis dan mendukung diskon.

### 5. Pelaporan dan Analitik
- **Laporan Penjualan:** Menyediakan rekap data penjualan harian, mingguan, bulanan, dan tahunan.
- **Laporan Menu Terlaris:** Menampilkan item menu yang paling banyak dipesan.
- **Export Data:** Ekspor laporan dalam format Excel atau PDF.

### 6. Sistem Keanggotaan dan Loyalitas
- **Manajemen Pelanggan:** Menyimpan data pelanggan.
- **Poin Loyalitas:** Memberikan poin untuk pelanggan setia.

## Cara Install
1. Clone repository ini:
   ```
   git clone https://github.com/username/restoku.git
   cd restoku
   ```
2. Install dependencies menggunakan Composer dan NPM:
    ```
    composer install
    npm install && npm run dev
    ```
3. Setup file .env dan lakukan konfigurasi database.

4. Jalankan migrasi database:
    ```
    php artisan migrate
    ```

5. Jalankan server lokal:

    ```
    php artisan serve
    ```

Akses aplikasi di http://localhost:8000.

## Kontribusi
Kontribusi sangat terbuka! Silakan fork repository ini dan kirimkan pull request Anda.

## Lisensi
Proyek ini dilisensikan di bawah MIT License.

## Kontak
Jika ada pertanyaan atau masukan, silakan hubungi saya di whichaldin@gmail.com / aldinprogrammer@gmail.com
