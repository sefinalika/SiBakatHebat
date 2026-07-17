# Panduan Deploy GRATIS ke Render (lewat GitHub)

Setelah selesai, aplikasi punya **link publik** — siapa pun yang membukanya bisa
**daftar, ikut tes, dan lihat hasil**.

> **Kode → GitHub → Render (menjalankan PHP + PostgreSQL) → Link publik.**
> GitHub hanya menyimpan kode; **Render** yang menjalankan aplikasinya (gratis).

File yang sudah disiapkan: `Dockerfile`, `render.yaml`, `.dockerignore`.
Aset frontend (`public/build`) sudah di-commit, jadi tak perlu build Node di server.

---

## 0. Siapkan akun
- **GitHub** (gratis) — https://github.com
- **Render** (gratis, login pakai GitHub) — https://render.com

---

## 1. Push kode ke GitHub
Di folder project, terminal:
```bash
git add .
git commit -m "Setup deploy Render"
git branch -M main
git remote add origin https://github.com/USERNAME/si-bakat-hebat.git
git push -u origin main
```
(Kalau `remote add` bilang sudah ada, lewati baris itu.)

---

## 2. Deploy via Blueprint (paling mudah — otomatis buat web + database)
1. Buka https://dashboard.render.com → **New +** → **Blueprint**.
2. Sambungkan/pilih repo **`si-bakat-hebat`**. Render membaca `render.yaml`.
3. Render menampilkan rencana: **1 Web Service** + **1 PostgreSQL** (gratis).
4. Anda akan diminta mengisi 2 variabel:
   - **APP_KEY** → lihat langkah 3.
   - **APP_URL** → isi sementara `https://si-bakat-hebat.onrender.com`
     (perbaiki di langkah 5 kalau URL final berbeda).

   Sangat disarankan menambah 2 variabel lagi (Environment → Add):
   - **ADMIN_PASSWORD** → password akun admin pertama (pilih yang kuat).
   - **GURU_PASSWORD** → password akun guru pertama.

   Kalau dikosongkan, seeder membuatkan password acak dan menampilkannya **sekali**
   di tab **Logs** saat deploy pertama.
5. Klik **Apply**. Render mulai build (beberapa menit pertama agak lama).

> Kalau tidak mau pakai Blueprint: buat **Web Service** (runtime **Docker**, dari repo)
> + **New PostgreSQL** manual, lalu isi variabel `DB_*` dari halaman database Render.

---

## 3. Buat APP_KEY
Di komputer (folder project):
```bash
php artisan key:generate --show
```
Salin hasilnya (`base64:...`) → tempel ke **APP_KEY** di Render.

---

## 4. Yang terjadi otomatis saat deploy
Container start akan otomatis:
- menjalankan **migrasi** (buat semua tabel di PostgreSQL), lalu
- **seeding** (40 karakter TB-40, 76 soal, akun admin & guru).

Cek tab **Logs** di Render bila ingin memastikan berhasil.

---

## 5. Cek & samakan URL
1. Setelah live, Render memberi URL (mis. `https://si-bakat-hebat.onrender.com`).
2. Pastikan variabel **APP_URL** = URL itu (Environment → edit → Save → auto re-deploy).
3. Buka URL → halaman login Si Bakat Hebat. **Bagikan link ini** ke orang lain. ✅

---

## 6. Login admin (bawaan)
- Nama Pengguna: **admin@gmail.com** — password = isi `ADMIN_PASSWORD`.
- Guru: **guru@gmail.com** — password = isi `GURU_PASSWORD`.

Kalau kedua variabel itu tidak diisi, buka tab **Logs** deploy pertama dan cari baris
`Akun admin dibuat — username: ... | password: ...`. Password acak itu hanya muncul sekali.

> Akun yang sudah ada **tidak pernah ditimpa** oleh deploy berikutnya, jadi password yang
> Anda ganti sendiri lewat database akan tetap bertahan.

---

## 7. Email hasil tes (WAJIB kalau ingin email benar-benar terkirim)
Selama `MAIL_MAILER` masih `log`, email hasil **hanya ditulis ke log** dan tidak pernah
sampai ke guru/wali murid. Isi variabel berikut di Render (Environment):

| Variabel | Contoh (Gmail) |
|---|---|
| `MAIL_MAILER` | `smtp` |
| `MAIL_HOST` | `smtp.gmail.com` |
| `MAIL_PORT` | `587` |
| `MAIL_SCHEME` | `tls` |
| `MAIL_USERNAME` | email Gmail Anda |
| `MAIL_PASSWORD` | **App Password** Google (bukan password akun) |
| `MAIL_FROM_ADDRESS` | `no-reply@domain-anda.com` |

Email dikirim lewat **antrean**: container menjalankan `queue:work` di latar belakang,
jadi peserta tidak menunggu render PDF + SMTP saat menekan submit. Kalau email gagal,
job-nya masuk tabel `failed_jobs` (bukan hilang diam-diam).

## 8. (Opsional) Login Google
Buat OAuth di https://console.cloud.google.com, redirect URI
`https://URL-ANDA/auth/google/callback`, lalu isi env `GOOGLE_CLIENT_ID`,
`GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`.

---

## Yang perlu Anda ketahui (jujur soal "gratis")
- **Web service gratis Render "tidur" saat tidak dipakai.** Pengunjung pertama setelah
  idle menunggu ~30–50 detik (cold start), berikutnya normal.
- **Database PostgreSQL gratis Render ada batas waktu** (cek ketentuan terbaru saat daftar).
  Untuk jangka panjang: upgrade database, atau pakai PostgreSQL gratis lain (mis. Neon/Supabase)
  lalu isikan `DB_*`-nya. Data tetap aman selama database aktif.
- Update aplikasi: cukup `git push` → Render auto-deploy. (Kalau ubah tampilan, jalankan
  `npm run build` lalu commit `public/build` sebelum push.)
