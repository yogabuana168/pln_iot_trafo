# Kustomisasi

Lihat referensi resmi: [FabKin Colors & SCSS](https://preview.pixeleyez.com/fabkin/docs/laravel/colors-default.html#!).

## SCSS
- Variabel global: `resources/assets/scss/_variables.scss`
- Mode gelap/terang: `_variables-dark.scss`, `_custom-variables.scss`
- Struktur SCSS:
  - `resources/assets/scss/custom/apps/**`
  - `resources/assets/scss/custom/components/**`
  - `resources/assets/scss/custom/dashboard/**`
  - `resources/assets/scss/custom/pages/**`
  - `resources/assets/scss/custom/plugins/**`
  - `resources/assets/scss/custom/structure/**` (header, sidebar, horizontal, semibox, dll.)

Setelah mengubah SCSS jalankan:
```
npm run dev   # atau npm run prod
```

## Javascript
- Script utama: `public/assets/js/app.js`
- Inisialisasi dashboard: `public/assets/js/dashboard/*.js`
- Komponen/Plugin: `public/assets/libs/**`

## Layouts & Modes
- Layout tersedia: Default, Horizontal, Semibox
- Mode: LTR/RTL, Light/Dark
- Include di Blade sudah disiapkan via `partials/head-css.blade.php` dan `partials/layouts/master.blade.php`.

## Ikon & Font
- Remixicon dan Bootstrap Icons dikelola via `webpack.mix.js` (copy fonts otomatis).

## Tips
- Hindari mengubah file di `public/assets/css/*` secara manual; lakukan via SCSS dan rebuild.
- Jika cache browser keras, lakukan hard refresh atau ganti versi file via query string.
