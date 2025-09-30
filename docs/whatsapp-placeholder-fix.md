# WhatsApp Placeholder Fix

## Masalah yang Ditemukan
Error: **"Undefined constant 'name'"** pada textarea placeholder

## Penyebab Error
Dalam placeholder textarea, kita menggunakan `{{name}}` yang diinterpretasikan sebagai Blade syntax, padahal seharusnya hanya teks biasa untuk placeholder.

## Perbaikan yang Dilakukan

### Sebelum (Error):
```html
<textarea placeholder="Hello {{name}}, this is a notification from PLN GPS Center...">
```

### Sesudah (Fixed):
```html
<textarea placeholder="Hello {name}, this is a notification from PLN GPS Center...">
```

## Penjelasan

### Mengapa Error Terjadi?
- **Blade Syntax**: `{{ }}` adalah syntax Blade untuk output variables
- **Placeholder**: Placeholder adalah atribut HTML biasa, bukan Blade template
- **Konflik**: Blade mencoba menginterpretasikan `{{name}}` sebagai variable

### Solusi
- **Gunakan `{name}`**: Tanpa kurung kurawal ganda
- **Escape Character**: Atau gunakan `@{{name}}` untuk escape
- **Single Curly**: `{name}` tidak diinterpretasikan sebagai Blade syntax

## Template Message yang Benar

### Default Template (Database):
```
Hello {{name}}, this is a notification from PLN GPS Center. Thank you for using our service.
```

### Placeholder (HTML):
```
Hello {name}, this is a notification from PLN GPS Center...
```

## Perbedaan Penggunaan

| Context | Syntax | Purpose |
|---------|--------|---------|
| **Database Value** | `{{name}}` | Placeholder untuk replacement |
| **HTML Placeholder** | `{name}` | Hanya teks contoh |
| **Blade Output** | `{{ $variable }}` | Output variable |

## Status: ✅ FIXED

### Changes Made:
- ✅ Fixed placeholder syntax
- ✅ No more "Undefined constant" error
- ✅ Template functionality preserved
- ✅ User experience improved

### Testing:
- ✅ No syntax errors
- ✅ No linting errors
- ✅ Cache cleared
- ✅ Ready for use

## Best Practices

### Untuk Template Messages:
1. **Database**: Gunakan `{{name}}` untuk placeholder replacement
2. **HTML Placeholder**: Gunakan `{name}` untuk contoh teks
3. **Blade Output**: Gunakan `{{ $variable }}` untuk output

### Contoh Implementasi:
```php
// Database value
'whatsapp_default_template' => 'Hello {{name}}, this is a notification from PLN GPS Center. Thank you for using our service.'

// HTML placeholder
placeholder="Hello {name}, this is a notification from PLN GPS Center..."
```

Error telah diperbaiki dan form WhatsApp sekarang berfungsi dengan baik! 🎉
