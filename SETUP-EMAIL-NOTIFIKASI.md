# Setup Email Notifikasi Login

Panduan lengkap untuk mengaktifkan notifikasi email login menggunakan Gmail SMTP.

## 📋 Langkah-Langkah Setup

### 1. **Aktifkan 2-Factor Authentication di Google Account**

1. Buka [Google Account Security](https://myaccount.google.com/security)
2. Login dengan akun Gmail Anda: `sefinalika@gmail.com`
3. Cari **"2-Step Verification"** di bagian **"How you sign in to Google"**
4. Klik **"2-Step Verification"** dan ikuti instruksi
5. Verifikasi nomor HP/metode lainnya

> **Catatan:** Fitur App Passwords HANYA tersedia jika 2FA sudah diaktifkan.

### 2. **Generate App Password**

1. Setelah 2FA aktif, buka [Google App Passwords](https://myaccount.google.com/apppasswords)
2. Pilih:
   - **Select app:** Mail
   - **Select device:** Windows PC (atau device yang digunakan)
3. Klik **Generate**
4. Google akan menampilkan password 16 karakter, contoh:
   ```
   abcd efgh ijkl mnop
   ```
   **Salin password ini tanpa spasi:** `abcdefghijklmnop`

### 3. **Konfigurasi .env**

Buka file `.env` di root project dan isi konfigurasi email:

```env
MAIL_MAILER=smtp
MAIL_SCHEME=tls
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=sefinalika@gmail.com
MAIL_PASSWORD=abcdefghijklmnop
MAIL_FROM_ADDRESS=sefinalika@gmail.com
MAIL_FROM_NAME="Si Bakat Hebat"
```

**Penjelasan:**
- `MAIL_USERNAME` = Email Gmail Anda
- `MAIL_PASSWORD` = App Password (16 karakter yang di-generate tadi, **BUKAN password akun Gmail**)
- `MAIL_FROM_ADDRESS` = Email yang dipakai sebagai pengirim
- `MAIL_FROM_NAME` = Nama yang muncul sebagai pengirim email

### 4. **Test Email Lokal**

Buka terminal di project folder dan jalankan:

```bash
php artisan tinker
```

Kemudian ketik:

```php
Mail::raw('Test email notifikasi', function ($m) {
    $m->to('tujuan@gmail.com')->subject('Test');
});
```

Cek inbox email Anda. Jika email diterima, setup berhasil! ✅

Ketik `exit` untuk keluar dari Tinker.

### 5. **Testing di App**

1. Jalankan server lokal:
   ```bash
   php artisan serve
   ```

2. Buka login page: `http://localhost:8000/login`

3. Login dengan username/password atau Google OAuth

4. Cek inbox email Anda — seharusnya ada email notifikasi login dengan:
   - Waktu login
   - Alamat IP
   - Instruksi keamanan

## 🚀 Production Setup

Saat deploy ke production (misal Render), konfigurasi MAIL di environment production:

1. **Di Render Dashboard:**
   - Buka project settings
   - Tambah environment variables:
     ```
     MAIL_MAILER=smtp
     MAIL_HOST=smtp.gmail.com
     MAIL_PORT=587
     MAIL_USERNAME=sefinalika@gmail.com
     MAIL_PASSWORD=abcdefghijklmnop
     MAIL_FROM_ADDRESS=sefinalika@gmail.com
     MAIL_FROM_NAME=Si Bakat Hebat
     ```

2. **Deploy ulang** aplikasi

## ⚠️ Security Notes

- **JANGAN** commit `.env` yang berisi password ke Git
- `.env` sudah ada di `.gitignore` — pastikan tidak ter-commit
- App Password berbeda dengan password akun Gmail — lebih aman
- Jika password ter-expose, hapus App Password di Google Account Security dan generate yang baru

## 📧 Fitur Email Notifikasi

Email notifikasi dikirim secara otomatis saat:
- ✅ User login dengan username/password
- ✅ User login dengan Google OAuth
- ✅ Email berisi waktu login dan alamat IP untuk keamanan

## ❌ Troubleshooting

### Email tidak terkirim di lokal

**Error:** "SMTP connection refused" atau "Authentication failed"

**Solusi:**
1. Verifikasi `MAIL_USERNAME` dan `MAIL_PASSWORD` benar
2. Pastikan 2FA sudah aktif di Google Account
3. Pastikan menggunakan **App Password**, bukan password akun
4. Cek firewall tidak memblokir port 587

### Email masuk ke Spam

**Solusi:**
1. Tandai email sebagai "Not Spam"
2. Tambahkan email pengirim ke kontak
3. Setup SPF/DKIM records di domain hosting (untuk production)

### "Less secure app access" error

**Solusi:** Gunakan App Password (bukan password akun). Setup dengan App Password sudah tidak bergantung pada "Less secure app access".

## 📞 Support

Jika ada masalah:
1. Cek log email di `storage/logs/laravel.log`
2. Pastikan internet connection stabil
3. Verifikasi credentials di `.env`
