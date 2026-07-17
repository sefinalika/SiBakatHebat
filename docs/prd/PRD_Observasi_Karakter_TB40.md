# Product Requirements Document
# Aplikasi Web Si Bakat Hebat (TB-40)

| | |
|---|---|
| **Versi** | 1.2.0 |
| **Tanggal** | 29 Juni 2026 |
| **Status** | Draft |
| **Stack** | Laravel 11 + MySQL + Chart.js + DomPDF + Laravel Socialite (Google) + Laravel Mail |

---

## Riwayat Perubahan

| Versi | Tanggal | Perubahan |
|---|---|---|
| 1.0.0 | 28 Juni 2026 | Draft awal |
| 1.1.0 | 29 Juni 2026 | Koreksi pengisi form (murid, bukan guru/wali); jumlah soal diperbarui dari 30 → 76; mapping soal ke karakter TB-40 dilengkapi; pertanyaan terbuka yang sudah terjawab dihapus |
| 1.2.0 | 29 Juni 2026 | Rebranding **Observasi Karakter → Si Bakat Hebat**, hapus label PKBM ADZKA; tambah **autentikasi** (login Google + daftar email/password); **hasil dikirim ke email** user; **form identitas diganti total** (email akun, jenis kelamin, tanggal lahir, nama sekolah, provinsi, kota dependent dropdown); tombol depan **tidak langsung** ke soal; **dashboard super-admin** (grafik 34 provinsi + data terbanyak); tautan WhatsApp di footer |

---

## Adendum v1.2.0 (Perubahan Terbaru — Otoritatif)

Bagian ini menggantikan/menambah ketentuan di bawahnya bila bertentangan.

### A. Branding
- Nama produk: **Si Bakat Hebat** (sebelumnya "Observasi Karakter TB-40").
- Label/teks **"PKBM ADZKA" dihapus** dari seluruh UI.

### B. Autentikasi & Akun
- Pengguna **wajib login** sebelum mengisi tes.
- Dua cara masuk:
  1. **Login dengan Google** (Laravel Socialite) — ambil nama, email, avatar.
  2. **Daftar akun baru** (email + password) lalu login.
- Data akun (`name`, `email`) tersimpan di tabel `users`. Email **tidak** diisi lagi di form identitas — diambil dari akun.
- Peran: `user` (peserta) dan `admin` (super admin). Kolom `role` di `users`.

### C. Alur Baru
```
[Landing Si Bakat Hebat]
        ↓  (tombol depan TIDAK langsung ke soal)
[Login / Daftar]  ← Google atau email+password
        ↓
[Form Identitas Peserta]  (lihat D)
        ↓  tombol "Simpan dan Mulai Test"
[76 Soal, 4 section]
        ↓
[Hasil lengkap]  → otomatis DIKIRIM ke email user + bisa unduh PDF
```

### D. Form Identitas (GANTI TOTAL)
Field lama (nama lengkap, nama panggilan, kelas, nama wali, tinggi/berat badan,
5 kondisi fisik) **dihapus**. Nama & email berasal dari akun. Form identitas kini:

| Urut | Field | Tipe | Wajib |
|---|---|---|---|
| 1 | Jenis Kelamin | Radio (laki-laki / perempuan) | ✅ |
| 2 | Tanggal Lahir | Date | ✅ |
| 3 | Nama Sekolah | Text | ✅ |
| 4 | Provinsi | Dropdown (34 provinsi) | ✅ |
| 5 | Kota / Kabupaten | Dropdown **dependent** (terfilter dari provinsi terpilih) | ✅ |

- Tombol aksi: **"Simpan dan Mulai Test"**.
- Catatan: pemilihan **provinsi** memfilter daftar **kota/kabupaten** pada dropdown kedua.

### E. Dashboard Super Admin
- Hanya untuk `role = admin`.
- Menampilkan **grafik 34 provinsi** beserta **data yang paling banyak digunakan**
  (mis. jumlah peserta per provinsi, top provinsi/kota).

### F. Email Hasil
- Setelah tes selesai, sistem **mengirim hasil tes ke email user yang login**
  (Laravel Mailable). Default mailer `log` saat pengembangan; SMTP diisi saat produksi.

### G. Footer
- Footer memuat **tautan WhatsApp** (kontak admin/sekolah).

### H. Perubahan Database
- **`users`**: tambah `role` ENUM('user','admin') default 'user', `google_id` (nullable), `avatar` (nullable). `password` nullable (akun Google bisa tanpa password).
- **`peserta`**: struktur ulang →
  `id`, `user_id` FK → users.id, `jenis_kelamin` ENUM('laki-laki','perempuan'),
  `tanggal_lahir` DATE, `nama_sekolah`, `provinsi`, `kota`, timestamps.
  (Kolom lama nama_lengkap/kelas/wali/tinggi/berat/kondisi fisik **dihapus**.)
- **`observasi`**: tetap (`peserta_id`, `tanggal`, metadata guru/TA/semester opsional).
- Tabel `jawaban`, `soal`, `karakter`, `hasil_observasi`: **tidak berubah**.
- Data wilayah (34 provinsi → kota) disediakan sebagai data statis untuk dropdown
  dependent & agregasi dashboard (provinsi/kota disimpan sebagai string di `peserta`).

---

## 1. Ringkasan Produk

### 1.1 Latar Belakang

PKBM ADZKA membutuhkan platform digital untuk menggantikan proses tes karakter peserta didik yang selama ini dilakukan secara manual via Google Forms. Platform ini memungkinkan **peserta didik mengisi sendiri** (self-report) penilaian karakternya secara online, lalu sistem mengotomatisasi perhitungan skor dan pembuatan laporan hasil berdasarkan metode **TB-40**.

Metode TB-40 merupakan metode tafsir bakat yang diselaraskan dengan 40 sifat mulia bersumber dari Al-Qur'an, As Sunnah, dan perkataan salaf. Metode ini menemukan bakat asli (karakter) seseorang meliputi 3 kinerja: **BAKAT**, **AKAL**, dan **HATI**.

### 1.2 Tujuan Produk

- Memudahkan peserta didik mengisi tes karakter TB-40 secara mandiri di website
- Mengotomatisasi perhitungan skor dari 76 pertanyaan skala 1–10
- Menghasilkan laporan visual yang informatif dan dapat dicetak
- Menyimpan data historis hasil tes peserta didik
- Mengurangi beban administratif guru dalam mengolah data

### 1.3 Pengguna Target

| Peran | Akses | Keterangan |
|---|---|---|
| Peserta Didik | Mengisi tes, melihat hasil sendiri | Pengguna utama — mengisi form secara mandiri |
| Guru / Admin PKBM | Melihat semua hasil, export laporan | Fase 2 — butuh login |
| Orang Tua / Wali | Melihat hasil anak | Fase 3 — butuh login |

### 1.4 Ruang Lingkup (Fase 1 / MVP)

**Termasuk:**
- Form identitas peserta didik (diisi sendiri oleh murid)
- 76 soal skala 1–10 dibagi 4 section
- Kalkulasi skor otomatis (40 karakter TB-40 + Aqidah + Ibadah + Gaya Belajar)
- Tampilan hasil lengkap (grafik + narasi)
- Export PDF laporan

**Tidak termasuk (fase berikutnya):**
- Login multi-user
- Dashboard admin/guru
- Riwayat observasi
- Notifikasi

---

## 2. Alur Pengguna (User Flow)

```
[Murid membuka website]
         ↓
[① Isi Identitas Diri]
         ↓
[② Kerjakan 76 Soal (skala 1–10, dibagi 4 section)]
         ↓
[③ Klik Selesai → Kalkulasi Otomatis]
         ↓
[④ Tampil Halaman Hasil Lengkap]
         ↓
[⑤ Cetak / Unduh PDF]
```

### 2.1 Tahap 1 — Pengisian Identitas

Form yang harus diisi murid sebelum mengakses soal:

| Field | Tipe | Wajib |
|---|---|---|
| Nama Lengkap Peserta Didik | Text | ✅ |
| Nama Panggilan | Text | ✅ |
| Nomor Induk | Text | ❌ |
| Kelas | Dropdown (Thufulah/SD/SMP/SMA) | ✅ |
| Nama Ayah / Wali Santri | Text | ✅ |
| Tinggi Badan (cm) | Number | ✅ |
| Berat Badan (kg) | Number | ✅ |
| Fungsi Pendengaran | Radio (Sangat Kurang–Sangat Baik) | ✅ |
| Fungsi Penglihatan | Radio (Sangat Kurang–Sangat Baik) | ✅ |
| Fungsi Perasa (kulit) | Radio (Sangat Kurang–Sangat Baik) | ✅ |
| Kondisi Gigi | Radio (Sangat Kurang–Sangat Baik) | ✅ |
| Kondisi Rambut | Radio (Sangat Kurang–Sangat Baik) | ✅ |

### 2.2 Tahap 2 — Pengerjaan Soal

- **Jumlah soal:** 76 pertanyaan dibagi 4 section
- **Format:** Skala linier 1–10
- **Label:** `Tidak Sesuai` (1) ←→ `Sesuai` (10)
- **UI:** Radio button / tombol angka per soal
- **Validasi:** Semua soal wajib dijawab sebelum submit
- **Progress bar:** Menampilkan persentase penyelesaian

**Pembagian 4 section soal:**

| Section | Soal | Jumlah | Kategori |
|---|---|---|---|
| 1 | 1–9 | 9 soal | Aqidah |
| 2 | 10–18 | 9 soal | Ibadah |
| 3 | 19–27 | 9 soal | Karakter Belajar |
| 4 | 28–76 | 49 soal | Karakter Bakat (9 umum + 40 karakter TB-40) |

### 2.3 Tahap 3 — Kalkulasi Otomatis

Setelah submit, sistem melakukan:
1. Hitung rata-rata soal 1–9 → nilai Aqidah
2. Hitung rata-rata soal 10–18 → nilai Ibadah
3. Hitung rata-rata soal 19–27 → nilai Karakter Belajar
4. Hitung rata-rata soal 28–36 → nilai Karakter Bakat umum
5. Ambil nilai soal 37–76 langsung → skor 40 karakter TB-40 (1 soal = 1 karakter)
6. Tentukan kategori warna per karakter
7. Urutkan Top 6 (kekuatan) dan Bottom 6 (kelemahan)
8. Hitung Gaya Belajar dari soal 19–27 (dikelompokkan per modalitas)
9. Hitung Bahasa Hati dari rata-rata kelompok karakter terkait
10. Tentukan potensi sifat tercela dari kekuatan & kelemahan tertinggi

### 2.4 Tahap 4 — Tampilan Hasil

Halaman hasil satu halaman lengkap yang dapat dicetak/disimpan sebagai PDF.

---

## 3. Spesifikasi Fitur

### 3.1 Mapping Soal → Karakter TB-40

Soal 37–76 masing-masing mengukur satu karakter TB-40 (urut abjad):

| Soal | Karakter | Nama Arab | Terjemahan |
|---|---|---|---|
| 37 | 'Adaalah | العَدَالَة | adil |
| 38 | Amaanah | الاَمَانَة | tanggung jawab |
| 39 | Anaah | الاَنَاة | tidak tergesa |
| 40 | 'Aziimah | العَزِيمَة | tekad |
| 41 | Basyaasyah | البَشَاشَة | berseri-seri |
| 42 | Dzakaa' | الذَّكَاء | cerdas |
| 43 | Fashaahah | الفَصَاحَة | fasih bicara |
| 44 | Firaasah | الفِرَاسَة | firasat |
| 45 | Ghairah | الغَيْرَة | cemburu |
| 46 | Hayaa' | الحَيَاء | malu |
| 47 | Hikmah | الحِكْمَة | hikmah |
| 48 | Hilm | الحِلْم | santun |
| 49 | Himmah | الهِمَّة | cita-cita tinggi |
| 50 | Husnuzhan | حُسْنُ الظَّن | prasangka baik |
| 51 | 'Iffah | العِفَّة | jaga diri |
| 52 | Ihsaan | الاِحْسَان | perfeksionis |
| 53 | Itsaar | الاِيْثَار | melayani |
| 54 | 'Izzah | العِزَّة | harga diri |
| 55 | Juud | الجُوْد | dermawan |
| 56 | Kitmaanus Sirr | كِتْمَانُ السِّرِّ | jaga rahasia |
| 57 | Mahabbah | المَحَبَّة | penuh cinta |
| 58 | Munaafasah | المُنَافَسَة | kompetitif |
| 59 | Muzaah | المُزَاح | humoris |
| 60 | Nashiihah | النَّصِيْحَة | nasehat |
| 61 | Nasyaath | النَّشَاط | semangat |
| 62 | Nubl | النُّبْل | cerdik |
| 63 | Nushrah | النُّصْرَة | menolong |
| 64 | Qanaa'ah | القَنَاعَة | sederhana |
| 65 | Rahmah | الرَّحْمَة | belas kasih |
| 66 | Rifq | الرِّفْق | lemah lembut |
| 67 | Satr | السَّتْر | menutup aib |
| 68 | Shabr | الصَّبْر | sabar |
| 69 | Shamt | الصَّمْت | pendiam |
| 70 | Shidq | الصِّدْق | jujur |
| 71 | Syajaa'ah | الشَّجَاعَة | berani |
| 72 | Ta'aawun | التَّعَاوُن | kerjasama |
| 73 | Tawaadhu' | التَّوَاضُع | rendah hati |
| 74 | Ulfah | الاُلْفَة | bersatu |
| 75 | Wafaa' | الوَفَاء | tepat janji |
| 76 | Waqaar | الوَقَار | wibawa |

### 3.2 Mapping Gaya Belajar (dari soal 19–27)

| Gaya Belajar | Soal | Rata-rata |
|---|---|---|
| As Sam'u (mendengar) | 19, 22, 25 | average(soal 19, 22, 25) |
| Al Fuad (bergerak) | 20, 23, 26 | average(soal 20, 23, 26) |
| Al Bashar (melihat) | 21, 24, 27 | average(soal 21, 24, 27) |

Urutan dari tertinggi ke terendah = urutan gaya belajar dominan.

### 3.3 Mapping Bahasa Hati (dari skor karakter TB-40)

| Bahasa Hati | Karakter yang dirata-rata |
|---|---|
| Perlindungan | Syajaa'ah, Ghairah, Munaafasah, Himmah, Juud |
| Pelayanan | Itsaar, Rahmah, Kitmaanus Sirr, Satr, Amaanah, Hilm, Shabr |
| Kebersamaan | Basyaasyah, Rifq, Muzaah, Mahabbah, Ta'aawun, Ulfah, Wafaa' |

### 3.4 Sistem Penilaian & Warna

| Warna | Label | Rentang Skor | Makna |
|---|---|---|---|
| ⬛ Hitam | Sangat Lemah | 1.0–2.9 | Karakter sangat belum berkembang |
| 🩶 Abu-abu | Lemah | 3.0–4.9 | Karakter belum berkembang |
| 🟩 Hijau | Sedang | 5.0–6.9 | Karakter cukup berkembang |
| 🟨 Kuning | Kuat | 7.0–8.9 | Karakter sudah berkembang baik |
| 🟥 Merah | Sangat Kuat | 9.0–10.0 | Karakter sangat berkembang |

### 3.5 Halaman Hasil — Bagian-bagian

#### A. Header
- Nama lengkap peserta didik, nama panggilan
- Nama ayah/wali, kelas, tanggal tes

#### B. Grafik Karakter (Visual Utama)
- Grafik batang per karakter TB-40
- Setiap batang diberi warna sesuai kategori skor
- Label: nama Arab + terjemahan Indonesia
- Dikelompokkan dalam 3 dimensi: Kinerja BAKAT, AKAL, HATI
- Legenda warna di bawah grafik

#### C. Bakat Kekuatan (Top 6)
Untuk setiap karakter kekuatan, tampilkan:
- Nama Arab + terjemahan + deskripsi singkat
- Profesi / peran yang sesuai
- Jurusan studi yang sesuai

#### D. Bakat Kelemahan (Bottom 6)
Untuk setiap karakter kelemahan, tampilkan:
- Nama Arab + terjemahan + deskripsi singkat kelemahan
- Profesi / peran yang tidak sesuai
- Jurusan studi yang tidak sesuai

#### E. Gaya Belajar — Kinerja Akal
- Urutan 3 modalitas: As Sam'u / Al Fuad / Al Bashar
- Deskripsi karakteristik tiap gaya belajar
- Rekomendasi lingkungan belajar yang nyaman

#### F. Bahasa Hati — Kinerja Hati
- Urutan 3 bahasa hati: Perlindungan / Pelayanan / Kebersamaan
- Deskripsi cara menyentuh hati peserta didik

#### G. Potensi Sifat Tercela & Solusi
- Dari bakat **terkuat**: potensi sifat tercela jika berlebihan + solusi
- Dari bakat **terlemah**: potensi sifat tercela jika diremehkan + solusi

#### H. Tafsir Bakat (Narasi TB-40)
- Penjelasan singkat metode TB-40
- Penjelasan 3 kinerja: BAKAT, AKAL, HATI

### 3.6 Export PDF
- Tombol **Cetak / Unduh PDF** di halaman hasil
- Format mengikuti tampilan laporan yang sudah ada
- Menggunakan package `barryvdh/laravel-dompdf`

---

## 4. Struktur Database (MySQL)

### Tabel: `peserta`
```sql
id                  BIGINT PRIMARY KEY AUTO_INCREMENT
nama_lengkap        VARCHAR(100)
nama_panggilan      VARCHAR(50)
nomor_induk         VARCHAR(30) NULL
kelas               ENUM('thufulah', 'sd', 'smp', 'sma')
nama_wali           VARCHAR(100)
tinggi_badan        SMALLINT
berat_badan         SMALLINT
fungsi_pendengaran  ENUM('sangat_kurang','kurang','cukup','baik','sangat_baik')
fungsi_penglihatan  ENUM('sangat_kurang','kurang','cukup','baik','sangat_baik')
fungsi_perasa       ENUM('sangat_kurang','kurang','cukup','baik','sangat_baik')
kondisi_gigi        ENUM('sangat_kurang','kurang','cukup','baik','sangat_baik')
kondisi_rambut      ENUM('sangat_kurang','kurang','cukup','baik','sangat_baik')
created_at          TIMESTAMP
```

### Tabel: `observasi`
```sql
id                  BIGINT PRIMARY KEY AUTO_INCREMENT
peserta_id          BIGINT FK → peserta.id
tanggal             DATE
created_at          TIMESTAMP
```

### Tabel: `jawaban`
```sql
id              BIGINT PRIMARY KEY AUTO_INCREMENT
observasi_id    BIGINT FK → observasi.id
nomor_soal      TINYINT UNSIGNED  -- 1-76
nilai           TINYINT UNSIGNED  -- 1-10
```

### Tabel: `karakter`
```sql
id              TINYINT PRIMARY KEY  -- 1-40
kode            VARCHAR(30)          -- contoh: 'adaalah'
nama_karakter   VARCHAR(50)          -- contoh: "'Adaalah"
nama_arab       VARCHAR(100)
terjemahan      VARCHAR(50)
label_diri      TEXT
definisi        TEXT
dimensi         ENUM('bakat','akal','hati')
tipe            ENUM('introvert','extrovert')
profesi         TEXT
jurusan         TEXT
sifat_tercela_melalaikan      VARCHAR(100)
cara_memperbaiki_melalaikan   TEXT
sifat_tercela_berlebihan      VARCHAR(100)
cara_memperbaiki_berlebihan   TEXT
nomor_soal      TINYINT UNSIGNED     -- soal ke berapa (37-76)
urut_abjad      TINYINT UNSIGNED     -- urutan abjad (1-40)
urut_grafik     TINYINT UNSIGNED     -- urutan tampil di grafik
```

### Tabel: `hasil_observasi`
```sql
id              BIGINT PRIMARY KEY AUTO_INCREMENT
observasi_id    BIGINT FK → observasi.id
karakter_id     TINYINT FK → karakter.id
skor            DECIMAL(4,2)
kategori        ENUM('hitam','abu','hijau','kuning','merah')
```

---

## 5. Struktur Laravel (Rekomendasi)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── ObservasiController.php   ← form identitas & soal
│   │   └── HasilController.php       ← tampil hasil & PDF
│   └── Requests/
│       ├── IdentitasRequest.php
│       └── JawabanRequest.php
├── Models/
│   ├── Peserta.php
│   ├── Observasi.php
│   ├── Jawaban.php
│   ├── Karakter.php
│   └── HasilObservasi.php
└── Services/
    └── KalkulasiTB40Service.php      ← logika hitung skor & warna
database/
└── seeders/
    └── KarakterSeeder.php
resources/
└── views/
    ├── identitas.blade.php
    ├── soal.blade.php
    └── hasil.blade.php               ← juga dipakai untuk PDF
routes/
└── web.php
```

### Route Utama

```php
Route::get('/', [ObservasiController::class, 'identitas']);
Route::post('/identitas', [ObservasiController::class, 'simpanIdentitas']);
Route::get('/soal', [ObservasiController::class, 'soal']);
Route::post('/soal', [ObservasiController::class, 'simpanJawaban']);
Route::get('/hasil/{observasi}', [HasilController::class, 'tampil']);
Route::get('/hasil/{observasi}/pdf', [HasilController::class, 'exportPdf']);
Route::get('/api/hasil/{observasi}', [HasilController::class, 'apiHasil']);
```

---

## 6. Stack Teknologi

| Layer | Teknologi | Alasan |
|---|---|---|
| Backend | Laravel 11 | Ekosistem lengkap, mudah dikembangkan |
| Database | MySQL | Stabil, pasangan standar Laravel |
| Frontend | Blade + Alpine.js | Simpel, tidak perlu build step |
| Grafik | Chart.js | Grafik batang berwarna, ringan |
| PDF | barryvdh/laravel-dompdf | Integrasi native Laravel |
| Hosting | Shared hosting (cPanel) | Murah, Laravel + MySQL sudah tersedia |

---

## 7. Rencana Pengembangan (Milestone)

| Fase | Nama | Cakupan |
|---|---|---|
| **Fase 1** | MVP | Form identitas + 76 soal (self-report murid) + kalkulasi otomatis + tampilan hasil + export PDF |
| **Fase 2** | Data Management | Simpan data, riwayat tes, dashboard admin/guru |
| **Fase 3** | Multi-user | Login guru & wali, manajemen kelas, laporan kolektif |
| **Fase 4** | Analytics | Tren perkembangan karakter, perbandingan antar peserta |

---

## 8. Pertanyaan Terbuka ⚠️

Hal-hal berikut perlu dikonfirmasi **sebelum mulai coding:**

1. **Apakah murid bisa mengulang tes?** — Apakah satu murid bisa mengisi lebih dari satu kali, atau dibatasi hanya sekali per periode?
2. **Siapa yang mencetak PDF?** — Murid langsung setelah selesai tes, atau guru yang mencetak dari dashboard?
3. **Validasi identitas** — Apakah perlu verifikasi nomor induk sebelum mulai tes, agar data tidak diisi sembarangan?

---

*PKBM ADZKA • Observasi Karakter TB-40 • v1.1.0 • Dokumen ini bersifat konfidensial*