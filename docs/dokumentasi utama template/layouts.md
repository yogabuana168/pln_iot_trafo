# Layouts & Modes

Template menyediakan beberapa layout dan mode tampilan.

## Layouts
- Default (Sidebar kiri)
- Horizontal
- Semibox (kombinasi container + sidebar)

Pengaturan layout diatur melalui partials:
- `resources/views/partials/layouts/master.blade.php`
- `resources/views/partials/header.blade.php`
- `resources/views/partials/sidebar.blade.php`
- `resources/views/partials/horizontal.blade.php`

## Modes
- LTR/RTL: CSS pasangan dihasilkan otomatis (`app.min.css` dan `app-rtl.min.css`).
- Light/Dark: Variabel di SCSS mengatur skema warna.

## Page Title & Komponen Umum
- `partials/page-title.blade.php`, `partials/footer.blade.php`, `partials/switcher.blade.php`

## Rujukan
- FabKin Layouts: [Docs](https://preview.pixeleyez.com/fabkin/docs/laravel/index.html)
