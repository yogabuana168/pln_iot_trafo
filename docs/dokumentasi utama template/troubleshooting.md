# Troubleshooting

## 1) View tidak ditemukan: `partials.layouts.master`
- Pastikan folder `resources/views/partials/layouts/` (bukan `Layouts/`).
- Bersihkan cache view:
```
php artisan view:clear
```

## 2) Autoload salah: mengarah ke `../laravel_pln/*`
- Pastikan `public/index.php` menggunakan:
```
require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../bootstrap/app.php';
```

## 3) Error DB: Access denied atau meminta root
- Set agar tidak bergantung DB untuk session/cache/queue:
```
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```
- Jika ingin konek DB, set `.env` sesuai kredensial dan recache config.

## 4) Cache/CDN menahan CSS lama
- Hard refresh browser atau bump versi query string.

## 5) Assets tidak termuat
- Pastikan build telah dibuat (`npm run prod`) dan file ada di `public/assets/css/*`.
- Cek `partials/head-css.blade.php` memuat file yang benar.
