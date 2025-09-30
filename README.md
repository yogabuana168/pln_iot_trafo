# PLN Admin (Laravel + dashboardLTE)

Template admin berbasis Laravel yang digunakan pada subdomain `pln.gpscenter.id`, dikustomisasi dari FabKin (Bootstrap 5, Remix Icon & Bootstrap Icons).

## Spesifikasi
- Laravel 12, PHP 8.2
- Build aset: Laravel Mix (Webpack 5)
- Aset publik: `public/assets/**`
- SCSS sumber: `resources/assets/scss/**`

## Instalasi Cepat
```bash
# clone (jika dari GitHub)
git clone https://github.com/yogabuana168/pln_iot_trafo.git
cd pln_iot_trafo

# install dependencies
composer install
npm ci || npm install

# salin & sesuaikan environment
cp .env.example .env
php artisan key:generate
# set DB/APP_URL sesuai server

# build aset
npm run dev    # atau npm run prod
```

## Routing Inti
- Autentikasi sederhana (login/logout) dan redirect ke `/dashboard` setelah login.
- `routes/web.php` minimal: login, logout, dashboard (auth middleware), root redirect ke login.

## Manajemen Menu Global (Single Source of Truth)
- Sumber menu: `resources/views/partials/menu-items.blade.php`
- Dipakai oleh:
  - Vertical & Semibox: `resources/views/partials/sidebar.blade.php`
  - Horizontal: `resources/views/partials/horizontal.blade.php` (pakai prefix ID `h-`)
- Tambah item menu cukup edit `menu-items.blade.php`:
```html
<li class="pe-slide">
  <a href="/fitur-baru" class="pe-nav-link">
    <i class="bi bi-grid pe-nav-icon"></i>
    <span class="pe-nav-content">Fitur Baru</span>
  </a>
</li>
```
- Tema Light/Dark: biarkan warna mengikuti kelas bawaan (`pe-nav-link`, `pe-menu-title`), hindari inline color.

Detail lengkap: `docs/dokumentasi utama template/menu-management.md`.

## Header
- Dropdown demo (About, Authentication & Pages, Help), Cart, dan Language dihapus.
- Notifikasi dikembalikan dengan dropdown kosong (siap diisi dinamis).

## Dokumentasi Proyek
Dokumen teknis berada di folder: `docs/dokumentasi utama template/`.
- Index: `index.md`
- Instalasi & Build: `installation-build.md`
- Kustomisasi (SCSS/JS/Sidebar): `customization.md`
- Layouts (Default/Horizontal/Semibox): `layouts.md`
- Manajemen Menu Global: `menu-management.md`
- Aset & Ikon: `assets.md`
- Environment & Deployment: `environment.md`, `deployment.md`
- Troubleshooting: `troubleshooting.md`

## Deployment Ringkas
- Pastikan vhost mengarah ke `public/` dan `.htaccess` root me-redirect ke `public/` bila diperlukan.
- Produksi aset: `npm run prod`.
- Cache optimasi opsional:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Lisensi
Kustomisasi proyek ini mengikuti lisensi template dan dependensi terkait. Laravel berlisensi [MIT](https://opensource.org/licenses/MIT).
