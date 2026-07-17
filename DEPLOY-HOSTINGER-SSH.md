# Deploy ke Hostinger dengan SSH & Git Pull

Panduan update Si Bakat Hebat ke hosting Hostinger menggunakan SSH terminal dan `git pull`.

## ✅ Keuntungan Metode Ini

- ⚡ **Tercepat** — hanya file yang berubah yang di-update
- 🔒 **Teraman** — bisa rollback instan kalau ada error
- 📉 **Downtime minimal** — ~1 menit atau bahkan tidak terasa
- 🎯 **Mudah** — tinggal `git pull` dan `composer install`

---

## 🔧 SETUP PERTAMA KALI (1x saja)

### 1. Akses SSH ke Hostinger

1. **Buka Hostinger Panel** → pilih hosting Anda
2. Cari menu **"Account"** atau **"SSH Access"** atau **"Terminal"**
3. Klik **"Manage"** atau **"Enable SSH"** (jika belum aktif)
4. Catat credentials:
   - **Host:** `hostinger-ssh.com` atau IP address
   - **Username:** username hosting Anda
   - **Password:** password hosting Anda (atau SSH key)

### 2. Connect ke SSH

**Windows (Powershell / Command Prompt):**
```powershell
ssh username@hostinger-ssh.com
# Atau gunakan PuTTY / Windows Terminal
```

**Mac/Linux:**
```bash
ssh username@hostinger-ssh.com
```

Masukkan password jika diminta.

### 3. Clone Repository ke Server

Setelah login SSH, masuk ke folder public_html:

```bash
cd ~/public_html
# atau
cd public_html
```

Clone repository (gunakan HTTPS, bukan SSH):

```bash
git clone https://github.com/sefinalika/sibakathebat1.git .
```

> ⚠️ **Hati-hati tanda `.` di akhir** — artinya clone ke folder sekarang (jangan buat subfolder baru).

### 4. Setup Environment & Dependencies

```bash
# Copy file .env.example menjadi .env
cp .env.example .env

# Generate APP_KEY
php artisan key:generate

# Install PHP dependencies
composer install --no-dev

# Build frontend assets
npm install
npm run build
```

### 5. Setup Database & Migration

```bash
# Jalankan migrations
php artisan migrate --force

# Seed database (opsional, kalau perlu data awal)
php artisan db:seed
```

### 6. Set Permissions (penting!)

```bash
# Set folder permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data .
```

> **Catatan:** Tanya Hostinger support jika perintah `chown` error — setiap hosting berbeda.

### 7. Konfigurasi .env

Edit file `.env` dengan SFTP/File Manager:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

DB_CONNECTION=mysql
DB_HOST=localhost (atau yang disediakan Hostinger)
DB_PORT=3306
DB_DATABASE=nama_db_anda
DB_USERNAME=user_db_anda
DB_PASSWORD=password_db_anda

MAIL_MAILER=smtp
MAIL_SCHEME=tls
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=sefinalika@gmail.com
MAIL_PASSWORD=[App Password Gmail 16 karakter]
MAIL_FROM_ADDRESS=sefinalika@gmail.com
MAIL_FROM_NAME=Si Bakat Hebat

WHATSAPP_NUMBER=082122945262
```

Setup selesai! ✅

---

## 📤 UPDATE KE HOSTINGER (setiap kali ada perubahan)

### Langkah 1: Push ke GitHub

Di komputer lokal:

```bash
git add .
git commit -m "Update Si Bakat Hebat - [deskripsi perubahan]"
git push origin main
```

### Langkah 2: SSH ke Server & Git Pull

Di SSH terminal Hostinger:

```bash
cd ~/public_html

# Pull update terbaru dari GitHub
git pull origin main

# Install dependency PHP baru (jika ada)
composer install --no-dev

# Build frontend jika ada perubahan CSS/JS
npm run build

# Run migrations jika ada database changes
php artisan migrate --force

# Clear cache Laravel
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Langkah 3: Verifikasi

Buka website Anda di browser — seharusnya sudah ter-update! 🎉

---

## ⚠️ HAL PENTING

### `.env` File

**Jangan** commit `.env` ke GitHub! File ini berisi credentials sensitive.

Kalau Anda sudah push `.env` ke GitHub:
```bash
git rm --cached .env
git commit -m "Remove .env from tracking"
git push
```

Kemudian di server, `.env` tetap aman (tidak akan terhapus saat `git pull`).

### Database Migrations

Jika Anda buat migration baru, jalankan di server:

```bash
php artisan migrate --force
```

Flag `--force` diperlukan di production agar tidak ada prompt.

### Rollback Kalau Error

Jika sesuatu error setelah `git pull`, bisa rollback instant:

```bash
git revert HEAD~1    # Batalkan commit terakhir
git pull origin main
php artisan cache:clear
```

---

## 🔄 Workflow Lengkap

### **Di Komputer Lokal:**
1. Edit file / buat feature
2. Test di lokal (`php artisan serve`)
3. Commit & push ke GitHub

### **Di Server Hostinger (SSH):**
1. `git pull` — ambil update dari GitHub
2. `composer install --no-dev` — install library PHP
3. `npm run build` — build CSS/JS
4. `php artisan migrate --force` — update database
5. `php artisan cache:clear` — refresh cache
6. Buka website → verifikasi berhasil

Total waktu: **2-5 menit**, downtime minimal! ⚡

---

## 📋 Troubleshooting

### Error: "Fatal: not a git repository"

**Solusi:** Repository belum di-clone. Ulangi langkah SETUP #3.

```bash
cd ~/public_html
git clone https://github.com/sefinalika/sibakathebat1.git .
```

### Error: "Permission denied" saat `git pull`

**Solusi 1:** Gunakan HTTPS (bukan SSH key):
```bash
git remote set-url origin https://github.com/sefinalika/sibakathebat1.git
git pull origin main
```

**Solusi 2:** Hubungi Hostinger support untuk permission issues.

### Error: "SQLSTATE[HY000]: General error"

**Solusi:** Database tidak ter-sync. Jalankan:
```bash
php artisan migrate --force
php artisan cache:clear
```

### Website masih error setelah update

**Debug:**
```bash
# Lihat error log
tail -100 storage/logs/laravel.log

# Clear semua cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

---

## 💡 Tips

- **Selalu test di lokal dulu** sebelum push ke GitHub
- **Buat commit message yang jelas** — memudahkan rollback
- **Check `git log`** di server untuk melihat history update
- **Backup database** sebelum migration besar
- **Buat tag di GitHub** untuk version penting: `git tag -a v1.0 -m "Release v1.0"`

---

## 📞 Support

Jika ada masalah:

1. **Cek error log:**
   ```bash
   tail -50 storage/logs/laravel.log
   ```

2. **Lihat git status:**
   ```bash
   git status
   git log --oneline -10
   ```

3. **Hubungi Hostinger support** untuk SSH/permission issues

4. **Hubungi tim development** untuk application bugs
