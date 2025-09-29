# Deployment

## Build Production
```
npm run prod
```

## Upload/Sync
- Pastikan `public/assets/**` berisi hasil build terbaru.
- Sinkronkan file proyek kecuali `node_modules` (tidak diperlukan di server runtime).

## Konfigurasi Web Server
- Document root: `public/`
- `public/index.php` sudah mengarah ke `../vendor/autoload.php` dan `../bootstrap/app.php`.

## Cache
```
php artisan config:clear && php artisan config:cache
php artisan view:clear && php artisan route:clear
```

## Troubleshooting
- Cek `storage/logs/laravel.log` untuk error runtime.
- Jika view/layout tidak ditemukan di Linux, cek case-sensitive folder `partials/layouts`.
- Pastikan `APP_URL` benar dan assets termuat dari `public/assets/*`.

## Referensi
- FabKin Deployment Notes: [Docs](https://preview.pixeleyez.com/fabkin/docs/laravel/index.html)
