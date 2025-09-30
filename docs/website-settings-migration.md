# Website Settings Migration

## Deskripsi
Memindahkan card "Website Settings" dari `/settings/system` ke `/settings/site` untuk organisasi menu yang lebih baik.

## Perubahan yang Dilakukan

### 1. **From: /settings/system**
Card Website Settings **dihapus** dari System Setting page.

### 2. **To: /settings/site**
Card Website Settings **dipindahkan** ke Site Setup page.

## Alasan Migrasi

### Logical Organization
- ✅ **Site Setup** lebih sesuai untuk pengaturan website dasar
- ✅ **System Setting** fokus untuk konfigurasi teknis (SMTP, WhatsApp, Firebase, Google Maps)
- ✅ **Better UX**: User lebih mudah menemukan pengaturan yang dicari

### Menu Structure (Updated)
```
Setting
├─ System Setting (Technical)
│  ├─ EMAIL SMTP Settings
│  ├─ Google Map Settings
│  ├─ WHATSAPP NOTIFICATION Settings
│  └─ FIREBASE PUSH NOTIFICATION Settings
│
└─ Site Setup (Website Basic)
   └─ Website Settings
      ├─ Informasi Website (name, description, footer)
      ├─ Logo Settings (logo, icon logo)
      ├─ Favicon Settings
      ├─ Tema & Tampilan (theme, timezone)
      └─ Preview
```

## File Changes

### 1. resources/views/settings/site.blade.php
**Status**: ✅ Updated

**Changes**:
- Added complete Website Settings card
- Added form with all fields:
  - Nama Website
  - Deskripsi Website
  - Footer Text
  - Logo Upload
  - Icon Logo Upload
  - Favicon Upload
  - Favicon Size
  - Default Theme
  - Zona Waktu
  - Preview section
- Added JavaScript for form handling
- Added file upload handlers
- Added notification functions

### 2. resources/views/settings/system.blade.php
**Status**: ✅ Updated

**Changes**:
- Removed Website Settings card
- Now starts directly with EMAIL SMTP Settings
- Keeps all other cards:
  - EMAIL SMTP Settings
  - Google Map Settings
  - WHATSAPP NOTIFICATION Settings
  - FIREBASE PUSH NOTIFICATION Settings

### 3. routes/web.php
**Status**: ✅ Updated

**Changes**:
- Updated `/settings/site` route to load settings from database
- Added default values for all website settings
- Passes `$settings` variable to view

## Settings Fields

### Website Settings (Now in /settings/site)
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| **site_name** | Text | ✅ | Nama website |
| **site_description** | Text | ✅ | Deskripsi website |
| **footer_text** | Text | ✅ | Footer text |
| **logo_file** | File | ❌ | Upload logo (150x150px) |
| **logo_icon_file** | File | ❌ | Upload icon logo (40x40px) |
| **favicon_file** | File | ❌ | Upload favicon |
| **favicon_size** | Number | ❌ | Favicon size (16-64px) |
| **default_theme** | Select | ✅ | Light/Dark/System |
| **timezone** | Select | ✅ | Timezone |

## Backend Integration

### Route: GET /settings/site
```php
Route::get('/settings/site', function () {
    // Load site settings from database
    $settings = DB::table('site_settings')
        ->pluck('setting_value', 'setting_key')
        ->toArray();
    
    // Default values if not set
    $defaultSettings = [
        'site_name' => 'PLN GPS Center',
        'site_description' => 'PLN GPS Center Management System',
        'footer_text' => '© ' . date('Y') . ' PLN GPS Center',
        'default_theme' => 'light',
        'timezone' => 'Asia/Jakarta',
        'logo_path' => '',
        'logo_icon_path' => '',
        'favicon_path' => '',
        'favicon_size' => '32'
    ];
    
    $settings = array_merge($defaultSettings, $settings);
    
    return view('settings.site', compact('settings'));
});
```

### Form Submission
- **Endpoint**: `POST /settings/system/save` (existing route)
- **Method**: AJAX with FormData
- **Response**: JSON with success/error
- **Auto Reload**: After 1.5 seconds on success

## JavaScript Features

### Form Handling
```javascript
document.getElementById('siteSettingsForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/settings/system/save', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessNotification('Settings berhasil disimpan!');
            
            // Update favicon if changed
            if (formData.get('favicon_file')) {
                updateFaviconGlobally();
            }
            
            // Reload page
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }
    });
});
```

### File Upload Handlers
- ✅ Logo file input handler
- ✅ Logo icon file input handler
- ✅ Favicon file input handler
- ✅ Console log for debugging

### Favicon Update
```javascript
function updateFaviconGlobally() {
    const faviconUrl = '/settings/system/favicon?t=' + new Date().getTime();
    
    document.querySelectorAll('link[rel*="icon"]').forEach(link => {
        link.href = faviconUrl;
    });
}
```

## User Impact

### Before Migration
```
/settings/system:
  - Website Settings (logo, favicon, theme)
  - EMAIL SMTP Settings
  - Google Map Settings
  - WhatsApp Settings
  - Firebase Settings

/settings/site:
  - Empty placeholder
```

### After Migration
```
/settings/system:
  - EMAIL SMTP Settings
  - Google Map Settings
  - WhatsApp Settings
  - Firebase Settings

/settings/site:
  - Website Settings (logo, favicon, theme) ← Moved here
```

## Benefits

### Better Organization
- ✅ **Site Setup**: Basic website configuration
- ✅ **System Setting**: Technical integrations (APIs, SMTP)
- ✅ **Clear Separation**: UI/UX vs Technical settings

### Improved UX
- ✅ **Easier to Find**: Users know where to look
- ✅ **Logical Grouping**: Related settings together
- ✅ **Reduced Clutter**: System page less crowded

### Maintainability
- ✅ **Modular**: Each page has focused responsibility
- ✅ **Scalable**: Easy to add more settings
- ✅ **Clean Code**: Better file organization

## Testing Checklist

### /settings/site
- ✅ Page loads successfully
- ✅ All form fields visible
- ✅ Settings values loaded from database
- ✅ File upload works
- ✅ Form submission works
- ✅ Preview section shows data
- ✅ Notifications appear
- ✅ Page reloads after save

### /settings/system
- ✅ Website Settings card removed
- ✅ Starts with EMAIL SMTP Settings
- ✅ All other cards intact
- ✅ No broken links
- ✅ All functions still working

## Status: ✅ COMPLETED

### Completed Tasks
- ✅ Moved Website Settings card to /settings/site
- ✅ Removed Website Settings from /settings/system
- ✅ Updated /settings/site route to load data
- ✅ Added JavaScript handlers
- ✅ Added notification functions
- ✅ Tested all functionality
- ✅ Cleared caches
- ✅ No linter errors

### Ready for Use
- ✅ /settings/site fully functional
- ✅ /settings/system cleaned up
- ✅ All settings persist
- ✅ User experience improved
- ✅ Navigation logical
