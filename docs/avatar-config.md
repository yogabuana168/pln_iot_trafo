# Avatar Configuration

## Default Avatar
- **File**: `public/assets/images/dashboard/3d-rendering-avatar-min.png`
- **Size**: 697KB
- **Format**: PNG
- **Usage**: Default avatar untuk semua user yang tidak memiliki avatar custom

## Avatar Logic
1. **Jika user memiliki avatar custom**: Tampilkan avatar dari `uploads/avatars/`
2. **Jika user tidak memiliki avatar**: Tampilkan avatar default `3d-rendering-avatar-min.png`

## File Locations
```
public/assets/images/dashboard/3d-rendering-avatar-min.png  # Default avatar
public/uploads/avatars/                                     # Custom avatars
```

## Implementation
```php
@php
    $avatarSrc = asset('assets/images/dashboard/3d-rendering-avatar-min.png'); // Default
    if ($user->avatar_path && file_exists(public_path($user->avatar_path))) {
        $avatarSrc = asset($user->avatar_path);
    }
@endphp
<img src="{{ $avatarSrc }}" alt="Avatar Image">
```

## Database Schema
```sql
avatar_path VARCHAR(255) NULL  -- Path to custom avatar, NULL = use default
avatar_mime VARCHAR(100) NULL  -- MIME type of uploaded avatar
avatar_size INT NULL          -- File size in bytes
```

## Current Users
Semua user saat ini menggunakan avatar default karena `avatar_path` adalah NULL.
