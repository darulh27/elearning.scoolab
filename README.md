# E-Learning Sederhana (PHP + MySQL + AI)

Kerangka dasar project e-learning dengan login, materi, kuis, dan chatbot AI.

## Struktur Folder
```
elearning/
├── index.php              -> Halaman login
├── register.php           -> Halaman daftar akun
├── logout.php              -> Proses logout
├── dashboard.php           -> Halaman utama setelah login
├── materi.php               -> Daftar materi + detail materi + chat AI
├── kuis.php                  -> Halaman kuis & penilaian otomatis
├── config/
│   └── database.php          -> Koneksi ke MySQL
├── includes/
│   ├── session.php            -> Cek status login
│   ├── header.php              -> Navbar (dipakai berulang)
│   ├── footer.php               -> Footer (dipakai berulang)
│   └── ai_chat.php                -> Endpoint yang manggil API Claude
├── css/
│   └── style.css               -> Semua styling
├── js/
│   └── ai-chat.js                -> Logic chat AI di frontend
└── sql/
    └── elearning.sql              -> Struktur database (import ini duluan)
```

## Cara Menjalankan (pakai XAMPP)

1. **Install XAMPP** dari https://www.apachefriends.org (kalau belum ada)
2. **Copy folder `elearning`** ini ke folder `htdocs` di XAMPP
   (biasanya di `C:\xampp\htdocs\elearning`)
3. **Buka XAMPP Control Panel**, nyalakan **Apache** dan **MySQL**
4. **Buat database:**
   - Buka browser, akses `http://localhost/phpmyadmin`
   - Klik tab **Import**
   - Pilih file `sql/elearning.sql`
   - Klik **Go**
5. **Buka web-nya:**
   - Akses `http://localhost/elearning/` di browser
   - Daftar akun baru lewat halaman **Register**, lalu login

## Setup Fitur AI (Chatbot)

1. Buka file `includes/ai_chat.php`
2. Ganti baris ini dengan API key Claude kamu:
   ```php
   $api_key = "MASUKKAN_API_KEY_KAMU_DI_SINI";
   ```
3. Dapatkan API key dari https://console.anthropic.com
4. Fitur chat AI akan muncul otomatis di halaman detail materi

⚠️ **Penting:** Jangan taruh API key langsung di kode JavaScript (frontend),
karena bisa dilihat semua orang lewat "Inspect Element". API key harus selalu
dipanggil dari backend (PHP), seperti contoh di `ai_chat.php`.

## Menambah Materi & Soal Kuis

Untuk sekarang, tambah data lewat **phpMyAdmin** secara manual:
1. Buka `http://localhost/phpmyadmin`
2. Pilih database `elearning`
3. Buka tabel `materi` → klik **Insert** untuk tambah materi baru
4. Buka tabel `kuis` → klik **Insert** untuk tambah soal (pastikan `materi_id`
   sesuai dengan id materi yang sudah dibuat)

(Ke depannya, ini bisa dikembangkan jadi halaman admin biar nggak perlu
lewat phpMyAdmin terus)

## Rencana Pengembangan Selanjutnya
- [ ] Halaman admin untuk kelola materi & soal lewat UI (bukan phpMyAdmin)
- [ ] Upload file materi (PDF/video)
- [ ] Fitur auto-grading untuk jawaban essay pakai AI
- [ ] Halaman riwayat nilai per siswa
- [ ] Validasi & keamanan tambahan (rate limiting chat AI, dll)
