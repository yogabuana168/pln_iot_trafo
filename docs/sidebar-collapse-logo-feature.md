# Fitur Logo Icon untuk Sidebar Collapse

## Deskripsi
Fitur ini memungkinkan admin untuk mengupload icon logo khusus yang akan ditampilkan saat sidebar dalam kondisi collapse (mode icon). Ini memberikan fleksibilitas tampilan yang lebih baik dengan logo yang sesuai untuk setiap kondisi sidebar.

## Fitur yang Ditambahkan

### 1. Upload Icon Logo
- Field upload baru di halaman System Settings
- Format file: JPG, PNG, GIF, SVG
- Ukuran maksimal: 1MB
- Ukuran otomatis: 40x40px (untuk collapsed sidebar)

### 2. Logika Switch Logo
- Logo normal ditampilkan saat sidebar expanded
- Icon logo ditampilkan saat sidebar collapsed
- Fallback ke logo normal jika icon logo tidak diupload

### 3. Implementasi Frontend
- JavaScript otomatis mengatur tampilan logo berdasarkan state sidebar
- Responsif terhadap perubahan state sidebar
- Kompatibel dengan tema light/dark

## File yang Dimodifikasi

### Backend
- `resources/views/settings/system.blade.php` - Form upload icon logo
- `app/Helpers/SiteSettings.php` - Method `getLogoIconUrl()`
- `routes/web.php` - Validasi dan upload icon logo

### Frontend
- `resources/views/partials/sidebar.blade.php` - Template logo dengan icon
- `public/assets/js/layout-setup.js` - Logika switch logo

## Cara Penggunaan

1. Masuk ke halaman **Settings > System Setting**
2. Di bagian **Logo Settings**, upload icon logo di field **"Upload Icon Logo (Collapsed Sidebar)"**
3. Simpan settings
4. Icon logo akan otomatis ditampilkan saat sidebar dalam mode collapse

## Spesifikasi Teknis

### Database
- Field baru: `logo_icon_path` di tabel `site_settings`
- Menyimpan path file icon logo

### File Upload
- Direktori: `public/uploads/site/`
- Nama file: `logo_icon_[timestamp].[extension]`
- Auto resize ke 40x40px

### CSS Classes
- `.pe-app-sidebar-logo-default` - Logo normal (expanded)
- `.pe-app-sidebar-logo-light` - Logo normal light theme
- `.pe-app-sidebar-logo-minimize` - Icon logo (collapsed)
- `.pe-app-sidebar-logo-minimize-light` - Icon logo light theme

## Kompatibilitas
- ✅ Responsive design
- ✅ Light/Dark theme
- ✅ Mobile friendly
- ✅ Browser compatibility
