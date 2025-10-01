<!-- Simplebar Css -->
<link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}">
<!-- Swiper Css -->
<link href="{{ asset('assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
<!-- Nouislider Css -->
<link href="{{ asset('assets/libs/nouislider/nouislider.min.css') }}" rel="stylesheet">
<!-- Bootstrap Css -->
<link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css">
<!--icons css-->
<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
<!-- App Css-->
<link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css">
<!-- Dropdown Fix Css -->
<link href="{{ asset('assets/css/dropdown-fix.css') }}" rel="stylesheet" type="text/css">
<style>
/* Ensure icon-only sidebar hides labels and arrows */
[data-sidebar="icon"] #sidebar .pe-menu-title,
[data-sidebar="icon"] #sidebar .pe-nav-content,
[data-sidebar="icon"] #sidebar .pe-nav-arrow { display: none !important; }
[data-sidebar="icon"] #sidebar .pe-nav-icon { margin: 0 auto; }

/* FIX: Sidebar submenu text wrapping - No text cut off */
.pe-slide-menu .pe-nav-link,
.pe-slide-sub-menu .pe-nav-link {
    padding: 12px 20px 12px 52px !important;
}

.pe-slide-menu .pe-nav-link .pe-nav-content,
.pe-slide-sub-menu .pe-nav-link .pe-nav-content {
    white-space: normal !important;
    word-wrap: break-word !important;
    line-height: 1.4 !important;
    overflow: visible !important;
}
</style>