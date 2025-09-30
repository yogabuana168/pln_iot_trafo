@extends('partials.layouts.master_auth')

@section('title', 'Reset Password | FabKin Admin & Dashboards Template')

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
                            <h6 class="mb-3 mb-8 fw-medium text-center">Reset Your Password</h6>
                            <p class="text-muted text-center mb-4">Enter your email address and we'll send you a link to reset your password.</p>
                            <form method="POST" action="/forgot-password" id="forgotPasswordForm">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label for="email" class="form-label">Email Address <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Enter your email address" required>
                                        <div class="form-text">We'll send password reset instructions to this email.</div>
                                    </div>

                                    <div class="col-12 mt-8">
                                        <button type="submit" class="btn btn-primary w-full mb-5" id="resetPasswordBtn">
                                            <span class="btn-text">Send Reset Link</span>
                                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </div>
                                <p class="mb-0 fw-semibold position-relative text-center fs-12">Back to <a
                                        href="auth-signin" class="text-decoration-underline text-primary">Log in</a></p>
                            </form>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Forgot Password Form
    document.getElementById('forgotPasswordForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = document.getElementById('resetPasswordBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const spinner = submitBtn.querySelector('.spinner-border');
        
        // Show loading state
        submitBtn.disabled = true;
        btnText.textContent = 'Sending...';
        spinner.classList.remove('d-none');
        
        fetch('/forgot-password', {
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
                // Show success message
                showSuccessNotification(data.message || 'Password reset link has been sent to your email!');
                
                // Reset form
                document.getElementById('forgotPasswordForm').reset();
            } else {
                showErrorNotification(data.message || 'Failed to send reset link. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorNotification('An error occurred. Please try again.');
        })
        .finally(() => {
            // Reset button state
            submitBtn.disabled = false;
            btnText.textContent = 'Send Reset Link';
            spinner.classList.add('d-none');
        });
    });
    
    // Notification functions
    function showSuccessNotification(message) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'alert alert-success alert-dismissible fade show position-fixed';
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="bi bi-check-circle me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
    
    function showErrorNotification(message) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'alert alert-danger alert-dismissible fade show position-fixed';
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="bi bi-exclamation-triangle me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
});
</script>
@endsection
