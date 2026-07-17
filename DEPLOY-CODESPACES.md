# Menjalankan Si Bakat Hebat lewat GitHub Codespaces (GRATIS, tanpa kartu)

Codespaces menjalankan aplikasi **langsung di GitHub** (cloud) dan memberi **link publik**
yang bisa dibuka & dicoba orang lain — cocok untuk **uji coba**. Gratis, tanpa kartu.

> ⚠️ Ini **bukan** hosting 24 jam. Codespace **berhenti otomatis ~30 menit** kalau tidak
> dipakai (untuk menghemat kuota gratis). Saat mau dipakai, tinggal dinyalakan lagi
> (data tetap tersimpan). Untuk uji coba / demo di kelas, ini sudah cukup.

---

## Langkah pakai

### 1. Buka Codespace
- Buka repo Anda: https://github.com/likahee-j/si-bakat-hebat
- Klik tombol hijau **Code** → tab **Codespaces** → **Create codespace on main**.
- Tunggu (± 2–4 menit pertama). Codespace otomatis: install dependency, siapkan
  database (SQLite), migrasi + seed. (Ada di `.devcontainer/`.)

### 2. Jalankan server
Setelah siap, di **Terminal** (bawah), ketik:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### 3. Jadikan link-nya publik
- Buka tab **PORTS** (di panel bawah, sebelah Terminal).
- Cari port **8000** → **klik kanan** → **Port Visibility** → **Public**.
- Salin **URL** di kolom "Forwarded Address" (bentuknya `https://xxxx-8000.app.github.dev`).

### 4. Bagikan!
Kirim URL itu ke siapa pun → mereka bisa **daftar & ikut tes** selama Codespace nyala. ✅
- Login admin: **admin@gmail.com** / **password**

---

## Menyalakan lagi nanti
Kalau Codespace sudah berhenti: buka repo → **Code → Codespaces** → klik codespace yang
ada (jangan buat baru) → tunggu aktif → jalankan lagi `php artisan serve ...` (langkah 2–3).

## Batasan gratis
- Kuota gratis Codespaces (akun personal): cukup untuk banyak sesi uji coba per bulan.
- Untuk website **online 24 jam permanen**, perlu hosting server sungguhan (butuh kartu
  atau upload manual). Kalau nanti butuh itu, ada `DEPLOY-RENDER.md` & `DEPLOY-INFINITYFREE.md`.
