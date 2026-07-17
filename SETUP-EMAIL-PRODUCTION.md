# Setup Email Notifikasi di Production

Panduan untuk mengaktifkan email notifikasi di hosting (Render, Infinityfree, dll).

## ⚠️ Masalah Umum

Email bekerja di lokal tetapi tidak terkirim di hosting? Kemungkinan penyebab:

1. **Environment variables tidak dikonfigurasi** — `.env` di hosting kosong atau tidak lengkap
2. **MAIL_PASSWORD tidak di-set** — perlu App Password Gmail yang di-generate khusus
3. ~~Queue worker tidak running~~ — ✅ SUDAH DIPERBAIKI (email sekarang dikirim langsung)

## 🚀 Langkah Setup di Render.com

### 1. Generate App Password Gmail

Ikuti dokumen di [SETUP-EMAIL-NOTIFIKASI.md](./SETUP-EMAIL-NOTIFIKASI.md) bagian **"Generate App Password"** untuk mendapatkan password 16 karakter.

### 2. Set Environment Variables di Render

1. Buka **Render Dashboard** → Pilih project Si Bakat Hebat
2. Klik **Environment** di sidebar
3. Tambahkan variables ini:

| Kunci | Nilai |
|-------|-------|
| `MAIL_MAILER` | `smtp` |
| `MAIL_SCHEME` | `tls` |
| `MAIL_HOST` | `smtp.gmail.com` |
| `MAIL_PORT` | `587` |
| `MAIL_USERNAME` | `sefinalika@gmail.com` |
| `MAIL_PASSWORD` | `[16 karakter app password]` |
| `MAIL_FROM_ADDRESS` | `sefinalika@gmail.com` |
| `MAIL_FROM_NAME` | `Si Bakat Hebat` |

4. Klik **Save**
5. Render akan auto-redeploy dengan konfigurasi baru

### 3. Verifikasi Email Berhasil

1. Login ke aplikasi di hosting
2. Cek email inbox Anda — seharusnya ada notifikasi login
3. Jika berhasil, email lain (hasil tes) juga akan terkirim

## 🌐 Setup di Platform Lain

### **Infinityfree**
1. Login ke Infinityfree Panel
2. Cari menu "Environment" atau "App Configuration"
3. Tambahkan variables MAIL seperti di atas

### **Heroku / Railway / Replit**
1. Cari menu "Config Variables" atau "Secrets"
2. Tambahkan variables MAIL dengan nilai yang sama

## 📋 Checklist Troubleshooting

Jika email masih tidak terkirim:

- [ ] Verifikasi `MAIL_PASSWORD` adalah **App Password** (16 karakter), bukan password akun Gmail
- [ ] Pastikan 2FA sudah aktif di Google Account
- [ ] Cek email masuk ke **Spam** atau **Promotions** folder
- [ ] Verifikasi `MAIL_USERNAME` dan `MAIL_FROM_ADDRESS` sama dengan email Gmail
- [ ] Pastikan aplikasi sudah di-redeploy setelah menambah variables
- [ ] Cek log di `/storage/logs/laravel.log` di production

## 💾 Perubahan di Kode

Saat ini, email hasil tes dikirim **langsung** (bukan melalui queue):

```php
// Sebelum:
Mail::to($email)->queue(new HasilTesMail($observasi));

// Sesudah:
Mail::to($email)->send(new HasilTesMail($observasi));
```

Keuntungan:
- ✅ Tidak perlu queue worker
- ✅ Lebih sederhana di hosting gratis
- ✅ Email terkirim langsung, tidak tertunda

## 🔒 Security Notes

- **JANGAN** commit `.env` dengan MAIL_PASSWORD ke Git
- `.env` sudah ada di `.gitignore`
- App Password bisa di-revoke kapan saja di [Google Account Security](https://myaccount.google.com/security)
- Jika password ter-expose, buat App Password baru dan update di hosting

## 📞 Support

Jika masih ada masalah:
1. Cek [SETUP-EMAIL-NOTIFIKASI.md](./SETUP-EMAIL-NOTIFIKASI.md) untuk troubleshooting lokal
2. Verifikasi log aplikasi di production
3. Cek status SMTP di Gmail `[settings.google.com](https://myaccount.google.com/security)`
