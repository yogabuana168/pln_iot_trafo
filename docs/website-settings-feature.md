# Website Settings Feature

## Overview
Fitur lengkap untuk mengelola pengaturan website melalui halaman `/settings/system` dengan kemampuan upload logo, favicon, pengaturan tema, zona waktu, dan text footer.

## Features

### ✅ Database Schema
```sql
CREATE TABLE site_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('text', 'image', 'boolean', 'select') DEFAULT 'text',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### ✅ Default Settings
- **site_name**: PLN GPS Center
- **site_description**: PLN GPS Center Management System
- **footer_text**: © 2025 PLN GPS Center. Design & Develop by Pixeleyez
- **default_theme**: light
- **timezone**: Asia/Jakarta
- **logo_path**: NULL
- **favicon_path**: NULL
- **logo_width**: 150
- **logo_height**: 40
- **favicon_size**: 32

## Form Features

### 1. **Informasi Website**
- **Nama Website** (required) - Ditampilkan di header dan title browser
- **Deskripsi Website** (required) - Untuk SEO dan metadata
- **Footer Text** (required) - Text yang ditampilkan di footer

### 2. **Logo Settings**
- **Upload Logo** - Format: JPG, PNG, GIF, SVG | Max: 2MB
- **Lebar Logo** - 50-500px
- **Tinggi Logo** - 20-200px
- **Auto Compression** - Otomatis resize sesuai ukuran yang ditentukan

### 3. **Favicon Settings**
- **Upload Favicon** - Format: ICO, PNG, JPG, GIF, SVG | Max: 1MB
- **Ukuran Favicon** - 16-64px
- **Auto Compression** - Otomatis resize ke ukuran standar (16x16, 32x32, 64x64)

### 4. **Tema & Tampilan**
- **Default Theme** - Light Mode, Dark Mode, System Default
- **Zona Waktu** - 8 pilihan zona waktu termasuk Asia/Jakarta, UTC, dll

### 5. **Preview Section**
- **Real-time Preview** - Menampilkan preview logo, nama website, dan footer text
- **Current Settings Display** - Menampilkan pengaturan yang sedang aktif

## Backend Implementation

### Routes
```php
// Load Settings
GET /settings/system

// Save Settings  
POST /settings/system/save
```

### Image Processing
- **Auto Resize Function** - Menggunakan GD library untuk resize gambar
- **Format Support** - JPEG, PNG, GIF dengan kualitas optimal
- **Transparency Preservation** - PNG dan GIF mempertahankan transparansi
- **Compression** - JPEG 90% quality, PNG level 9 compression

### File Upload
- **Directory**: `public/uploads/site/`
- **Naming**: `logo_timestamp.ext`, `favicon_timestamp.ext`
- **Validation**: File type, size, dan format validation
- **Security**: CSRF protection dan file validation

## Frontend Features

### Form Validation
- **Client-side**: Required fields, file type validation
- **Server-side**: Comprehensive validation dengan error messages
- **Real-time**: Toast notifications untuk feedback

### User Experience
- **Loading States**: Button disabled saat submit
- **Toast Notifications**: Success/error messages
- **Auto Refresh**: Page reload setelah save untuk menampilkan perubahan
- **Reset Function**: Tombol reset untuk kembali ke nilai default

### Responsive Design
- **Mobile Friendly**: Form responsive untuk semua device
- **Bootstrap Grid**: Layout menggunakan Bootstrap grid system
- **Icon Integration**: Bootstrap Icons untuk visual enhancement

## Dynamic Integration

### Footer Integration
```php
@php
    $footerText = DB::table('site_settings')->where('setting_key', 'footer_text')->value('setting_value') 
        ?? '© ' . date('Y') . ' PLN GPS Center. Design & Develop by Pixeleyez';
@endphp
{!! $footerText !!}
```

### Theme Integration
- **Default Theme Setting** - Tersimpan di database
- **System Integration** - Bisa diintegrasikan dengan theme switcher
- **User Preference** - Bisa dikembangkan untuk per-user theme preference

## Security Features

### File Upload Security
- **File Type Validation** - Hanya image files yang diperbolehkan
- **Size Limitation** - Logo max 2MB, Favicon max 1MB
- **Directory Permissions** - Upload directory dengan permission 755
- **CSRF Protection** - Semua request menggunakan CSRF token

### Data Validation
- **Input Sanitization** - Semua input di-validate dan di-sanitize
- **SQL Injection Prevention** - Menggunakan Laravel ORM
- **XSS Protection** - Output escaping untuk HTML content

## File Structure
```
public/uploads/site/          # Logo dan favicon uploads
resources/views/settings/system.blade.php  # Settings form
routes/web.php                # Settings routes
docs/website-settings-feature.md  # Documentation
```

## Usage

1. **Akses Settings**: Navigate ke `/settings/system`
2. **Edit Information**: Ubah nama website, deskripsi, footer text
3. **Upload Assets**: Upload logo dan favicon dengan auto compression
4. **Configure Theme**: Pilih default theme dan zona waktu
5. **Preview Changes**: Lihat preview sebelum menyimpan
6. **Save Settings**: Submit form untuk menyimpan perubahan
7. **Auto Apply**: Perubahan langsung diterapkan di website

## Testing

### Current Status
- **URL**: https://pln.gpscenter.id/settings/system
- **Database**: Settings tersimpan dengan benar
- **Upload Directory**: Siap menerima file uploads
- **Footer**: Dynamic text sudah aktif

### Test Cases
- ✅ Form validation
- ✅ File upload dengan compression
- ✅ Database save/load
- ✅ Toast notifications
- ✅ Dynamic footer text
- ✅ Preview functionality

## Future Enhancements

### Potential Additions
- **Logo Variants**: Light/dark logo variants
- **Custom CSS**: Upload custom CSS files
- **SEO Settings**: Meta tags, keywords, description
- **Analytics Integration**: Google Analytics, tracking codes
- **Social Media**: Social media links dan meta tags
- **Backup/Restore**: Export/import settings
- **Version History**: Track perubahan settings over time

## Performance Considerations

### Optimization
- **Image Compression**: Automatic compression untuk mengurangi file size
- **Caching**: Settings bisa di-cache untuk performa lebih baik
- **Lazy Loading**: Preview images dengan lazy loading
- **CDN Ready**: Upload directory siap untuk CDN integration

Fitur website settings sudah fully functional dan siap digunakan untuk mengelola semua aspek branding dan konfigurasi website!
