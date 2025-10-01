# WA Notification Settings - Moveable Containers

## 🎯 Konsep
Sistem **multiple moveable containers** seperti sticky notes yang bisa dipindah-pindah di canvas. Setiap container berisi:
- **1 Template WhatsApp** (wajib)
- **Multiple Users/Groups** (min 1)

## 🎨 Layout UI

```
┌─────────────────────────────────────────────────────────┐
│                  [Save All Configurations]              │
│                                                         │
│  ┌────────────┐    ╔════════════════╗    ┌──────────┐  │
│  │ Users &    │    ║    CANVAS      ║    │WhatsApp  │  │
│  │ Groups     │    ║                ║    │Templates │  │
│  ├────────────┤    ║  ┌──────────┐  ║    ├──────────┤  │
│  │ [Groups]   │    ║  │Container1│  ║    │Template1 │  │
│  │ [Users]    │    ║  │ • Template║    │Template2 │  │
│  │            │───drag→│ • Users  │  ║←drag─Template3 │  │
│  │ • Group1   │    ║  │ • Groups │  ║    │Template4 │  │
│  │ • Group2   │    ║  └──────────┘  ║    └──────────┘  │
│  │ • User1    │    ║                ║                  │
│  │ • User2    │    ║  ┌──────────┐  ║                  │
│  └────────────┘    ║  │Container2│  ║                  │
│                    ║  │ • Template║                  │
│                    ║  │ • Users  │  ║                  │
│                    ║  └──────────┘  ║                  │
│                    ╚════════════════╝                  │
│                                                         │
│              [+ Add Container]                          │
└─────────────────────────────────────────────────────────┘
```

## ✨ Fitur Utama

### 1. **Panel Kiri - Recipients Source** (Fixed Position)
- 📍 **Position**: Fixed left (250px width)
- **Tabs**:
  - Groups tab
  - Users tab
- **Search**: Real-time filter
- **Drag Source**: Drag users/groups ke container

### 2. **Panel Tengah - Canvas Area**
- 📍 **Position**: Between left & right panels
- **Background**: Gradient (abu-abu)
- **Canvas untuk multiple containers**
- Container bisa **dipindah-pindah posisi** (drag header)
- Infinite canvas (scroll if needed)

### 3. **Panel Kanan - Templates Source** (Fixed Position)
- 📍 **Position**: Fixed right (250px width)
- **Background**: Green gradient
- **WhatsApp templates** yang aktif
- **Search**: Real-time filter
- **Drag Source**: Drag template ke container

### 4. **Container (Moveable Box)**
📦 **Container Components**:
```
┌─────────────────────────────┐
│ Container #1            [×] │ ← Header (Drag to move)
├─────────────────────────────┤
│ ┌─────────────────────────┐ │
│ │ 🟢 Template Name        │ │ ← Template Slot (Drop here)
│ └─────────────────────────┘ │
│                             │
│ ┌─────────────────────────┐ │
│ │ 👥 User1            [×] │ │
│ │ 👥 Group1           [×] │ │ ← Recipients Slot (Drop here)
│ │ 👤 User2            [×] │ │
│ └─────────────────────────┘ │
└─────────────────────────────┘
```

**Container Features**:
- ✅ **Moveable**: Drag header untuk pindah posisi
- ✅ **Drop Zone untuk Template**: Drop 1 template (locked setelah diisi)
- ✅ **Drop Zone untuk Recipients**: Drop multiple users/groups
- ✅ **Remove Recipients**: Click × untuk hapus
- ✅ **Delete Container**: Click × di header

### 5. **Buttons**
- **"Add Container"** (bottom center): Buat container baru
- **"Save All Configurations"** (top center): Save semua container

## 🚀 Cara Penggunaan

### Step 1: Buat Container
1. Click tombol **"Add Container"**
2. Container muncul di canvas
3. Ulangi untuk multiple containers

### Step 2: Drop Template
1. **Drag** template dari panel kanan
2. **Drop** ke area hijau "Drop Template Here"
3. Template akan locked (tidak bisa diganti kecuali delete container)

### Step 3: Drop Recipients
1. **Drag** user/group dari panel kiri
2. **Drop** ke area abu "Drop Users/Groups Here"
3. Ulangi untuk menambah recipients
4. Click **×** untuk remove recipient

### Step 4: Pindah Container
1. **Drag** header container (warna biru)
2. **Move** ke posisi yang diinginkan
3. Container akan tetap di posisi tersebut

### Step 5: Save
1. Review semua containers
2. Pastikan setiap container punya template + min 1 recipient
3. Click **"Save All Configurations"**
4. Toast notification muncul

## 📂 File Structure

```
resources/views/settings/wa.blade.php
├── HTML Structure
│   ├── Source Panel Left (Fixed)
│   │   ├── Header
│   │   ├── Search Box
│   │   ├── Tabs (Groups/Users)
│   │   └── Lists (draggable items)
│   ├── Canvas Area (Center)
│   │   └── Multiple Containers (moveable)
│   ├── Source Panel Right (Fixed)
│   │   ├── Header (Green)
│   │   ├── Search Box
│   │   └── Templates List
│   ├── Add Container Button (Bottom)
│   └── Save All Button (Top)
│
└── CSS Styles (embedded)
    ├── .source-panel (fixed panels)
    ├── .source-item (draggable items)
    ├── .canvas-area (center canvas)
    ├── .notification-container (moveable box)
    ├── .container-header (drag handle)
    ├── .template-slot (drop zone)
    └── .recipients-slot (drop zone)

public/assets/js/wa-containers.js
├── State Management
│   ├── containers[] (array of containers)
│   │   └── { id, x, y, template, recipients[] }
│   ├── allGroups[]
│   ├── allUsers[]
│   └── allTemplates[]
│
├── Core Functions
│   ├── loadData() - Fetch dari API
│   ├── addContainer() - Buat container baru
│   ├── renderContainer() - Render container ke canvas
│   ├── updateContainer() - Update tampilan container
│   ├── deleteContainer() - Hapus container
│   └── removeRecipient() - Hapus recipient
│
├── Drag & Drop
│   ├── attachSourceDrag() - Drag dari source panels
│   ├── attachDropZones() - Drop ke container slots
│   └── makeDraggable() - Move container position
│
└── Save
    └── saveAllBtn.click() - Save configurations
```

## 🎨 CSS Classes

| Class | Purpose |
|-------|---------|
| `.source-panel` | Fixed side panels (left/right) |
| `.source-panel-left` | Left panel (users/groups) |
| `.source-panel-right` | Right panel (templates) |
| `.source-item` | Draggable items in source panels |
| `.template-source-item` | Template items (green) |
| `.canvas-area` | Center canvas for containers |
| `.notification-container` | Moveable container box |
| `.container-header` | Header (drag handle) |
| `.template-slot` | Drop zone untuk template |
| `.template-slot.filled` | Template sudah diisi |
| `.recipients-slot` | Drop zone untuk recipients |
| `.recipients-slot.drag-over` | Hover state |
| `.recipient-item` | Individual recipient item |

## 🎭 Interaksi Drag & Drop

### Drag dari Source Panel
```javascript
// Drag start: Store data
draggedData = {
    type: 'user' | 'group' | 'template',
    id: 123,
    name: 'User Name',
    email: 'user@email.com'
};
```

### Drop ke Container
```javascript
// Template Slot
if (draggedData.type === 'template') {
    container.template = { id, name };
}

// Recipients Slot
if (draggedData.type === 'user' || draggedData.type === 'group') {
    container.recipients.push({ type, id, name, email });
}
```

### Move Container Position
```javascript
// Mousedown on header → Start drag
// Mousemove → Update position (x, y)
// Mouseup → Stop drag
container.x = currentX;
container.y = currentY;
```

## 💾 Data Structure

### Container Object
```javascript
{
    id: 1,
    x: 100,           // Position X
    y: 50,            // Position Y
    template: {
        id: 5,
        name: "Alert Template"
    },
    recipients: [
        { type: 'group', id: 1, name: 'Admin Group' },
        { type: 'user', id: 3, name: 'John Doe', email: 'john@example.com' }
    ]
}
```

### Save Payload
```javascript
// Convert to API format
{
    audience_type: 'group' | 'users',
    audience_ids: [1, 2, 3],
    template_id: 5
}
```

## 📡 API Integration

### Load Data
```javascript
GET /api/wa/audience-options
→ { groups[], users[], templates[] }
```

### Save Configuration
```javascript
POST /api/wa/setting/save
Body: {
    audience_type: 'group',
    audience_ids: [1, 2],
    template_id: 5
}
→ { success: true }
```

## ✅ Validasi

### Container Level
- ❌ Template wajib diisi
- ❌ Minimum 1 recipient
- ❌ Tidak bisa duplicate recipient

### Save Level
- ❌ Semua container harus valid
- ❌ Template tidak boleh null
- ❌ Recipients tidak boleh kosong

## 🎯 Use Cases

### Use Case 1: Single Notification
```
Container #1:
  Template: "Server Down Alert"
  Recipients: 
    - Admin Group
    - IT Manager (user)
```

### Use Case 2: Multiple Different Notifications
```
Container #1:
  Template: "Critical Alert"
  Recipients: Admin Group

Container #2:
  Template: "Daily Report"
  Recipients: 
    - Manager Group
    - Director (user)

Container #3:
  Template: "Maintenance Notice"
  Recipients: All Users Group
```

### Use Case 3: Same Template, Different Recipients
```
Container #1:
  Template: "Status Update"
  Recipients: Regional Team 1

Container #2:
  Template: "Status Update"
  Recipients: Regional Team 2
```

## 🚀 Advantages

### vs Old UI (Dropdown)
| Old | New Moveable Containers |
|-----|-------------------------|
| ❌ Single configuration | ✅ Multiple configurations |
| ❌ Static form | ✅ Visual & interactive |
| ❌ Dropdown select | ✅ Drag & drop |
| ❌ Tidak flexible | ✅ Move anywhere |
| ❌ Sulit organize | ✅ Easy to organize |

### vs Previous Drag & Drop
| Previous | New |
|----------|-----|
| ❌ Single drop zone | ✅ Multiple containers |
| ❌ Static layout | ✅ Moveable containers |
| ❌ Fixed positions | ✅ Free positioning |
| ❌ Limited to 1 config | ✅ Unlimited containers |

## 🎨 Visual States

### Empty State
```
┌──────────────────────┐
│   ⊕ (large icon)     │
│ Click "Add Container"│
│      to start        │
└──────────────────────┘
```

### Container States
1. **Empty**: Dashed borders for drop zones
2. **Template Filled**: Green solid border
3. **Recipients Added**: List with remove buttons
4. **Dragging**: Opacity 0.4 on source item
5. **Drag Over**: Blue highlight on drop zone

## 📱 Responsive Notes
- Fixed panels: 250px width
- Canvas: Dynamic (between panels)
- Container: 280px width
- Mobile: May need vertical layout (future)

## 🔒 Security
- CSRF token in save request
- Template validation (WhatsApp + active)
- Duplicate prevention
- XSS protection via escapeHtml()

## 🐛 Known Limitations
- Currently saves only first container (API limitation)
- Need API enhancement for multiple configs
- Canvas tidak infinite (terbatas viewport)
- No persistence of container positions

## 🔮 Future Enhancements
- [ ] API support untuk multiple configurations
- [ ] Persistence container positions
- [ ] Zoom in/out canvas
- [ ] Snap to grid
- [ ] Container templates/presets
- [ ] Export/import configurations
- [ ] Duplicate container
- [ ] Container grouping
- [ ] Connection lines between containers
- [ ] Mini-map untuk navigation

---
**Created**: 2025-10-01  
**Version**: 2.0.0 (Moveable Containers)  
**Previous Version**: 1.0.0 (Static Drag & Drop)

