# Aset & Ikon

## CSS yang di-include
`resources/views/partials/head-css.blade.php` memuat:
- `assets/libs/simplebar/simplebar.min.css`
- `assets/libs/swiper/swiper-bundle.min.css`
- `assets/libs/nouislider/nouislider.min.css`
- `assets/css/bootstrap.min.css`
- `assets/css/icons.min.css`
- `assets/css/app.min.css`

## Struktur Aset Publik
- `public/assets/css/*`
- `public/assets/js/*`
- `public/assets/images/*`
- `public/assets/fonts/*`
- `public/assets/libs/*`

## Build & Copy
- Diatur oleh `webpack.mix.js` untuk kompilasi SCSS dan penyalinan assets dari `resources/assets/**`.

## Referensi
- FabKin Docs: [Assets](https://preview.pixeleyez.com/fabkin/docs/laravel/index.html)
