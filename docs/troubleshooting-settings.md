# Troubleshooting Website Settings

## Issue: Form Submission Error

### Problem
Form di `/settings/system` tidak bisa disimpan dan menampilkan error.

### Root Cause Analysis

#### 1. **CSRF Token Issue (Error 419)**
- **Error**: "Page Expired" atau HTTP 419
- **Cause**: CSRF token tidak valid atau tidak dikirim
- **Solution**: 
  - Pastikan meta tag CSRF ada di layout: `<meta name="csrf-token" content="{{ csrf_token() }}">`
  - Pastikan JavaScript mengirim header: `X-CSRF-TOKEN`
  - Pastikan form memiliki `@csrf` directive

#### 2. **Function Call Error**
- **Error**: "Call to undefined method Illuminate\Routing\RouteFileRegistrar::resizeImage()"
- **Cause**: Menggunakan `$this->resizeImage()` di route closure
- **Solution**: Gunakan `resizeImage()` sebagai global function

#### 3. **Database Connection**
- **Error**: Database connection issues
- **Solution**: 
  - Pastikan database credentials benar di `.env`
  - Test connection dengan `php artisan tinker`
  - Cek tabel `site_settings` exists

#### 4. **File Upload Permissions**
- **Error**: File upload gagal
- **Solution**:
  - Pastikan direktori `public/uploads/site/` exists
  - Set permission 755: `chmod 755 public/uploads/site/`
  - Pastikan web server bisa write ke direktori

### Debugging Steps

#### 1. **Check Logs**
```bash
tail -f storage/logs/laravel.log
```

#### 2. **Test Database**
```bash
mysql -h [host] -u [user] -p [database] -e "SELECT * FROM site_settings;"
```

#### 3. **Test Route**
```bash
curl -X POST https://domain.com/settings/system/save \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "site_name=Test&site_description=Test&footer_text=Test&default_theme=light&timezone=Asia/Jakarta" \
  -H "X-CSRF-TOKEN: [token]"
```

#### 4. **Check PHP Extensions**
```bash
php -m | grep -i gd  # For image processing
```

### Fixed Issues

#### ✅ **CSRF Token Fixed**
- Added proper CSRF token handling in JavaScript
- Added `X-Requested-With: XMLHttpRequest` header
- Improved error handling in fetch request

#### ✅ **Function Call Fixed**
- Changed `$this->resizeImage()` to `resizeImage()`
- Function is defined as global function in routes/web.php

#### ✅ **Error Handling Improved**
- Added response.ok check before parsing JSON
- Better error messages in JavaScript
- Comprehensive logging in backend

### Current Status

#### ✅ **Working Components**
- Database connection: OK
- Settings table: 10 records
- Database update: OK  
- resizeImage function: EXISTS
- File upload directory: Created with proper permissions
- CSRF token: Properly configured
- Form validation: Client and server-side

#### ✅ **Test Results**
- Page loads without errors
- Form validation works
- Toast notifications display correctly
- File upload directory accessible
- Database operations successful

### Prevention

#### 1. **Always Use Global Functions in Routes**
```php
// ❌ Wrong
$this->resizeImage($path, $path, $width, $height);

// ✅ Correct  
resizeImage($path, $path, $width, $height);
```

#### 2. **Proper CSRF Handling**
```javascript
// ✅ Correct
fetch('/settings/system/save', {
    method: 'POST',
    body: formData,
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'X-Requested-With': 'XMLHttpRequest'
    }
})
```

#### 3. **Error Handling**
```javascript
// ✅ Correct
.then(response => {
    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }
    return response.json();
})
```

### Monitoring

#### 1. **Check Logs Regularly**
```bash
tail -f storage/logs/laravel.log | grep -i "settings\|error"
```

#### 2. **Monitor File Uploads**
```bash
ls -la public/uploads/site/
```

#### 3. **Database Health**
```bash
mysql -e "SELECT COUNT(*) FROM site_settings;"
```

### Success Indicators

- ✅ Form submission returns success response
- ✅ Settings saved to database
- ✅ File uploads work with auto compression
- ✅ Toast notifications display correctly
- ✅ Page refresh shows updated values
- ✅ No errors in Laravel logs

Website settings feature is now fully functional and stable! 🎯
