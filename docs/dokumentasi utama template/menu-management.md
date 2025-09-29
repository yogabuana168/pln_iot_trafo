# Manajemen Menu Global

Dokumen ini menjelaskan cara menambah/mengubah menu agar otomatis berlaku untuk semua layout (Vertical, Horizontal, dan Semibox).

## Ringkasan Implementasi
- Sumber menu tunggal: `resources/views/partials/menu-items.blade.php`
- Dipakai oleh:
  - Vertical & Semibox: `resources/views/partials/sidebar.blade.php`
  - Horizontal: `resources/views/partials/horizontal.blade.php`
- Prefix ID collapse horizontal: `@php($menuIdPrefix = 'h-')` untuk menghindari tabrakan ID antar layout.

Dengan desain ini, mengedit satu file `menu-items.blade.php` akan memperbarui semua layout sekaligus.

## Struktur File
```
resources/views/partials/
  ├─ sidebar.blade.php         # Vertical & Semibox, include menu-items
  ├─ horizontal.blade.php      # Horizontal, include menu-items (prefix h-)
  └─ menu-items.blade.php      # Sumber menu global (di-edit di sini)
```

## Cara Menambah Menu
- Gunakan kelas bawaan agar tema Light/Dark otomatis:
  - Item: `li.pe-slide` → `a.pe-nav-link` → ikon `i.bi ... pe-nav-icon` atau `i.ri ... pe-nav-icon`
  - Judul seksi: `li.pe-menu-title`
- Contoh menambah satu item menu sederhana:
```html
<li class="pe-menu-title">Main</li>
<li class="pe-slide">
  <a href="/fitur-baru" class="pe-nav-link">
    <i class="bi bi-grid pe-nav-icon"></i>
    <span class="pe-nav-content">Fitur Baru</span>
  </a>
</li>
```
- Untuk submenu (dropdown), gunakan pola `pe-has-sub` + `collapse`:
```html
@php($p = isset($menuIdPrefix) ? $menuIdPrefix : '')
<li class="pe-slide pe-has-sub">
  <a href="#{{ $p }}collapseMaster" class="pe-nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="{{ $p }}collapseMaster">
    <i class="bi bi-collection pe-nav-icon"></i>
    <span class="pe-nav-content">Master</span>
    <i class="ri-arrow-down-s-line pe-nav-arrow"></i>
  </a>
  <ul class="pe-slide-menu collapse" id="{{ $p }}collapseMaster">
    <li class="pe-slide-item"><a href="/master/users" class="pe-nav-link">Users</a></li>
    <li class="pe-slide-item"><a href="/master/roles" class="pe-nav-link">Roles</a></li>
  </ul>
</li>
```
Catatan: variabel `$menuIdPrefix` akan otomatis berisi `'h-'` pada layout horizontal. Jangan hapus prefix ini agar `id` collapse unik.

## Ikon & Tema
- Ikon mendukung Remix Icon dan Bootstrap Icons.
- Gunakan `pe-nav-icon` dan hindari inline color agar warna mengikuti tema Light/Dark.
- Referensi nama ikon Remix Icon: [remixicon.com](https://remixicon.com/).

## Perubahan Cepat (Tanpa Build)
- Karena menu ada di Blade, cukup edit file dan (opsional) bersihkan cache view:
```bash
php artisan view:clear
```

## Praktik Terbaik
- Hindari menempelkan HTML sidebar langsung di `sidebar.blade.php` atau `horizontal.blade.php`; selalu gunakan `@include('partials.menu-items')`.
- Untuk konsistensi navigasi aktif, gunakan tautan path yang stabil (mis. `/dashboard`, `/fitur-baru`).
- Jangan gunakan `text-white/text-dark` atau inline `style="color:..."` pada menu agar tema tetap otomatis.
