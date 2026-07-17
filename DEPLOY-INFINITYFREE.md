# Panduan Deploy GRATIS ke InfinityFree (tanpa kartu)

InfinityFree = hosting PHP + MySQL **gratis selamanya, tanpa kartu kredit**.
Kekurangannya: **upload manual** (bukan dari GitHub) dan **tidak ada command line**
(migrasi dijalankan lewat browser). Ikuti langkah ini dengan sabar. 🙂

> ⚠️ **CEK PALING PENTING DULU (langkah 2):** aplikasi ini butuh **PHP 8.3**.
> Kalau InfinityFree Anda hanya menyediakan sampai PHP 8.2, aplikasi **tidak akan jalan** —
> beri tahu saya, nanti kita pilih cara lain.

---

## 1. Daftar & buat hosting
1. Daftar di https://infinityfree.com (gratis, tanpa kartu).
2. **Create Account** → pilih subdomain gratis (mis. `sibakathebat.rf.gd`) atau domain sendiri.
3. Tunggu akun aktif (beberapa menit), lalu buka **Control Panel** (VistaPanel).

## 2. Pastikan PHP 8.3  ⚠️
Di Control Panel → cari **PHP / Select PHP Version** → pilih **8.3**.
- Kalau **tidak ada 8.3** (maksimal 8.2) → **STOP**, kabari saya. App butuh 8.3.

## 3. Buat database MySQL
Control Panel → **MySQL Databases** → buat database baru. Catat:
- **Host** (mis. `sqlXXX.infinityfree.com`)
- **Database name** (mis. `if0_xxxx_sibakathebat`)
- **Username** (mis. `if0_xxxx`)
- **Password** (yang Anda buat)

## 4. Siapkan file di komputer (folder project)
Pastikan sudah ada (kita sudah lakukan): `vendor/` (dari `composer install`) dan
`public/build/` (dari `npm run build`).

**a) Buat file `.env`** (di root project) berisi — sesuaikan DB & domain.

> **Jangan pakai APP_KEY milik orang lain.** Buat kunci baru khusus untuk situs Anda:
> jalankan `php artisan key:generate --show` di komputer, salin hasilnya (`base64:...`)
> ke `APP_KEY` di bawah. Kunci ini mengenkripsi session & cookie — kalau bocor,
> orang lain bisa memalsukan sesi login.

```
APP_NAME="Si Bakat Hebat"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:GANTI-DENGAN-HASIL-key:generate
APP_URL=https://SUBDOMAIN-ANDA.rf.gd

DB_CONNECTION=mysql
DB_HOST=sqlXXX.infinityfree.com
DB_PORT=3306
DB_DATABASE=if0_xxxx_sibakathebat
DB_USERNAME=if0_xxxx
DB_PASSWORD=passwordDB-anda

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_CHANNEL=errorlog

MAIL_MAILER=log
WHATSAPP_NUMBER=082122945262

# Token untuk setup DB via browser. WAJIB minimal 32 karakter acak
# (kalau lebih pendek, route setup otomatis mati / 404).
# Buat dengan: php -r "echo bin2hex(random_bytes(24));"
# HAPUS baris ini segera setelah setup selesai.
APP_SETUP_TOKEN=
```

**b) Buat file `.htaccess`** yang akan diletakkan di **htdocs** (arahkan ke `public/`):
```apache
RewriteEngine On
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^(.*)$ /public/$1 [L]
```
(File ini akan menutup akses ke `.env` juga, jadi aman.)

## 5. Upload ke InfinityFree (pakai FTP — file-nya banyak)
1. Control Panel → **FTP Accounts** → lihat/atur **FTP host, username, password**.
2. Install **FileZilla** (gratis) → connect pakai data FTP itu.
3. Buka folder **`htdocs`** di server. **Upload SELURUH isi folder project** ke dalam `htdocs`
   (app, bootstrap, config, database, public, resources, routes, storage, vendor, artisan,
   composer.json, `.env`, dst). Ini agak lama karena `vendor/` banyak file — sabar ya.
4. Upload juga file **`.htaccess`** (dari langkah 4b) ke dalam **`htdocs`** (di luar folder public).

> Struktur akhir: `htdocs/.htaccess`, `htdocs/public/...`, `htdocs/app/...`, `htdocs/.env`, dst.

## 6. Jalankan migrasi + seed lewat browser
Buka di browser (ganti dengan `APP_SETUP_TOKEN` Anda — minimal 32 karakter acak):
```
https://SUBDOMAIN-ANDA.rf.gd/deploy/setup/TOKEN-ACAK-ANDA
```
Akan muncul log migrasi lalu **"=== SELESAI ==="**. Berarti database sudah terisi
(40 karakter, 76 soal, akun admin & guru).

## 7. Amankan kembali
Edit `.env` → **kosongkan** `APP_SETUP_TOKEN=` → upload ulang `.env`.
(Supaya route setup tidak bisa dipakai orang lain.)

## 8. Selesai — coba!
Buka `https://SUBDOMAIN-ANDA.rf.gd` → halaman login Si Bakat Hebat. **Bagikan link-nya.** ✅
- Login admin: **admin@gmail.com** — passwordnya diambil dari `ADMIN_PASSWORD` di `.env`
  (isi sebelum menjalankan setup; kalau kosong, seeder membuat password acak yang hanya
  ditampilkan sekali di halaman hasil setup).

---

## Kalau ada masalah
- **Error 500 / halaman putih** → cek PHP sudah 8.3, folder `storage/` & `bootstrap/cache/`
  ter-upload lengkap, dan `.env` benar. Kirim pesan errornya ke saya.
- **"could not find driver"** → pastikan DB_CONNECTION=mysql & PHP MySQL aktif (default aktif).
- **Setup route 404** → pastikan `APP_SETUP_TOKEN` di `.env` sama persis dengan di URL.
- **Login Google / email** butuh konfigurasi tambahan (opsional) — bilang kalau perlu.
