# Konfigurasi Environment

## Variabel Utama
- `APP_ENV=production`
- `APP_URL=https://pln.gpscenter.id`
- `APP_DEBUG=true` (sesuaikan di production)

## Session/Cache/Queue (tanpa DB)
- `SESSION_DRIVER=file`
- `CACHE_STORE=file`
- `QUEUE_CONNECTION=sync`

## Database (opsional)
- `DB_CONNECTION=mysql`
- `DB_HOST=arezzo-db.id.domainesia.com`
- `DB_PORT=3306`
- `DB_DATABASE=gpscente_fcopln`
- `DB_USERNAME=gpscente_yogabuana`
- `DB_PASSWORD=********`

## Commands
- Clear & cache config:
```
php artisan config:clear && php artisan config:cache
```
- Clear view/route cache:
```
php artisan view:clear && php artisan route:clear
```
