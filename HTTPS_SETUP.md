# SETUP HTTPS UNTUK LIVE STREAMING CBT

## Langkah-langkah untuk mengaktifkan HTTPS di Laravel Herd:

### 1. Install SSL Certificate
```bash
# Buka terminal dan jalankan:
herd secure cbt-test
```

### 2. Verifikasi HTTPS
- Buka browser dan akses: https://cbt-test.test
- Pastikan muncul ikon gembok hijau di address bar

### 3. Izinkan Camera Permission
- Di browser, klik ikon gembok/camera di address bar
- Pilih "Allow" untuk camera dan microphone access
- Refresh halaman

### 4. Test Live Streaming
- Login sebagai admin
- Buka: https://cbt-test.test/admin/exam/live-stream
- Login sebagai siswa di tab berbeda
- Mulai ujian untuk melihat camera muncul di live stream

## Troubleshooting:

### Jika kamera tidak muncul:
1. Pastikan menggunakan HTTPS (bukan HTTP)
2. Allow camera permission di browser
3. Restart browser setelah mengaktifkan HTTPS
4. Cek browser console untuk error messages

### Jika Laravel Herd belum installed:
```bash
# Install Laravel Herd untuk Windows
# Download dari: https://herd.laravel.com/windows
# Atau gunakan command:
winget install Laravel.Herd
```

### Browser Support:
- Chrome/Edge: ✅ Full support
- Firefox: ✅ Full support
- Safari: ✅ Full support
- Internet Explorer: ❌ Not supported

## Perintah Herd yang berguna:
```bash
# Lihat semua sites
herd sites

# Secure site dengan SSL
herd secure site-name

# Unsecure site
herd unsecure site-name

# Restart Herd
herd restart
```
