# Instalasi & Build

## Dependensi
- PHP 8.2
- Composer
- Node.js 18+ & npm

## Instalasi
1. Install PHP dependencies:
```
composer install --no-interaction --prefer-dist --optimize-autoloader
```
2. Install JS dependencies:
```
npm install
```

## Build Aset
- Mode development:
```
npm run dev
```
- Mode production (minified):
```
npm run prod
```

`webpack.mix.js` sudah dikonfigurasi untuk:
- Kompilasi SCSS ke `public/assets/css/{app,bootstrap,icons}.min.css`
- Copy `images`, `js`, `fonts`, `libs` dari `resources/assets` ke `public/assets`
- Generate file RTL otomatis

## BrowserSync (opsional)
Konfigurasi tersedia di `webpack.mix.js` (proxy ke `localhost:8000`).

## Referensi
- FabKin: [Installation & Build](https://preview.pixeleyez.com/fabkin/docs/laravel/index.html)
