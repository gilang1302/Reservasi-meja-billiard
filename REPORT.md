# LAPORAN PROGRESS TUGAS BESAR — 100% IMPLEMENTASI
## PERANCANGAN PERANGKAT LUNAK: CUEMASTER RESERVE

### Anggota Kelompok:
* 2272024 GILANG ARDIWILAGA
* 2372006 STEPANUS SUGIANTO
* 2372056 MAVERICK RAFAEL TANADI
* 2372059 SYAHRIAL ACHMAD
* 2272040 DAUD PANJAITAN

**Program Studi Teknik Informatika**  
**Universitas Kristen Maranatha, Bandung**  
**2026**

---

### 1. RINGKASAN PROGRESS & TARGET MINGGU INI (100% IMPLEMENTASI)
Pada minggu ini, kelompok kami telah menyelesaikan **100% implementasi** dari platform **CueMaster Reserve**, yaitu sistem reservasi dan manajemen meja billiard modern berbasis web. Seluruh 15 fitur yang direncanakan beserta 7 design pattern pendukung telah terintegrasi penuh ke dalam sistem menggunakan framework **Laravel 12** dan **TailwindCSS**.

*   **Tautan Branch GitHub Progres Minggu Ini**: `final-implementation`
*   **Dokumen Guideline Akses Publik**: Telah disertakan pada file [GUIDELINES.md](file:///c:/Users/Administrator/Desktop/PDPL/TUBES/Reservasi-meja-billiard/GUIDELINES.md) di direktori utama proyek.

---

### 2. DETAIL IMPLEMENTASI FITUR (15/15 FITUR SELESAI)

1.  **Sistem Autentikasi Multi-Role**: Pemisahan hak akses menggunakan middleware antara Pelanggan (reservasi & order), Operator/Kasir (kontrol lampu & transaksi), dan Owner (analitik & laporan).
2.  **Dashboard Profil & Riwayat Poin**: Visualisasi profil pelanggan beserta progress level member (Bronze, Gold, Platinum) dan poin loyalitas akumulatif.
3.  **Sistem Notifikasi Push/WhatsApp**: Notifikasi simulator yang otomatis terkirim untuk mengingatkan pembayaran dan status sesi bermain.
4.  **Visual Denah Meja Interaktif**: Denah hall billiard interaktif berbasis CSS Grid yang menampilkan status meja secara real-time dan dapat di-klik untuk reservasi langsung.
5.  **Penjadwalan Slot Waktu Dinamis**: Validasi sistem backend untuk mendeteksi tumpang tindih slot sewa guna mencegah terjadinya *double booking*.
6.  **Sistem Harga Bertingkat (Peak/Off-Peak)**: Logika kalkulasi harga dinamis di mana tarif meja naik 50% pada jam sibuk (17:00 - 23:00) secara otomatis.
7.  **Integrasi Payment Gateway**: Simulasi transfer bank manual menggunakan upload gambar bukti pembayaran yang langsung masuk ke antrean verifikasi kasir.
8.  **Pemesanan F&B (Food & Beverage)**: Pemilihan makanan/minuman dari katalog menu oleh pelanggan aktif yang dikirim langsung ke dapur kasir.
9.  **Manajemen Inventaris Alat**: Melacak stik billiard pro, set bola, dan aksesoris lainnya yang disewa beserta tarif per jamnya.
10. **Panel Kontrol Lampu Meja (Simulasi)**: Tombol toggle switch lampu meja di panel kasir terintegrasi via AJAX, di mana lampu menyala otomatis saat sesi bermain dimulai.
11. **Laporan Pendapatan Otomatis**: Dasbor laporan owner yang memetakan statistik pendapatan harian, mingguan, dan bulanan yang siap dicetak/diekspor ke PDF.
12. **Sistem Perpanjangan Waktu Otomatis (Extend Time)**: Pelanggan dapat menambah durasi bermain 1-2 jam langsung dari web jika slot jam berikutnya masih kosong.
13. **Sistem Rating & Feedback Pelanggan**: Pelanggan memberikan rating bintang 1-5 dan ulasan setelah sesi bermain selesai untuk diulas oleh owner.
14. **Leaderboard & Gamifikasi**: Peringkat 50 besar pelanggan berdasarkan jam bermain dan poin loyalitas untuk meningkatkan retensi.
15. **Analitik Penggunaan Meja (Heatmap)**: Representasi visual denah meja dengan gradien warna intensitas sewa (panas ke dingin) untuk analisis layout.

---

### 3. PENERAPAN DESIGN PATTERN (7/7 DESIGN PATTERNS)

#### 1. Singleton Pattern (Creational)
*   **Penerapan**: Koneksi Database Laravel.
*   **Deskripsi**: Laravel Service Container mengikat instance koneksi database (SQLite/MySQL) secara tunggal (*shared singleton*) di seluruh siklus hidup aplikasi untuk menghemat memori.

#### 2. Strategy Pattern (Behavioral)
*   **Penerapan**: Modul Kalkulasi Harga (`app/Services/Pricing/`).
*   **Deskripsi**: `PricingContext` mendeteksi jam bermain dan level member untuk memilih strategi kalkulasi harga yang tepat secara dinamis (`NormalPricingStrategy`, `PeakHourPricingStrategy`, atau `MemberDiscountPricingStrategy`).

#### 3. Factory Pattern (Creational)
*   **Penerapan**: Pembuatan Anggota Leveling (`app/Factories/MemberFactory.php`).
*   **Deskripsi**: Membuat konfigurasi objek member secara dinamis (Bronze, Gold, Platinum) yang memiliki keuntungan tarif diskon dan prioritas antrean yang berbeda.

#### 4. State Pattern (Behavioral)
*   **Penerapan**: Transisi Status Meja (`app/States/TableState/`).
*   **Deskripsi**: Mengelola perubahan status operasional meja (`AvailableState`, `BookedState`, `OccupiedState`, `MaintenanceState`) yang mempengaruhi status lampu meja secara terstruktur.

#### 5. Facade Pattern (Structural)
*   **Penerapan**: Checkout Reservasi (`app/Services/BookingFacade.php`).
*   **Deskripsi**: Menyediakan satu method sederhana `confirmBooking()` untuk mengoordinasikan pengecekan ketersediaan meja, perhitungan harga, penyimpanan transaksi, dan pengiriman notifikasi.

#### 6. Decorator Pattern (Structural)
*   **Penerapan**: Penyusunan Invoice Tagihan (`app/Decorators/`).
*   **Deskripsi**: Menghitung total tagihan dengan menyusun komponen biaya secara dinamis: `BaseBookingCost` (biaya meja) dibungkus oleh `EquipmentRentalDecorator` (sewa stik) dan `FnBDecorator` (makanan/minuman).

#### 7. Template Method Pattern (Behavioral)
*   **Penerapan**: Pembuatan Laporan Pendapatan (`app/Reports/`).
*   **Deskripsi**: `ReportTemplate` mendefinisikan algoritme umum kompilasi laporan (query dasar, perhitungan total), sedangkan subclass (`DailyReport`, `WeeklyReport`, `MonthlyReport`) mengimplementasikan logika spesifik pengelompokan baris data.

---

### 4. DEKLARASI PENGGUNAAN AI ATAU SUMBER LAIN
Dalam pengerjaan tugas besar akhir ini, kami menggunakan bantuan AI **Antigravity** (desain asisten AI dari tim Google DeepMind) sebagai rekan pemrograman (*pair programming partner*).

*   **Kontribusi AI**:
    1.  Membantu pembuatan kerangka class-class design pattern (Strategy, State, Decorator, dll.) agar memenuhi kaidah *clean code* dan *single responsibility*.
    2.  Membantu penulisan kerangka halaman user interface (UI) dengan Blade & TailwindCSS agar responsive, modern, dan memiliki visual dark mode yang premium.
    3.  Membantu penulisan file migrasi tambahan untuk relasi tabel yang kurang.
*   **Tinjauan & Modifikasi Manusia**: Seluruh kode yang dihasilkan oleh AI telah ditinjau kembali, diuji coba alurnya secara manual, disesuaikan dengan skema database SQL lokal, dan diintegrasikan secara penuh oleh tim mahasiswa.

---
**Bandung, 21 Juni 2026**
*Kelompok CueMaster Reserve*
