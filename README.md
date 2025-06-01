# SISINPEM - Sistem Informasi Inventaris Pembelajaran

Selamat datang di SISINPEM, sebuah aplikasi web yang dirancang untuk membantu institusi pendidikan dalam mengelola inventaris barang dan perlengkapan pembelajaran secara efisien. Aplikasi ini dibangun untuk tujuan pembelajaran oleh Kelompok 2 MIC2023.

## Deskripsi Singkat

SISINPEM memungkinkan administrator untuk mencatat, melacak, dan mengelola aset pembelajaran, mulai dari buku, peralatan lab, hingga perangkat elektronik. Mahasiswa dapat melihat daftar inventaris yang tersedia dan melaporkan kerusakan barang dengan mudah. Sistem ini juga dilengkapi dengan notifikasi otomatis untuk kejadian penting seperti pelaporan kerusakan baru atau penambahan item inventaris.

Aplikasi ini dirancang dengan antarmuka yang responsif namun sangat direkomendasikan untuk diakses melalui perangkat desktop untuk pengalaman pengguna yang optimal karena kompleksitas beberapa fitur manajemen.

## Fitur Utama

### Panel Admin:
* **Dashboard Ringkasan:** Menampilkan statistik kunci mengenai total item, kuantitas, kondisi barang, jumlah laporan kerusakan (terbuka/selesai), jumlah kategori, dan ringkasan pengguna.
* **Manajemen Kategori:** Operasi CRUD (Create, Read, Update, Delete) untuk kategori barang.
* **Manajemen Item (Data Inventaris):** Operasi CRUD untuk item inventaris, termasuk upload gambar, pemilihan kategori, pencatatan kode unik, kuantitas, kondisi, dan lokasi.
* **Manajemen Laporan Kerusakan:** Melihat daftar laporan kerusakan yang dikirim oleh mahasiswa atau dibuat oleh admin, memperbarui status laporan (misalnya, diverifikasi, dalam perbaikan, selesai diperbaiki, dihapuskan), menambahkan catatan admin, dan melihat detail laporan termasuk tipe kerusakan (ringan, sedang, berat) dan foto kerusakan.
* **Manajemen Notifikasi:** Melihat daftar notifikasi yang dihasilkan sistem (misalnya, laporan kerusakan baru, item baru ditambahkan), menandai notifikasi sebagai sudah dibaca/belum dibaca, dan menghapus notifikasi.
* **Manajemen Pengguna (implisit):** Sistem membedakan peran admin dan mahasiswa, di mana akun dibuat melalui antarmuka administrator basis data (seperti phpMyAdmin).

### Panel Mahasiswa:
* **Dashboard Mahasiswa:** Menampilkan ringkasan laporan kerusakan yang pernah dibuat dan tautan cepat ke fitur lain.
* **Lihat Daftar Barang (Cek Barang):** Mencari dan memfilter daftar item inventaris yang tersedia.
* **Lapor Kerusakan Barang:** Mengirimkan laporan kerusakan untuk item tertentu, termasuk deskripsi kerusakan, pemilihan tipe kerusakan (ringan, sedang, berat), dan kemampuan untuk mengunggah foto kerusakan.

### Fitur Sistem:
* **Notifikasi Otomatis:** Notifikasi dikirim ke admin ketika ada laporan kerusakan baru atau ketika item inventaris baru ditambahkan.
* **Kontrol Akses Berbasis Peran:** Fungsionalitas yang berbeda untuk peran Admin dan Mahasiswa.
* **Antarmuka Pengguna:** Didesain dengan tema warna biru dominan (`blue-700`) dan tidak menggunakan dark mode.
* **Deteksi Perangkat:** Memberikan rekomendasi atau mengarahkan pengguna untuk menggunakan perangkat desktop demi fungsionalitas penuh.

## Teknologi yang Digunakan

* **Framework Backend:** Laravel 11
* **Framework Frontend Dinamis:** Livewire
* **Scaffolding Autentikasi & UI Awal:** Laravel Jetstream (dengan stack Livewire)
* **Styling:** Tailwind CSS
* **Ikon:** Heroicons (digunakan melalui komponen Blade)
* **Database:** MySQL (atau database relasional lain yang didukung Laravel)
* **Web Server:** Apache/Nginx (atau `php artisan serve` untuk development)
* **PHP:** Versi 8.2 atau lebih baru (sesuai kebutuhan Laravel 11)
* **Manajemen Dependensi:** Composer (PHP), NPM/Yarn (JavaScript)

## Prasyarat

Sebelum Anda memulai, pastikan sistem Anda telah terinstal:
* PHP >= 8.2
* Composer versi 2.x
* Node.js & NPM (atau Yarn)
* Database Server (misalnya MySQL, MariaDB)
* Git (opsional, untuk kloning)

## Panduan Instalasi dan Setup Lokal

1.  **Clone Repository (Jika Ada):**
    ```bash
    git clone [URL_REPOSITORY_ANDA] sisinpem
    cd sisinpem
    ```
    Jika tidak ada repository, Anda akan membuat proyek dari awal dengan `composer create-project laravel/laravel sisinpem "11.*"`.

2.  **Instal Dependensi PHP:**
    ```bash
    composer install
    ```

3.  **Buat File Environment:**
    Salin `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```

4.  **Generate Kunci Aplikasi:**
    ```bash
    php artisan key:generate
    ```

5.  **Konfigurasi Database di `.env`:**
    Sesuaikan variabel berikut dengan konfigurasi database lokal Anda:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=db_sisinpem # Ganti dengan nama database Anda
    DB_USERNAME=root      # Ganti dengan username database Anda
    DB_PASSWORD=          # Ganti dengan password database Anda

    APP_URL=http://localhost:8000 # Sesuaikan jika port berbeda saat development
    ```

6.  **Jalankan Migrasi dan Seeder:**
    Perintah ini akan membuat struktur tabel di database dan mengisi data awal (jika Anda sudah membuat seeder untuk peran, kategori default, atau user admin).
    ```bash
    php artisan migrate --seed
    ```

7.  **Instal Dependensi Frontend:**
    ```bash
    npm install
    # atau
    # yarn install
    ```

8.  **Compile Aset Frontend:**
    ```bash
    npm run dev
    # atau
    # yarn dev
    ```
    Untuk build produksi: `npm run build` atau `yarn build`.

9.  **Buat Symbolic Link untuk Storage:**
    Agar file yang diupload (seperti gambar item atau laporan kerusakan) bisa diakses publik:
    ```bash
    php artisan storage:link
    ```

10. **Jalankan Development Server:**
    ```bash
    php artisan serve
    ```
    Aplikasi akan tersedia di `http://localhost:8000` (atau port lain jika default sudah terpakai).

11. **(Opsional) Jalankan Queue Worker:**
    Jika notifikasi atau pekerjaan lain di-queue (`ShouldQueue`), jalankan worker:
    ```bash
    php artisan queue:work
    ```

## Penggunaan

Setelah setup selesai:
* Akses aplikasi melalui URL yang ditampilkan oleh `php artisan serve`.
* **Login Akun:** Akun pengguna (Admin dan Mahasiswa) dibuat secara manual melalui phpMyAdmin atau alat manajemen database lainnya. Tidak ada fitur registrasi publik.
    * **Contoh Akun Admin (jika dibuat via Seeder):**
        * Email: `admin@example.com`
        * Password: `password` (sesuaikan dengan yang Anda set di seeder)
    * **Contoh Akun Mahasiswa (jika dibuat via Seeder):**
        * Email: `mahasiswa@example.com`
        * Password: `password`

## Catatan Mengenai Pendaftaran Pengguna

Fitur pendaftaran pengguna publik sengaja tidak diaktifkan. Semua akun pengguna, baik untuk Admin maupun Mahasiswa, diharapkan untuk dibuat dan dikelola oleh administrator sistem langsung melalui database

## Ucapan Terima Kasih / Pengembang

Dibuat untuk tujuan pembelajaran oleh **Kelompok 2 MIC2023**.

---