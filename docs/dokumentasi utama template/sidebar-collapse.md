# Perbaikan Sidebar (Collapse/Icon Mode)

Dokumen ini menjelaskan perubahan untuk memastikan saat sidebar di-collapse, hanya ikon yang tampil (label dan title disembunyikan) tanpa CSS custom terpisah.

## Ringkasan
- Toggle menggunakan atribut pada `<html>`: `data-sidebar="default" | "icon"`.
- Menyembunyikan label (`.pe-nav-content`) dan judul section (`.pe-menu-title`) saat `data-sidebar="icon"` melalui JavaScript (inline style) agar tidak bentrok dengan CSS bawaan.

## File yang Diubah
- `public/assets/js/layout-setup.js`
  - Ditambahkan fungsi `updateIconModeVisibility()` dan dipanggil pada:
    - `updateSimpleBar(layout)`
    - Event klik toggle sidebar
    - Inisialisasi awal

```javascript
function updateIconModeVisibility() {
  const isIcon = document.documentElement.getAttribute("data-sidebar") === "icon";
  const hideSelectors = [
    '#sidebar .pe-nav-content',
    '#sidebar .pe-menu-title'
  ];
  hideSelectors.forEach((selector) => {
    document.querySelectorAll(selector).forEach((el) => {
      el.style.display = isIcon ? 'none' : '';
    });
  });
}
```

## Alur Toggle
1. Tombol toggle di `partials/header.blade.php` (`#toggleSidebar`) diklik.
2. `layout-setup.js` mengubah `data-sidebar` menjadi `icon` atau `default`.
3. `updateIconModeVisibility()` menyembunyikan/menampilkan label dan title sesuai mode.
4. `removeShowClassFromSidebar()` menutup submenu yang masih terbuka.

## Dampak UI
- Mode default: ikon + label terlihat, judul section tampil.
- Mode icon: lebar sidebar kecil, hanya ikon yang terlihat, judul section hilang, panah dropdown tidak relevan.

## Catatan
- Solusi ini tidak mengubah SCSS. Jika ingin permanen di level SCSS, gunakan selector `[data-sidebar="icon"]` untuk menyembunyikan `.pe-nav-content` dan `.pe-menu-title` (mengikuti pola dari file structure `_icon.scss` pada template asli), namun pastikan tidak memicu flicker (inisialisasi JS terlebih dahulu).

