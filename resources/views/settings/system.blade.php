@extends('partials.layouts.master')

@section('title', 'System Setting')
@section('pagetitle', 'System Setting')

@section('content')

<!-- EMAIL SMTP Settings -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">EMAIL SMTP Settings</h5>
                <p class="text-muted mb-3">Konfigurasi pengaturan email SMTP untuk pengiriman email otomatis.</p>
            </div>
            <div class="card-body">
                <form id="smtpSettingsForm">
                    @csrf
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="bi bi-envelope me-2"></i>SMTP Configuration</h6>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">SMTP Host <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="smtp_host" value="{{ $settings['smtp_host'] ?? '' }}" placeholder="smtp.gmail.com" required>
                            <div class="form-text">Alamat server SMTP</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">SMTP Port <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="smtp_port" value="{{ $settings['smtp_port'] ?? '587' }}" placeholder="587" required>
                            <div class="form-text">Port SMTP (biasanya 587 untuk TLS, 465 untuk SSL)</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">SMTP Username <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="smtp_username" value="{{ $settings['smtp_username'] ?? '' }}" placeholder="your-email@gmail.com" required>
                            <div class="form-text">Email yang digunakan untuk mengirim</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">SMTP Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="smtp_password" value="{{ $settings['smtp_password'] ?? '' }}" placeholder="App Password" required>
                            <div class="form-text">Password atau App Password untuk email</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Encryption</label>
                            <select class="form-select" name="smtp_encryption" required>
                                <option value="tls" {{ ($settings['smtp_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ ($settings['smtp_encryption'] ?? 'tls') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="none" {{ ($settings['smtp_encryption'] ?? 'tls') == 'none' ? 'selected' : '' }}>None</option>
                            </select>
                            <div class="form-text">Jenis enkripsi yang digunakan</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">From Name</label>
                            <input type="text" class="form-control" name="smtp_from_name" value="{{ $settings['smtp_from_name'] ?? 'PLN GPS Center' }}" placeholder="PLN GPS Center">
                            <div class="form-text">Nama pengirim email</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="saveSmtpSettings">
                                    <i class="bi bi-check-lg me-1"></i>Simpan SMTP Settings
                                </button>
                                <button type="button" class="btn btn-info" id="testSmtpConnection">
                                    <i class="bi bi-send me-1"></i>Test Connection
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Google Map Settings -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Google Map Settings</h5>
                <p class="text-muted mb-3">Konfigurasi API key Google Maps untuk fitur peta dan lokasi.</p>
            </div>
            <div class="card-body">
                <form id="googleMapSettingsForm">
                    @csrf
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="bi bi-geo-alt me-2"></i>Google Maps API Configuration</h6>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Google Maps API Key <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="google_maps_api_key" value="{{ $settings['google_maps_api_key'] ?? '' }}" placeholder="AIzaSyBxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" required>
                            <div class="form-text">
                                API Key dari Google Cloud Console. 
                                <a href="https://console.cloud.google.com/apis/credentials" target="_blank" class="text-primary">Dapatkan API Key</a>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Default Latitude</label>
                            <input type="number" class="form-control" name="default_latitude" value="{{ $settings['default_latitude'] ?? '-6.2088' }}" step="any" placeholder="-6.2088">
                            <div class="form-text">Koordinat latitude default (Jakarta: -6.2088)</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Default Longitude</label>
                            <input type="number" class="form-control" name="default_longitude" value="{{ $settings['default_longitude'] ?? '106.8456' }}" step="any" placeholder="106.8456">
                            <div class="form-text">Koordinat longitude default (Jakarta: 106.8456)</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Default Zoom Level</label>
                            <input type="number" class="form-control" name="default_zoom" value="{{ $settings['default_zoom'] ?? '10' }}" min="1" max="20" placeholder="10">
                            <div class="form-text">Level zoom default (1-20)</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Map Type</label>
                            <select class="form-select" name="default_map_type">
                                <option value="roadmap" {{ ($settings['default_map_type'] ?? 'roadmap') == 'roadmap' ? 'selected' : '' }}>Roadmap</option>
                                <option value="satellite" {{ ($settings['default_map_type'] ?? 'roadmap') == 'satellite' ? 'selected' : '' }}>Satellite</option>
                                <option value="hybrid" {{ ($settings['default_map_type'] ?? 'roadmap') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                <option value="terrain" {{ ($settings['default_map_type'] ?? 'roadmap') == 'terrain' ? 'selected' : '' }}>Terrain</option>
                            </select>
                            <div class="form-text">Tipe peta default</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="saveGoogleMapSettings">
                                    <i class="bi bi-check-lg me-1"></i>Simpan Google Map Settings
                                </button>
                                <button type="button" class="btn btn-info" id="testGoogleMapApi">
                                    <i class="bi bi-geo-alt me-1"></i>Test API Key
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- WhatsApp Notification Settings -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">WHATSAPP NOTIFICATION Settings</h5>
                <p class="text-muted mb-3">Konfigurasi pengaturan WhatsApp API untuk notifikasi otomatis.</p>
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="bi bi-lightbulb me-2"></i>
                    <div>
                        <strong>Rekomendasi Provider:</strong> 
                        <a href="https://fonnte.com" target="_blank" class="alert-link fw-bold">Fonnte.com</a> - 
                        Platform WhatsApp API Indonesia dengan fitur lengkap, harga terjangkau, dan support bahasa Indonesia.
                        <br><small class="text-muted">Dapatkan API Key dengan mudah dan mulai kirim notifikasi WhatsApp dalam hitungan menit!</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="whatsappSettingsForm">
                    @csrf
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="bi bi-whatsapp me-2"></i>WhatsApp API Configuration</h6>
                        </div>
                        <div class="col-12">
                            <label class="form-label">WhatsApp API Key <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="whatsapp_api_key" value="{{ $settings['whatsapp_api_key'] ?? '' }}" placeholder="Enter your WhatsApp API Key" required>
                            <div class="form-text">
                                <strong>API Key dari WhatsApp Business API atau provider WhatsApp lainnya.</strong><br>
                                <span class="text-info">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <strong>Rekomendasi:</strong> Gunakan <a href="https://fonnte.com" target="_blank" class="text-primary fw-bold">Fonnte.com</a> - 
                                    Platform WhatsApp API Indonesia yang mudah digunakan dengan harga terjangkau.
                                </span><br>
                                <small class="text-muted">
                                    • <a href="https://developers.facebook.com/docs/whatsapp" target="_blank" class="text-primary">Facebook WhatsApp Business API</a><br>
                                    • <a href="https://fonnte.com" target="_blank" class="text-primary">Fonnte.com - WhatsApp API Indonesia</a><br>
                                    • <a href="https://wa.me/6281234567890" target="_blank" class="text-primary">Konsultasi WhatsApp API</a>
                                </small>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">WhatsApp Phone Number ID</label>
                            <input type="text" class="form-control" name="whatsapp_phone_number_id" value="{{ $settings['whatsapp_phone_number_id'] ?? '' }}" placeholder="Phone Number ID">
                            <div class="form-text">ID nomor telepon WhatsApp Business</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">WhatsApp Business Account ID</label>
                            <input type="text" class="form-control" name="whatsapp_business_account_id" value="{{ $settings['whatsapp_business_account_id'] ?? '' }}" placeholder="Business Account ID">
                            <div class="form-text">ID akun bisnis WhatsApp</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Webhook URL</label>
                            <input type="url" class="form-control" name="whatsapp_webhook_url" value="{{ $settings['whatsapp_webhook_url'] ?? '' }}" placeholder="https://yourdomain.com/webhook/whatsapp">
                            <div class="form-text">URL webhook untuk menerima notifikasi WhatsApp</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Webhook Verify Token</label>
                            <input type="text" class="form-control" name="whatsapp_webhook_verify_token" value="{{ $settings['whatsapp_webhook_verify_token'] ?? '' }}" placeholder="Your verify token">
                            <div class="form-text">Token untuk verifikasi webhook</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Default Message Template</label>
                            <textarea class="form-control" name="whatsapp_default_template" rows="3" placeholder="Hello {name}, this is a notification from PLN GPS Center...">{{ $settings['whatsapp_default_template'] ?? '' }}</textarea>
                            <div class="form-text">Template pesan default untuk notifikasi WhatsApp</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Notification Status</label>
                            <select class="form-select" name="whatsapp_notification_status">
                                <option value="active" {{ ($settings['whatsapp_notification_status'] ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ ($settings['whatsapp_notification_status'] ?? 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <div class="form-text">Status notifikasi WhatsApp</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Test Phone Number</label>
                            <input type="tel" class="form-control" name="whatsapp_test_phone" value="{{ $settings['whatsapp_test_phone'] ?? '' }}" placeholder="+6281234567890">
                            <div class="form-text">Nomor telepon untuk testing (format: +6281234567890)</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="saveWhatsappSettings">
                                    <i class="bi bi-check-lg me-1"></i>Simpan WhatsApp Settings
                                </button>
                                <button type="button" class="btn btn-info" id="testWhatsappApi">
                                    <i class="bi bi-whatsapp me-1"></i>Test WhatsApp API
                                </button>
                                <button type="button" class="btn btn-success" id="sendTestMessage">
                                    <i class="bi bi-send me-1"></i>Kirim Test Message
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informasi Cara Mendapatkan API Key -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">
                                        <i class="bi bi-question-circle me-2"></i>Cara Mendapatkan API Key
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-success">📱 Via Fonnte.com (Rekomendasi)</h6>
                                            <ol class="small">
                                                <li>Kunjungi <a href="https://fonnte.com" target="_blank" class="text-primary">fonnte.com</a></li>
                                                <li>Daftar akun gratis</li>
                                                <li>Verifikasi nomor WhatsApp</li>
                                                <li>Dapatkan API Key di dashboard</li>
                                                <li>Copy API Key ke form di atas</li>
                                            </ol>
                                            <div class="alert alert-success small">
                                                <strong>Keunggulan Fonnte:</strong> Harga mulai Rp 1.000/bulan, 
                                                support bahasa Indonesia, dokumentasi lengkap, dan customer service responsif.
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-info">🔧 Via Facebook Business API</h6>
                                            <ol class="small">
                                                <li>Buat Facebook Business Account</li>
                                                <li>Daftar WhatsApp Business API</li>
                                                <li>Verifikasi bisnis Anda</li>
                                                <li>Dapatkan API Key dari Meta</li>
                                                <li>Setup webhook dan token</li>
                                            </ol>
                                            <div class="alert alert-warning small">
                                                <strong>Catatan:</strong> Proses lebih kompleks, 
                                                memerlukan verifikasi bisnis, dan biaya lebih tinggi.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="https://fonnte.com" target="_blank" class="btn btn-outline-primary btn-sm me-2">
                                            <i class="bi bi-external-link me-1"></i>Daftar di Fonnte.com
                                        </a>
                                        <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-whatsapp me-1"></i>Konsultasi WhatsApp API
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Firebase Push Notification Settings -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">FIREBASE PUSH NOTIFICATION Settings</h5>
                <p class="text-muted mb-3">Konfigurasi Firebase untuk push notifikasi di aplikasi mobile.</p>
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <div>
                        <strong>Firebase Configuration:</strong> 
                        Setup Firebase untuk mengirim push notifikasi ke aplikasi mobile Android & iOS.
                        <br><small class="text-muted">Dapatkan konfigurasi Firebase dari <a href="https://console.firebase.google.com" target="_blank" class="alert-link">Firebase Console</a></small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="firebaseSettingsForm">
                    @csrf
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="bi bi-phone me-2"></i>Firebase Configuration</h6>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Firebase Server Key <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="firebase_server_key" value="{{ $settings['firebase_server_key'] ?? '' }}" placeholder="AAAAxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" required>
                            <div class="form-text">
                                <strong>Server Key dari Firebase Console > Project Settings > Cloud Messaging.</strong><br>
                                <span class="text-info">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <strong>Cara Mendapatkan:</strong> 
                                    <a href="https://console.firebase.google.com" target="_blank" class="text-primary">Firebase Console</a> → 
                                    Project Settings → Cloud Messaging → Server Key
                                </span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Firebase Project ID</label>
                            <input type="text" class="form-control" name="firebase_project_id" value="{{ $settings['firebase_project_id'] ?? '' }}" placeholder="your-project-id">
                            <div class="form-text">ID proyek Firebase (untuk referensi)</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Firebase App ID</label>
                            <input type="text" class="form-control" name="firebase_app_id" value="{{ $settings['firebase_app_id'] ?? '' }}" placeholder="1:123456789:android:abcdef123456">
                            <div class="form-text">ID aplikasi Firebase</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Firebase Config JSON</label>
                            <textarea class="form-control" name="firebase_config_json" rows="6" placeholder='{"apiKey":"AIza...","authDomain":"project.firebaseapp.com","projectId":"project-id","storageBucket":"project.appspot.com","messagingSenderId":"123456789","appId":"1:123456789:web:abcdef123456"}'>{{ $settings['firebase_config_json'] ?? '' }}</textarea>
                            <div class="form-text">Konfigurasi Firebase untuk aplikasi web (opsional)</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Notification Title</label>
                            <input type="text" class="form-control" name="firebase_notification_title" value="{{ $settings['firebase_notification_title'] ?? 'PLN GPS Center' }}" placeholder="PLN GPS Center">
                            <div class="form-text">Judul default untuk push notifikasi</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Notification Icon</label>
                            <input type="text" class="form-control" name="firebase_notification_icon" value="{{ $settings['firebase_notification_icon'] ?? 'ic_notification' }}" placeholder="ic_notification">
                            <div class="form-text">Icon untuk push notifikasi (nama file di assets)</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Default Notification Message</label>
                            <textarea class="form-control" name="firebase_default_message" rows="3" placeholder="You have a new notification from PLN GPS Center...">{{ $settings['firebase_default_message'] ?? '' }}</textarea>
                            <div class="form-text">Pesan default untuk push notifikasi</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Notification Status</label>
                            <select class="form-select" name="firebase_notification_status">
                                <option value="active" {{ ($settings['firebase_notification_status'] ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ ($settings['firebase_notification_status'] ?? 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <div class="form-text">Status push notifikasi Firebase</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Test Device Token</label>
                            <input type="text" class="form-control" name="firebase_test_token" value="{{ $settings['firebase_test_token'] ?? '' }}" placeholder="Device token untuk testing">
                            <div class="form-text">Token device untuk testing push notifikasi</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="saveFirebaseSettings">
                                    <i class="bi bi-check-lg me-1"></i>Simpan Firebase Settings
                                </button>
                                <button type="button" class="btn btn-info" id="testFirebaseConnection">
                                    <i class="bi bi-phone me-1"></i>Test Firebase Connection
                                </button>
                                <button type="button" class="btn btn-success" id="sendTestNotification">
                                    <i class="bi bi-bell me-1"></i>Kirim Test Notification
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informasi Cara Setup Firebase -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">
                                        <i class="bi bi-gear me-2"></i>Cara Setup Firebase Push Notification
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-success">📱 Setup Firebase Project</h6>
                                            <ol class="small">
                                                <li>Kunjungi <a href="https://console.firebase.google.com" target="_blank" class="text-primary">Firebase Console</a></li>
                                                <li>Buat project baru atau pilih existing project</li>
                                                <li>Enable Cloud Messaging di project</li>
                                                <li>Download google-services.json (Android)</li>
                                                <li>Download GoogleService-Info.plist (iOS)</li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-info">🔧 Get Server Key</h6>
                                            <ol class="small">
                                                <li>Buka Project Settings</li>
                                                <li>Pilih tab "Cloud Messaging"</li>
                                                <li>Copy "Server Key"</li>
                                                <li>Paste ke form di atas</li>
                                                <li>Test connection</li>
                                            </ol>
                                        </div>
                                    </div>
                                    <div class="alert alert-info small mt-3">
                                        <strong>💡 Tips:</strong> 
                                        Pastikan aplikasi mobile sudah terintegrasi dengan Firebase SDK. 
                                        Untuk Android gunakan google-services.json, untuk iOS gunakan GoogleService-Info.plist.
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="https://console.firebase.google.com" target="_blank" class="btn btn-outline-primary btn-sm me-2">
                                            <i class="bi bi-external-link me-1"></i>Firebase Console
                                        </a>
                                        <a href="https://firebase.google.com/docs/cloud-messaging" target="_blank" class="btn btn-outline-info btn-sm">
                                            <i class="bi bi-book me-1"></i>Firebase Documentation
                                        </a>
                                    </div>
                                </div>
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
    // Success notification function
    function showSuccessNotification(message) {
        const toastContainer = document.getElementById('toast-container') || createToastContainer();
        
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div class="toast align-items-center text-white bg-success border-0" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: 3000
        });
        
        toast.show();
        
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }

    function showErrorNotification(message) {
        const toastContainer = document.getElementById('toast-container') || createToastContainer();
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div class="toast align-items-center text-white bg-danger border-0" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-x-circle me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 5000 });
        toast.show();
        toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
    }

    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    }

    // Function to force favicon refresh globally
    function updateFaviconGlobally() {
        // Get current favicon URL with timestamp to force refresh
        const timestamp = new Date().getTime();
        const faviconUrl = `/settings/system/favicon?t=${timestamp}`;
        
        // Update all favicon links
        const faviconLinks = document.querySelectorAll('link[rel*="icon"]');
        faviconLinks.forEach(link => {
            link.href = faviconUrl;
        });
        
        // Also update document title to force browser refresh
        const originalTitle = document.title;
        document.title = originalTitle + ' ';
        setTimeout(() => {
            document.title = originalTitle;
        }, 100);
        
        // Force favicon refresh by creating new link element
        const newFavicon = document.createElement('link');
        newFavicon.rel = 'shortcut icon';
        newFavicon.href = faviconUrl;
        newFavicon.type = 'image/png';
        
        // Remove old favicon and add new one
        const oldFavicon = document.querySelector('link[rel="shortcut icon"]');
        if (oldFavicon) {
            oldFavicon.remove();
        }
        document.head.appendChild(newFavicon);
    }

    // Form submission
    document.getElementById('siteSettingsForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Check required fields
        const requiredFields = ['site_name', 'site_description', 'footer_text', 'default_theme', 'timezone'];
        const missingFields = [];
        
        requiredFields.forEach(field => {
            const value = formData.get(field);
            if (!value || value.trim() === '') {
                missingFields.push(field);
            }
        });
        
        if (missingFields.length > 0) {
            showErrorNotification('Field wajib diisi: ' + missingFields.join(', '));
            return;
        }

        // Disable button to prevent double submission
        const submitBtn = document.getElementById('saveSettings');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

        // Submit to server
        fetch('/settings/system/save', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showSuccessNotification(data.message || 'Settings berhasil disimpan!');
                
                // Force favicon refresh for all tabs
                updateFaviconGlobally();
                
                // Refresh page after delay to show updated settings
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showErrorNotification(data.message || 'Gagal menyimpan settings');
                
                if (data.errors) {
                    console.error('Validation errors:', data.errors);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorNotification('Terjadi kesalahan saat menyimpan settings');
        })
        .finally(() => {
            // Re-enable button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan Settings';
        });
    });

    // Reset button
    document.getElementById('resetSettings')?.addEventListener('click', function() {
        if (confirm('Apakah Anda yakin ingin mereset semua settings ke nilai default?')) {
            window.location.reload();
        }
    });

    // File preview for logo
    document.querySelector('input[name="logo_file"]')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // You can add preview functionality here if needed
                console.log('Logo file selected:', file.name, file.size);
            };
            reader.readAsDataURL(file);
        }
    });

    // File preview for favicon
    document.querySelector('input[name="favicon_file"]')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // You can add preview functionality here if needed
                console.log('Favicon file selected:', file.name, file.size);
            };
            reader.readAsDataURL(file);
        }
    });

    // File preview for logo icon
    document.querySelector('input[name="logo_icon_file"]')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // You can add preview functionality here if needed
                console.log('Logo icon file selected:', file.name, file.size);
            };
            reader.readAsDataURL(file);
        }
    });

    // SMTP Settings Form
    document.getElementById('smtpSettingsForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = document.getElementById('saveSmtpSettings');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

        fetch('/settings/smtp/save', {
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
                showSuccessNotification(data.message || 'SMTP settings berhasil disimpan!');
            } else {
                showErrorNotification(data.message || 'Gagal menyimpan SMTP settings');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorNotification('Terjadi kesalahan saat menyimpan SMTP settings');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan SMTP Settings';
        });
    });

    // Google Map Settings Form
    document.getElementById('googleMapSettingsForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = document.getElementById('saveGoogleMapSettings');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

        fetch('/settings/google-map/save', {
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
                showSuccessNotification(data.message || 'Google Map settings berhasil disimpan!');
            } else {
                showErrorNotification(data.message || 'Gagal menyimpan Google Map settings');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorNotification('Terjadi kesalahan saat menyimpan Google Map settings');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan Google Map Settings';
        });
    });

    // SMTP Test Modal + Handler
    function buildSmtpTestModal() {
        const existing = document.getElementById('smtpTestModal');
        if (existing) return existing;
        const modalHtml = `
<div class="modal fade" id="smtpTestModal" tabindex="-1" aria-labelledby="smtpTestModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="smtpTestModalLabel">Test SMTP Connection</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="smtpTestForm">
          <div class="mb-3">
            <label class="form-label">Kirim email test ke</label>
            <input type="email" class="form-control" name="test_email" placeholder="recipient@example.com" required>
            <div class="form-text">Masukkan alamat email tujuan untuk menerima email uji coba.</div>
          </div>
          <div class="alert alert-secondary small" role="alert">
            Pengujian akan menggunakan konfigurasi SMTP yang saat ini terisi pada form.
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-info" id="startSmtpTest"><span class="label"><i class="bi bi-send me-1"></i>Mulai Test</span></button>
      </div>
    </div>
  </div>
</div>`;
        const wrapper = document.createElement('div');
        wrapper.innerHTML = modalHtml.trim();
        document.body.appendChild(wrapper.firstChild);
        return document.getElementById('smtpTestModal');
    }

    function attachSmtpTestHandler() {
        document.getElementById('startSmtpTest')?.addEventListener('click', function () {
            const btn = this;
            const form = document.getElementById('smtpTestForm');
            if (!form) return;
            const testEmail = form.querySelector('input[name="test_email"]').value.trim();
            if (!testEmail) { showErrorNotification('Mohon isi email tujuan test'); return; }

            const smtpHost = document.querySelector('input[name="smtp_host"]').value.trim();
            const smtpPort = document.querySelector('input[name="smtp_port"]').value.trim();
            const smtpUsername = document.querySelector('input[name="smtp_username"]').value.trim();
            const smtpPassword = document.querySelector('input[name="smtp_password"]').value;
            const smtpEncryption = document.querySelector('select[name="smtp_encryption"]').value;
            const smtpFromName = document.querySelector('input[name="smtp_from_name"]').value.trim();
            if (!smtpHost || !smtpPort || !smtpUsername || !smtpPassword || !smtpEncryption || !smtpFromName) {
                showErrorNotification('Mohon lengkapi semua field SMTP terlebih dahulu');
                return;
            }

            btn.disabled = true;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menguji...';

            fetch('/settings/smtp/test', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    smtp_host: smtpHost,
                    smtp_port: Number(smtpPort),
                    smtp_username: smtpUsername,
                    smtp_password: smtpPassword,
                    smtp_encryption: smtpEncryption,
                    smtp_from_name: smtpFromName,
                    test_email: testEmail
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showSuccessNotification(data.message || 'SMTP test berhasil.');
                    const modalEl = document.getElementById('smtpTestModal');
                    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modal.hide();
                } else {
                    showErrorNotification(data.message || 'SMTP test gagal.');
                }
            })
            .catch(err => {
                console.error(err);
                showErrorNotification('Terjadi kesalahan saat menguji SMTP');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            });
        });
    }

    // Test SMTP Connection
    document.getElementById('testSmtpConnection')?.addEventListener('click', function() {
        const modalEl = buildSmtpTestModal();
        if (!modalEl) { showErrorNotification('Gagal menyiapkan modal pengujian SMTP'); return; }
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
        // ensure handler attached after modal is built
        attachSmtpTestHandler();
    });

    // Test Google Map API - client-side load and preview map
    function buildGoogleMapTestModal() {
        const existing = document.getElementById('googleMapTestModal');
        if (existing) return existing;
        const html = `
<div class="modal fade" id="googleMapTestModal" tabindex="-1" aria-labelledby="googleMapTestModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="googleMapTestModalLabel">Google Maps API Test</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2 small text-muted">Jika peta tampil (meski bertuliskan "for development purposes only"), koneksi API Maps JS berfungsi.</div>
        <div id="googleMapPreview" style="width: 100%; height: 380px; border-radius: 8px; overflow: hidden; background:#f3f6f9;"></div>
        <div id="googleMapTestStatus" class="mt-2 small"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>`;
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html.trim();
        document.body.appendChild(wrapper.firstChild);
        return document.getElementById('googleMapTestModal');
    }

    function isMapsLoaded() {
        return !!(window.google && window.google.maps && typeof window.google.maps.Map === 'function');
    }

    function loadGoogleMapsJsOnce(apiKey, onReady) {
        if (isMapsLoaded()) { onReady(); return; }
        // Check if a script is already being loaded
        const existing = document.querySelector('script[data-gmaps-loader="true"]');
        if (existing) {
            existing.addEventListener('load', function() { onReady(); }, { once: true });
            existing.addEventListener('error', function() {
                const status = document.getElementById('googleMapTestStatus');
                if (status) status.innerHTML = '<span class="text-danger">Gagal memuat Google Maps JavaScript API (script error).</span>';
            }, { once: true });
            return;
        }

        const script = document.createElement('script');
        script.setAttribute('data-gmaps-loader', 'true');
        script.src = 'https://maps.googleapis.com/maps/api/js?key=' + encodeURIComponent(apiKey) + '&v=weekly&loading=async';
        script.async = true;
        script.defer = true;
        script.onload = function() { onReady(); };
        script.onerror = function() {
            const status = document.getElementById('googleMapTestStatus');
            if (status) status.innerHTML = '<span class="text-danger">Gagal memuat Google Maps JavaScript API. Periksa API Key dan izin domain (HTTP referrer).</span>';
        };
        document.head.appendChild(script);
    }

    document.getElementById('testGoogleMapApi')?.addEventListener('click', function() {
        const apiKey = document.querySelector('input[name="google_maps_api_key"]').value.trim();
        if (!apiKey) {
            showErrorNotification('Mohon isi Google Maps API Key terlebih dahulu');
            return;
        }

        const modalEl = buildGoogleMapTestModal();
        const modal = new bootstrap.Modal(modalEl);
        modal.show();

        const latStr = document.querySelector('input[name="default_latitude"]').value || '-6.2088';
        const lngStr = document.querySelector('input[name="default_longitude"]').value || '106.8456';
        const zoomStr = document.querySelector('input[name="default_zoom"]').value || '10';
        const lat = parseFloat(latStr);
        const lng = parseFloat(lngStr);
        const zoom = parseInt(zoomStr, 10) || 10;

        const initWhenReady = function() {
            try {
                const status = document.getElementById('googleMapTestStatus');
                if (!window.google || !window.google.maps) {
                    if (status) status.innerHTML = '<span class="text-danger">Google Maps API termuat namun objek google.maps tidak tersedia.</span>';
                    return;
                }
                const initMap = function() {
                    const mapEl = document.getElementById('googleMapPreview');
                    if (!mapEl) return;
                    const map = new google.maps.Map(mapEl, {
                        center: { lat: isNaN(lat) ? -6.2088 : lat, lng: isNaN(lng) ? 106.8456 : lng },
                        zoom: zoom,
                        mapTypeId: document.querySelector('select[name="default_map_type"]').value || 'roadmap'
                    });
                    // Force resize after modal shown to avoid white map
                    setTimeout(function() {
                        google.maps.event.trigger(map, 'resize');
                        map.setCenter({ lat: isNaN(lat) ? -6.2088 : lat, lng: isNaN(lng) ? 106.8456 : lng });
                    }, 250);
                    google.maps.event.addListenerOnce(map, 'idle', function() {
                        if (status) status.innerHTML = '<span class="text-success">Peta berhasil ditampilkan. API Maps JS berfungsi.</span>';
                    });
                };
                // If modal already shown, init immediately; else wait for shown event
                if (modalEl.classList.contains('show')) {
                    initMap();
                } else {
                    modalEl.addEventListener('shown.bs.modal', function handleShown() {
                        modalEl.removeEventListener('shown.bs.modal', handleShown);
                        initMap();
                    });
                }
            } catch (e) {
                console.error(e);
                const status = document.getElementById('googleMapTestStatus');
                if (status) status.innerHTML = '<span class="text-danger">Gagal menginisialisasi peta: ' + (e && e.message ? e.message : 'unknown') + '</span>';
            }
        };

        // Load Maps JS once then init
        loadGoogleMapsJsOnce(apiKey, initWhenReady);
    });

    // WhatsApp Settings Form
    document.getElementById('whatsappSettingsForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = document.getElementById('saveWhatsappSettings');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

        fetch('/settings/whatsapp/save', {
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
                showSuccessNotification(data.message || 'WhatsApp settings berhasil disimpan!');
            } else {
                showErrorNotification(data.message || 'Gagal menyimpan WhatsApp settings');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorNotification('Terjadi kesalahan saat menyimpan WhatsApp settings');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan WhatsApp Settings';
        });
    });

    // Test WhatsApp API (Fonnte)
    document.getElementById('testWhatsappApi')?.addEventListener('click', function() {
        const apiKey = document.querySelector('input[name="whatsapp_api_key"]').value;
        const testPhone = document.querySelector('input[name="whatsapp_test_phone"]').value;
        const defaultTpl = document.querySelector('textarea[name="whatsapp_default_template"]').value || 'Hello, this is a test from PLN GPS Center.';
        if (!apiKey) { showErrorNotification('Mohon isi WhatsApp API Key terlebih dahulu'); return; }
        if (!testPhone) { showErrorNotification('Mohon isi Test Phone Number terlebih dahulu'); return; }

        const btn = this;
        btn.disabled = true;
        const original = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menguji...';

        fetch('/settings/whatsapp/test', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                whatsapp_api_key: apiKey,
                target: testPhone,
                message: defaultTpl.replace(/\{\{name\}\}/g, 'Tester'),
                country_code: '62'
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showSuccessNotification(data.message || 'Test WhatsApp berhasil dikirim.');
            } else {
                showErrorNotification(data.message || 'Test WhatsApp gagal.');
                if (data.provider_response) console.warn('Fonnte response:', data.provider_response);
            }
        })
        .catch(err => { console.error(err); showErrorNotification('Terjadi kesalahan saat menguji WhatsApp API'); })
        .finally(() => { btn.disabled = false; btn.innerHTML = original; });
    });

    // Send Test Message (Fonnte) - gunakan template default
    document.getElementById('sendTestMessage')?.addEventListener('click', function() {
        const apiKey = document.querySelector('input[name="whatsapp_api_key"]').value;
        const testPhone = document.querySelector('input[name="whatsapp_test_phone"]').value;
        const defaultTpl = document.querySelector('textarea[name="whatsapp_default_template"]').value || 'Hello, this is a test from PLN GPS Center.';
        if (!apiKey) { showErrorNotification('Mohon isi WhatsApp API Key terlebih dahulu'); return; }
        if (!testPhone) { showErrorNotification('Mohon isi nomor telepon test terlebih dahulu'); return; }

        const btn = this;
        btn.disabled = true;
        const original = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengirim...';

        fetch('/settings/whatsapp/test', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                whatsapp_api_key: apiKey,
                target: testPhone,
                message: defaultTpl.replace(/\{\{name\}\}/g, 'Tester'),
                country_code: '62'
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showSuccessNotification(data.message || 'Pesan tes WhatsApp berhasil dikirim.');
            } else {
                showErrorNotification(data.message || 'Gagal mengirim pesan tes WhatsApp.');
                if (data.provider_response) console.warn('Fonnte response:', data.provider_response);
            }
        })
        .catch(err => { console.error(err); showErrorNotification('Terjadi kesalahan saat mengirim pesan tes WhatsApp'); })
        .finally(() => { btn.disabled = false; btn.innerHTML = original; });
    });

    // Firebase Settings Form
    document.getElementById('firebaseSettingsForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = document.getElementById('saveFirebaseSettings');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

        fetch('/settings/firebase/save', {
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
                showSuccessNotification(data.message || 'Firebase settings berhasil disimpan!');
            } else {
                showErrorNotification(data.message || 'Gagal menyimpan Firebase settings');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorNotification('Terjadi kesalahan saat menyimpan Firebase settings');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan Firebase Settings';
        });
    });

    // Test Firebase Connection
    document.getElementById('testFirebaseConnection')?.addEventListener('click', function() {
        const serverKey = document.querySelector('input[name="firebase_server_key"]').value;
        if (!serverKey) {
            showErrorNotification('Mohon isi Firebase Server Key terlebih dahulu');
            return;
        }
        showErrorNotification('Fitur test Firebase connection akan segera tersedia');
    });

    // Send Test Notification
    document.getElementById('sendTestNotification')?.addEventListener('click', function() {
        const serverKey = document.querySelector('input[name="firebase_server_key"]').value;
        const testToken = document.querySelector('input[name="firebase_test_token"]').value;
        
        if (!serverKey) {
            showErrorNotification('Mohon isi Firebase Server Key terlebih dahulu');
            return;
        }
        
        if (!testToken) {
            showErrorNotification('Mohon isi Test Device Token terlebih dahulu');
            return;
        }
        
        showErrorNotification('Fitur kirim test notification akan segera tersedia');
    });
});
</script>
@endsection