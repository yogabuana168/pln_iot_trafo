@extends('partials.layouts.master')

@section('title', 'Site Setup')
@section('pagetitle', 'Site Setup')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Website Settings</h5>
                <p class="text-muted mb-3">Kelola pengaturan website, logo, favicon, tema, dan zona waktu.</p>
            </div>
            <div class="card-body">
                <form id="siteSettingsForm">
                    @csrf
                    
                    <!-- Basic Website Information -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="bi bi-globe me-2"></i>Informasi Website</h6>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Nama Website <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="site_name" value="{{ $settings['site_name'] ?? 'PLN GPS Center' }}" required>
                            <div class="form-text">Nama yang akan ditampilkan di header dan title browser</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Deskripsi Website <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="site_description" value="{{ $settings['site_description'] ?? 'PLN GPS Center Management System' }}" required>
                            <div class="form-text">Deskripsi singkat untuk SEO dan metadata</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Footer Text <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="footer_text" value="{{ $settings['footer_text'] ?? '© 2025 PLN GPS Center. Design & Develop by Pixeleyez' }}" required>
                            <div class="form-text">Text yang akan ditampilkan di footer website</div>
                        </div>
                    </div>

                    <!-- Logo Settings -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="bi bi-image me-2"></i>Logo Settings</h6>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Upload Logo (Normal)</label>
                            <input type="file" class="form-control" name="logo_file" accept="image/*" id="logoFileInput">
                            <div class="form-text">
                                Format: JPG, PNG, GIF, SVG | Max: 2MB | Ukuran maksimal: 150x150px (flexible) | 
                                @if(isset($settings['logo_path']) && $settings['logo_path'])
                                    <a href="{{ asset($settings['logo_path']) }}" target="_blank" class="text-primary">Lihat logo saat ini</a>
                                @else
                                    <span class="text-muted">Belum ada logo</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Upload Icon Logo (Collapsed Sidebar)</label>
                            <input type="file" class="form-control" name="logo_icon_file" accept="image/*" id="logoIconFileInput">
                            <div class="form-text">
                                Format: JPG, PNG, GIF, SVG | Max: 1MB | Ukuran maksimal: 40x40px | 
                                @if(isset($settings['logo_icon_path']) && $settings['logo_icon_path'])
                                    <a href="{{ asset($settings['logo_icon_path']) }}" target="_blank" class="text-primary">Lihat icon saat ini</a>
                                @else
                                    <span class="text-muted">Belum ada icon</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Favicon Settings -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="bi bi-bookmark me-2"></i>Favicon Settings</h6>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Upload Favicon</label>
                            <input type="file" class="form-control" name="favicon_file" accept="image/*,.ico" id="faviconFileInput">
                            <div class="form-text">
                                Format: ICO, PNG, JPG, GIF, SVG | Max: 1MB | 
                                @if(isset($settings['favicon_path']) && $settings['favicon_path'])
                                    <a href="{{ asset($settings['favicon_path']) }}" target="_blank" class="text-primary">Lihat favicon saat ini</a>
                                @else
                                    <span class="text-muted">Belum ada favicon</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Ukuran Favicon (px)</label>
                            <input type="number" class="form-control" name="favicon_size" value="{{ $settings['favicon_size'] ?? '32' }}" min="16" max="64">
                            <div class="form-text">Ukuran favicon akan disesuaikan otomatis (16x16, 32x32, 64x64)</div>
                        </div>
                    </div>

                    <!-- Theme & Appearance -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="bi bi-palette me-2"></i>Tema & Tampilan</h6>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Default Theme</label>
                            <select class="form-select" name="default_theme" required>
                                <option value="light" {{ ($settings['default_theme'] ?? 'light') == 'light' ? 'selected' : '' }}>Light Mode</option>
                                <option value="dark" {{ ($settings['default_theme'] ?? 'light') == 'dark' ? 'selected' : '' }}>Dark Mode</option>
                                <option value="system" {{ ($settings['default_theme'] ?? 'light') == 'system' ? 'selected' : '' }}>System Default</option>
                            </select>
                            <div class="form-text">Tema default yang akan digunakan saat pertama kali mengunjungi website</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Zona Waktu</label>
                            <select class="form-select" name="timezone" required>
                                <option value="Asia/Jakarta" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                                <option value="Asia/Makassar" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                                <option value="Asia/Jayapura" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                                <option value="UTC" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="America/New_York" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                                <option value="Europe/London" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                                <option value="Asia/Tokyo" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo (JST)</option>
                                <option value="Asia/Shanghai" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Shanghai' ? 'selected' : '' }}>Asia/Shanghai (CST)</option>
                            </select>
                            <div class="form-text">Zona waktu yang akan digunakan untuk menampilkan tanggal dan waktu</div>
                        </div>
                    </div>

                    <!-- Preview Section -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="bi bi-eye me-2"></i>Preview</h6>
                        </div>
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        @if(isset($settings['logo_path']) && $settings['logo_path'])
                                            <img src="{{ asset($settings['logo_path']) }}" alt="Logo Preview" 
                                                 style="max-width: 150px; max-height: 150px; width: auto; height: auto;">
                                        @else
                                            <div class="bg-secondary text-white px-3 py-2 rounded">
                                                {{ $settings['site_name'] ?? 'PLN GPS Center' }}
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-1">{{ $settings['site_name'] ?? 'PLN GPS Center' }}</h6>
                                            <small class="text-muted">{{ $settings['site_description'] ?? 'PLN GPS Center Management System' }}</small>
                                        </div>
                                    </div>
                                    <div class="text-muted">
                                        <small>{{ $settings['footer_text'] ?? '© 2025 PLN GPS Center. Design & Develop by Pixeleyez' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="saveSettings">
                                    <i class="bi bi-check-lg me-1"></i>Simpan Settings
                                </button>
                                <button type="button" class="btn btn-secondary" id="resetSettings">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Site Settings Form
    document.getElementById('siteSettingsForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = document.getElementById('saveSettings');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

        fetch('/settings/system/save', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessNotification(data.message || 'Settings berhasil disimpan!');
                
                // Update favicon globally if changed
                if (formData.get('favicon_file') && formData.get('favicon_file').size > 0) {
                    updateFaviconGlobally();
                }
                
                // Reload after 1.5 seconds to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showErrorNotification(data.message || 'Gagal menyimpan settings');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorNotification('Terjadi kesalahan saat menyimpan settings');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan Settings';
        });
    });

    // Reset Settings
    document.getElementById('resetSettings')?.addEventListener('click', function() {
        if (confirm('Reset all settings to default values?')) {
            document.getElementById('siteSettingsForm').reset();
            showSuccessNotification('Form has been reset');
        }
    });

    // File input preview for Logo
    document.getElementById('logoFileInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            console.log('Logo file selected:', file.name);
        }
    });

    // File input preview for Logo Icon
    document.getElementById('logoIconFileInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            console.log('Logo icon file selected:', file.name);
        }
    });

    // File input preview for Favicon
    document.getElementById('faviconFileInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            console.log('Favicon file selected:', file.name);
        }
    });

    // Success notification function
    function showSuccessNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'alert alert-success alert-dismissible fade show position-fixed';
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="bi bi-check-circle me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Error notification function
    function showErrorNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'alert alert-danger alert-dismissible fade show position-fixed';
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="bi bi-exclamation-triangle me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Update favicon globally
    function updateFaviconGlobally() {
        const faviconUrl = '/settings/system/favicon?t=' + new Date().getTime();
        
        // Update all favicon links
        document.querySelectorAll('link[rel*="icon"]').forEach(link => {
            link.href = faviconUrl;
        });
    }
});
</script>
@endsection


