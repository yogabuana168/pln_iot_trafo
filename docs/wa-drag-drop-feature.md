# WA Notification Settings - Drag & Drop Interface

## 📝 Deskripsi
Sistem drag and drop yang modern untuk mengkonfigurasi pengiriman notifikasi WhatsApp ke users atau groups dengan template yang sudah dibuat.

## 🎨 Tampilan UI

### Layout 3 Kolom:
```
┌──────────────────┬──────────────────┬──────────────────┐
│   Recipients     │    Selected      │   Templates      │
│   (Source)       │  (Drop Zone)     │   (Source)       │
├──────────────────┼──────────────────┼──────────────────┤
│ • Groups Tab     │ • Stats Card     │ • WhatsApp       │
│ • Users Tab      │ • Drop Zone      │   Templates      │
│ • Search Box     │ • Recipient List │ • Search Box     │
│ • Drag Items     │ • Remove Btn     │ • Drag Cards     │
│                  │                  │ • Click Select   │
│                  │ Template Display │                  │
│                  │ • Selected WA    │                  │
│                  │                  │                  │
│                  │  Save Button     │                  │
└──────────────────┴──────────────────┴──────────────────┘
```

## ✨ Fitur Utama

### 1. **Drag & Drop Recipients**
- **Panel Kiri**: Daftar Groups dan Users dalam tabs
  - Tab Groups: Menampilkan semua user groups
  - Tab Users: Menampilkan semua users
  - Search box untuk filter real-time
  - Drag icon (grip vertical) untuk visual cue

- **Panel Tengah**: Drop zone untuk recipients
  - Stats card menampilkan total recipients
  - Visual drop zone dengan border dashed
  - Drag-over effect (highlight saat hover)
  - List recipients yang sudah dipilih
  - Remove button untuk setiap recipient
  - Clear All button

### 2. **Drag & Drop Templates**
- **Panel Kanan**: WhatsApp Templates
  - Hanya menampilkan template WhatsApp yang aktif
  - Search box untuk filter templates
  - Drag ke panel tengah untuk select
  - Click untuk select (alternative to drag)
  - Visual selected state (green border)

### 3. **Visual Effects**
- Gradient backgrounds (purple, green)
- Smooth animations (slideIn, transform)
- Hover effects pada drag items
- Dragging state (opacity 0.5)
- Drag-over state (blue highlight)

### 4. **Validasi**
- Minimum 1 recipient harus dipilih
- Template wajib dipilih
- Duplicate recipient tidak allowed
- Toast notification untuk feedback

## 🔧 Cara Penggunaan

### Step 1: Pilih Recipients
1. Buka tab "Groups" atau "Users"
2. (Optional) Gunakan search box untuk filter
3. **Drag** item ke drop zone tengah
4. Item akan muncul di list "Selected Recipients"
5. Ulangi untuk menambah recipients
6. Klik tombol "X" untuk remove recipient

### Step 2: Pilih WhatsApp Template
1. Lihat panel kanan "WhatsApp Templates"
2. (Optional) Gunakan search box untuk filter
3. **Drag** template ke area "Selected Template" ATAU
4. **Click** pada card template untuk select
5. Template yang dipilih akan highlighted (green)

### Step 3: Save
1. Review jumlah recipients di stats card
2. Review template yang dipilih
3. Klik tombol "Save WA Notification Settings"
4. Toast notification akan muncul jika berhasil

## 📂 File Structure

```
resources/views/settings/wa.blade.php
├── HTML Structure
│   ├── 3 Column Layout
│   │   ├── Col 1: Recipients (Groups/Users)
│   │   ├── Col 2: Selected (Drop Zone + Template)
│   │   └── Col 3: WhatsApp Templates
│   └── CSS Styles (embedded)
│       ├── .drag-source-panel
│       ├── .drag-item
│       ├── .drop-zone
│       ├── .dropped-item
│       ├── .template-card
│       └── .stat-card
│
public/assets/js/wa-drag-drop.js
├── State Management
│   ├── selectedRecipients[]
│   ├── selectedTemplate
│   ├── allGroups[]
│   ├── allUsers[]
│   └── allTemplates[]
├── Render Functions
│   ├── renderGroups()
│   ├── renderUsers()
│   ├── renderTemplates()
│   ├── updateDropZone()
│   └── updateTemplateDisplay()
├── Drag & Drop Events
│   ├── attachDragEvents()
│   ├── attachTemplateDragEvents()
│   ├── dragstart
│   ├── dragend
│   ├── dragover
│   ├── dragleave
│   └── drop
└── API Calls
    ├── loadData()
    ├── /api/wa/audience-options
    ├── /api/wa/setting (GET)
    └── /api/wa/setting/save (POST)
```

## 🔌 API Endpoints

### GET `/api/wa/audience-options`
**Response:**
```json
{
  "groups": [
    {"id": 1, "name": "Admin Group"},
    {"id": 2, "name": "Field Staff"}
  ],
  "users": [
    {"id": 1, "name": "John Doe", "email": "john@example.com"},
    {"id": 2, "name": "Jane Smith", "email": "jane@example.com"}
  ],
  "templates": [
    {"id": 1, "name": "Alert Template", "type": "whatsapp", "status": "active"},
    {"id": 2, "name": "Info Template", "type": "whatsapp", "status": "active"}
  ]
}
```

### GET `/api/wa/setting`
**Response:**
```json
{
  "audience_type": "group",
  "audience_ids": [1, 2],
  "template_id": 1
}
```

### POST `/api/wa/setting/save`
**Request:**
```json
{
  "audience_type": "group",
  "audience_ids": [1, 2],
  "template_id": 1
}
```

**Response:**
```json
{
  "success": true
}
```

## 🎯 Business Logic

### Audience Type Determination:
1. **Only Groups selected** → `audience_type: 'group'`
2. **Only Users selected** → `audience_type: 'users'`
3. **Mixed (Groups + Users)** → Convert to `users` only (with warning)

### Data Flow:
```
Load Page
   ↓
Fetch /api/wa/audience-options
   ↓
Render Groups, Users, Templates
   ↓
Fetch /api/wa/setting (existing settings)
   ↓
Populate selected recipients & template
   ↓
User Interaction (Drag & Drop)
   ↓
Update UI State
   ↓
Click Save Button
   ↓
Validate (recipients > 0, template selected)
   ↓
POST /api/wa/setting/save
   ↓
Show Toast Notification
```

## 🎨 CSS Classes

| Class | Purpose |
|-------|---------|
| `.drag-source-panel` | Container untuk draggable items |
| `.drag-item` | Individual draggable item (user/group) |
| `.drag-item.dragging` | State saat sedang di-drag |
| `.drop-zone` | Area untuk drop recipients |
| `.drop-zone.drag-over` | State saat hover over drop zone |
| `.drop-zone.empty` | State saat belum ada recipients |
| `.dropped-item` | Recipient yang sudah di-drop |
| `.template-card` | Card untuk template WhatsApp |
| `.template-card.selected` | State template yang dipilih |
| `.stat-card` | Card untuk menampilkan statistics |

## 🔔 Toast Notifications

| Event | Message | Type |
|-------|---------|------|
| Recipient added | "Recipient added!" | Success |
| Duplicate recipient | "Already added!" | Error |
| Recipient removed | "Recipient removed" | Success |
| Template selected | "Template selected!" | Success |
| All cleared | "All recipients cleared" | Success |
| Save validation fail (no recipients) | "Please select at least one recipient!" | Error |
| Save validation fail (no template) | "Please select a WhatsApp template!" | Error |
| Save success | "✅ WA Notification Settings saved successfully!" | Success |
| Save error | "Failed to save settings" | Error |

## 🚀 Keuntungan Drag & Drop vs Old UI

### Old UI (Form-based):
- ❌ Radio buttons untuk audience type
- ❌ Multiple select dropdown (sulit di mobile)
- ❌ Tidak visual
- ❌ Sulit melihat siapa saja yang dipilih
- ❌ Tidak ada feedback visual

### New UI (Drag & Drop):
- ✅ Intuitif dan visual
- ✅ Mobile-friendly (drag & drop works on touch)
- ✅ Clear separation of concerns (3 panels)
- ✅ Real-time visual feedback
- ✅ Stats card untuk monitoring
- ✅ Search functionality
- ✅ Beautiful animations
- ✅ Modern UX/UI

## 📱 Responsive Design
- Desktop (>992px): 3 columns side by side
- Tablet (768-991px): 3 columns with smaller width
- Mobile (<768px): Stacked vertically (col-12)

## 🔐 Security
- CSRF token included in save request
- Template validation (only WhatsApp + active)
- Audience IDs validation (exists in database)
- XSS protection via `escapeHtml()` function

## 📈 Future Enhancements
- [ ] Bulk selection (select all groups/users)
- [ ] Preview template content before select
- [ ] Drag & drop ordering untuk recipients
- [ ] Export/import settings
- [ ] Schedule sending notifications
- [ ] Test send notification feature

## 🎓 Developer Notes
- Uses native HTML5 Drag & Drop API
- No external library dependencies (except Bootstrap)
- Vanilla JavaScript (no jQuery)
- Modular code structure
- Reusable toast notification function
- State management dengan plain JS objects

---
**Created**: 2025-10-01  
**Author**: AI Assistant  
**Version**: 1.0.0

