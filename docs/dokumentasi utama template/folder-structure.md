# Struktur Folder

Struktur inti yang relevan untuk template admin ini:

```
app/
bootstrap/
config/
database/
docs/
node_modules/
public/
  assets/
resources/
  assets/
    js/
    scss/
    images/
    fonts/
    libs/
  views/
    partials/
      layouts/    <-- master.blade.php, master_auth.blade.php
routes/
storage/
vendor/
```

- Views utama berada di `resources/views/**`.
- Layout berada di `resources/views/partials/layouts/*` dan dipanggil dengan `@extends('partials.layouts.master')`.
- Aset hasil build ada di `public/assets/**` sesuai `webpack.mix.js`.
