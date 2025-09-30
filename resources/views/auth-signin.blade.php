@extends('partials.layouts.master_auth')

@section('title', 'Sign In| FabKin Admin & Dashboards Template')

@section('content')

    <!-- START -->
    <div>
        <img src="assets/images/auth/login_bg.jpg" alt="Auth Background"
            class="auth-bg light w-full h-full opacity-60 position-absolute top-0">
        <img src="assets/images/auth/auth_bg_dark.jpg" alt="Auth Background" class="auth-bg d-none dark">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100 py-10">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card mx-xxl-8">
                        <div class="card-body py-12 px-8">
                            @php
                                $logoIconPath = DB::table('site_settings')->where('setting_key', 'logo_icon_path')->value('setting_value');
                                $logoIconUrl = $logoIconPath ? asset($logoIconPath) : (($logoPath = DB::table('site_settings')->where('setting_key', 'logo_path')->value('setting_value')) ? asset($logoPath) : asset('assets/images/logo-dark.png'));
                            @endphp
                            <img src="{{ $logoIconUrl }}" alt="Logo Icon" height="40" width="40"
                                class="mb-4 mx-auto d-block" style="object-fit: contain;">
                            <h6 class="mb-3 mb-8 fw-medium text-center">Sign In to PLN GPS Center</h6>
                            <form method="POST" action="/login" id="loginForm">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label for="username" class="form-label">Username <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="username" name="username"
                                            placeholder="Enter your username" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="password" class="form-label">Password <span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Enter your password" required>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="rememberMe">
                                                <label class="form-check-label" for="rememberMe">Remember me</label>
                                            </div>
                                            <div class="form-text">
                                                <a href="/auth-reset-password"
                                                    class="link link-primary text-muted text-decoration-underline">Forgot
                                                    password?</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-8">
                                        <button type="submit" class="btn btn-primary w-full mb-4">Sign In<i
                                                class="bi bi-box-arrow-in-right ms-1 fs-16"></i></button>
                                    </div>
                                </div>
                            </form>
                            <div class="text-center">
                            </div>
                        </div>
                    </div>
                    @php
                        $footerText = class_exists('\App\Helpers\SiteSettings') ? \App\Helpers\SiteSettings::getFooterText() : (DB::table('site_settings')->where('setting_key', 'footer_text')->value('setting_value') ?? '© ' . date('Y') . ' PLN GPS Center. Design & Develop by Pixeleyez');
                    @endphp
                    <p class="position-relative text-center fs-12 mb-0">{!! $footerText !!}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<!-- Success Login Modal -->
<div class="modal fade" id="loginSuccessModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center py-12 px-8">
                <div class="mb-6">
                    <div class="position-relative d-inline-block">
                        <!-- Avatar Container -->
                        <div id="userAvatarContainer" class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto" 
                             style="width: 120px; height: 120px; overflow: hidden;">
                            <!-- Default: Initial Letter -->
                            <span id="userInitial" class="text-white fw-bold" style="font-size: 48px;"></span>
                            <!-- Or: User Photo (if exists) -->
                            <img id="userPhoto" src="" alt="User Photo" class="d-none w-100 h-100" style="object-fit: cover;">
                        </div>
                        <!-- Success Check Badge -->
                        <div class="position-absolute bottom-0 end-0 bg-success rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px;">
                            <i class="bi bi-check-lg text-white fs-20"></i>
                        </div>
                    </div>
                </div>
                
                <h4 class="fw-bold mb-3 text-success" id="greetingText">Selamat Datang!</h4>
                <h5 class="fw-semibold mb-3" id="usernameText"></h5>
                <p class="text-muted mb-6">Anda telah berhasil login ke PLN GPS Center</p>
                
                <div class="progress mb-4" style="height: 4px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                         role="progressbar" style="width: 100%"></div>
                </div>
                
                <p class="text-muted small mb-0">Redirecting to dashboard...</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Get greeting based on time
    function getGreeting() {
        const hour = new Date().getHours();
        
        if (hour >= 5 && hour < 11) {
            return 'Selamat Pagi';
        } else if (hour >= 11 && hour < 15) {
            return 'Selamat Siang';
        } else if (hour >= 15 && hour < 18) {
            return 'Selamat Sore';
        } else {
            return 'Selamat Malam';
        }
    }
    
    // Generate avatar color based on username
    function getAvatarColor(username) {
        const colors = [
            '#3498db', // Blue
            '#2ecc71', // Green
            '#e74c3c', // Red
            '#f39c12', // Orange
            '#9b59b6', // Purple
            '#1abc9c', // Turquoise
            '#e67e22', // Carrot
            '#34495e', // Dark Blue
            '#16a085', // Green Sea
            '#c0392b', // Pomegranate
            '#8e44ad', // Wisteria
            '#2980b9', // Belize Blue
            '#27ae60', // Nephritis
            '#d35400', // Pumpkin
        ];
        
        // Generate consistent color based on username
        let hash = 0;
        for (let i = 0; i < username.length; i++) {
            hash = username.charCodeAt(i) + ((hash << 5) - hash);
        }
        
        const index = Math.abs(hash) % colors.length;
        return colors[index];
    }
    
    // Setup user avatar
    function setupUserAvatar(user) {
        const avatarContainer = document.getElementById('userAvatarContainer');
        const userInitial = document.getElementById('userInitial');
        const userPhoto = document.getElementById('userPhoto');
        
        if (!avatarContainer || !userInitial || !userPhoto) return;
        
        const username = user?.name || user?.username || 'User';
        
        // Get first letter of username
        const initial = username.charAt(0).toUpperCase();
        
        // Generate color based on username
        const backgroundColor = getAvatarColor(username);
        
        // Check if user has photo
        if (user?.photo) {
            // Show user photo
            userPhoto.src = user.photo;
            userPhoto.classList.remove('d-none');
            userInitial.classList.add('d-none');
            avatarContainer.style.backgroundColor = '#f0f0f0';
        } else {
            // Show initial with colored background
            userInitial.textContent = initial;
            userInitial.classList.remove('d-none');
            userPhoto.classList.add('d-none');
            avatarContainer.style.backgroundColor = backgroundColor;
        }
    }
    
    // Login Form Handler
    document.getElementById('loginForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Signing In...';
        
        fetch('/login', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update modal content
                const greeting = getGreeting();
                
                // Get username from response
                const displayName = data.user?.name || data.user?.username || 'User';
                
                console.log('Full login response:', data);
                console.log('User data:', data.user);
                console.log('Display name:', displayName);
                
                // Update modal elements
                const greetingElement = document.getElementById('greetingText');
                const usernameElement = document.getElementById('usernameText');
                
                if (greetingElement) {
                    greetingElement.textContent = greeting + '!';
                }
                
                if (usernameElement) {
                    usernameElement.textContent = displayName;
                }
                
                // Setup user avatar with initial or photo
                setupUserAvatar(data.user);
                
                // Show success modal
                const modal = new bootstrap.Modal(document.getElementById('loginSuccessModal'));
                modal.show();
                
                // Redirect after 2 seconds
                setTimeout(() => {
                    window.location.href = data.redirect || '/dashboard';
                }, 2000);
            } else {
                // Show error notification
                showErrorNotification(data.message || 'Invalid username or password');
                
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorNotification('An error occurred. Please try again.');
            
            // Reset button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    });
    
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
});
</script>
@endsection
