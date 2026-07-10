# DOKUMEN PANDUAN PENGGUNAAN & AKSES PERANGKAT LUNAK
## CueMaster Reserve — Platform Reservasi & Manajemen Meja Billiard Modern

Selamat datang di **CueMaster Reserve**, platform manajemen dan reservasi meja billiard berbasis web yang dirancang khusus untuk memodernisasi operasional hall billiard, mempermudah pelanggan dalam memilih meja secara interaktif, memesan F&B, dan mengontrol pencahayaan meja secara otomatis.

Dokumen ini disusun untuk panduan pengujian bagi dosen/asisten praktikum.

---

### 1. DAFTAR AKUN UJI COBA (CREDENTIALS)

Semua akun berikut diseed secara otomatis dalam database dengan sandi: `password123`

| Role / Peran | Alamat Email | Sandi (Password) | Fitur Utama |
|---|---|---|---|
| **Owner (Pemilik)** | `owner@cuemaster.com` | `password123` | Laporan Keuangan, Heatmap Meja, CRUD Inventaris Alat & Meja, Feedback |
| **Operator (Kasir)** | `kasir@cuemaster.com` | `password123` | Kontrol Lampu Meja (Simulasi), Verifikasi Pembayaran & F&B |
| **Pelanggan (Platinum)** | `maverick@gmail.com` | `password123` | Reservasi Meja, Diskon Member 20%, Prioritas, VIP Room Access |
| **Pelanggan (Gold)** | `syahrial@gmail.com` | `password123` | Reservasi Meja, Diskon Member 10%, Order F&B |
| **Pelanggan (Bronze)** | `daud@gmail.com` | `password123` | Reservasi Meja (Default Member), Riwayat Bermain, Rating |

---

### 2. LANGKAH-LANGKAH MENJALANKAN APLIKASI SECARA LOKAL

Aplikasi dibangun menggunakan framework **Laravel 12** dan **TailwindCSS**. Ikuti perintah berikut di terminal/Powershell Anda:

1. **Instalasi Dependensi PHP (Composer)**:
   ```bash
   composer install
   ```

2. **Instalasi Dependensi Assets (NPM)**:
   ```bash
   npm install
   ```

3. **Duplikasi File Environment**:
   ```powershell
   copy .env.example .env
   ```

4. **Konfigurasi Database (Default: SQLite)**:
   Buat database kosong `database.sqlite` di folder `database/` (Aplikasi akan otomatis menggunakannya).
   ```powershell
   New-Item -Path "database\database.sqlite" -ItemType "file"
   ```

5. **Generate Kunci Aplikasi**:
   ```bash
   php artisan key:generate
   ```

6. **Jalankan Migrasi & Seeders**:
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Jalankan Server Lokal**:
   * Jalankan web server Laravel:
     ```bash
     php artisan serve
     ```
   * Jalankan compiler aset Vite:
     ```bash
     npm run dev
     ```
   Akses aplikasi melalui browser Anda di tautan: `http://127.0.0.1:8000`

---

### 3. ALUR OPERASIONAL PENGUJIAN FITUR (TEST RUN SCENARIO)

#### Skenario 1: Alur Booking Pelanggan
1. Masuk / Log In sebagai pelanggan: `maverick@gmail.com` / `password123`.
2. Buka menu **Denah Meja (Booking)**.
3. Saring tanggal bermain, jam mulai (misalnya pukul `18:00` untuk menguji **Peak Hour Pricing Strategy**), dan jam selesai.
4. Klik salah satu meja yang berwarna hijau (Tersedia) pada denah interaktif.
5. Klik **Lanjutkan Reservasi**.
6. Pada halaman Konfirmasi, Anda dapat memilih stik premium opsional (memicu **Equipment Rental Decorator**) dan mengisi catatan tambahan. Klik **Buat Reservasi & Bayar**.
7. Anda akan dialihkan ke rincian reservasi berstatus *Pending*. Klik **Upload Bukti Transfer**.
8. Pilih metode transfer Bank BCA atau scan QRIS, lampirkan screenshot bukti transfer, lalu klik **Konfirmasi & Kirim**. Status booking akan berganti menjadi *Confirmed* (menunggu verifikasi operator).

#### Skenario 2: Verifikasi & Kontrol Kasir
1. Masuk / Log In sebagai kasir: `kasir@cuemaster.com` / `password123`.
2. Buka menu **Transaksi Pembayaran** untuk memverifikasi bukti transfer pelanggan (Klik **Verifikasi**).
3. Buka menu **Kontrol Meja & Lampu**.
4. Disini kasir dapat menyalakan/mematikan lampu meja billiard secara digital dengan meng-klik toggle switch. Toggle switch bekerja menggunakan **AJAX** tanpa me-refresh halaman web (memicu notifikasi Toast).
5. Klik **Mulai Sesi Main** pada meja yang sudah di-booking oleh pelanggan untuk mengubah status meja menjadi *Occupied* (lampu menyala otomatis).
6. Setelah selesai, kasir meng-klik **Selesaikan Sesi** pada meja tersebut untuk melepas meja kembali menjadi *Available* (lampu mati otomatis).

#### Skenario 3: Analitik Bisnis Owner
1. Masuk / Log In sebagai owner: `owner@cuemaster.com` / `password123`.
2. Di dashboard owner, Anda akan disajikan grafik tren pendapatan 7 hari terakhir (Chart.js) dan daftar meja terpopuler.
3. Buka menu **Laporan Keuangan** untuk menganalisis pendapatan harian/mingguan/bulanan berdasarkan **Template Method Pattern**. Owner dapat mencetak laporan secara langsung menggunakan tombol **Cetak / Ekspor PDF**.
4. Buka menu **Heatmap Meja** untuk melihat visualisasi meja mana saja yang paling sering disewa dalam gradien warna "Panas" (merah pekat) hingga "Dingin" (biru).

---

### 4. PENERAPAN DESIGN PATTERN PADA SOFTWARE

1. **Singleton Pattern**: Digunakan pada instance koneksi database (SQLite/MySQL) yang diatur oleh Laravel Container agar hanya ada satu instance koneksi database aktif untuk menghemat memori.
2. **Strategy Pattern**: Diterapkan pada kelas di `app/Services/Pricing/` untuk menghitung harga sewa dinamis berdasarkan waktu (Normal Hour vs Peak Hour + diskon loyalitas tier member) tanpa merusak kode inti transaksi.
3. **Factory Pattern**: Terletak di `app/Factories/MemberFactory.php` untuk memproduksi objek member (Bronze, Gold, Platinum) dengan limitasi booking dan multiplikasi perolehan poin loyalitas yang berbeda.
4. **State Pattern**: Terletak di `app/States/TableState/` untuk mengontrol transisi status meja billiard (Available, Booked, Occupied, Maintenance) dan status lampu meja terintegrasi.
5. **Facade Pattern**: Terletak di `app/Services/BookingFacade.php` untuk membungkus kerumitan proses reservasi (validasi slot tabrakan, pembuatan model sewa, kalkulasi harga, generator notifikasi WhatsApp/Push) dalam satu API call sederhana.
6. **Decorator Pattern**: Terletak di `app/Decorators/` untuk menyusun rincian invoice secara dinamis (biaya sewa meja dasar + biaya rental alat premium + biaya order F&B kantin).
7. **Template Method Pattern**: Terletak di `app/Reports/` untuk menyatukan struktur kompilasi laporan keuangan harian, mingguan, dan bulanan secara teratur.

---
**CueMaster Reserve Team 2026**
* Gilang Ardiwilaga (2272024)
* Stepanus Sugianto (2372006)
* Maverick Rafael Tanadi (2372056)
* Syahrial Achmad (2372059)
* Daud Panjaitan (2272040)
