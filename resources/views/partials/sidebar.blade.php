<style>
/* CRITICAL FIX: Logo display - Force only 1 logo at a time */
/* Maximum specificity to override ALL other CSS rules */

/* Step 1: FORCE HIDE ALL LOGOS (Base rule) */
#sidebar .pe-app-sidebar-logo .pe-app-sidebar-logo-default,
#sidebar .pe-app-sidebar-logo .pe-app-sidebar-logo-light,
#sidebar .pe-app-sidebar-logo .pe-app-sidebar-logo-minimize,
#sidebar .pe-app-sidebar-logo .pe-app-sidebar-logo-minimize-light {
    display: none !important;
}

/* Step 2: SHOW ONLY THE CORRECT ONE */

/* === SIDEBAR COLOR: LIGHT or DEFAULT === */
/* Expanded State */
html[data-sidebar-color="default"]:not([data-sidebar="icon"]) #sidebar .pe-app-sidebar-logo .pe-app-sidebar-logo-default,
html[data-sidebar-color="light"]:not([data-sidebar="icon"]) #sidebar .pe-app-sidebar-logo .pe-app-sidebar-logo-default,
html:not([data-sidebar-color]):not([data-sidebar="icon"]) #sidebar .pe-app-sidebar-logo .pe-app-sidebar-logo-default {
    display: block !important;
}

/* Collapsed State (Icon Mode) */
html[data-sidebar-color="default"][data-sidebar="icon"] #sidebar .pe-app-sidebar-logo .pe-app-sidebar-logo-minimize,
html[data-sidebar-color="light"][data-sidebar="icon"] #sidebar .pe-app-sidebar-logo .pe-app-sidebar-logo-minimize,
html:not([data-sidebar-color])[data-sidebar="icon"] #sidebar .pe-app-sidebar-logo .pe-app-sidebar-logo-minimize {
    display: block !important;
}

/* === SIDEBAR COLOR: DARK === */
/* Expanded State */
html[data-sidebar-color="dark"]:not([data-sidebar="icon"]) #sidebar .pe-app-sidebar-logo .pe-app-sidebar-logo-light {
    display: block !important;
}

/* Collapsed State (Icon Mode) */
html[data-sidebar-color="dark"][data-sidebar="icon"] #sidebar .pe-app-sidebar-logo .pe-app-sidebar-logo-minimize-light {
    display: block !important;
}

/* Extra: Handle data-sidebar="default" case */
html[data-sidebar="default"] #sidebar .pe-app-sidebar-logo .pe-app-sidebar-logo-minimize,
html[data-sidebar="default"] #sidebar .pe-app-sidebar-logo .pe-app-sidebar-logo-minimize-light {
    display: none !important;
}
</style>
<aside class="pe-app-sidebar" id="sidebar">
    <div class="pe-app-sidebar-logo px-6 d-flex align-items-center position-relative">
        <!--begin::Brand Image-->
        @php
            $logoPath = DB::table('site_settings')->where('setting_key', 'logo_path')->value('setting_value');
            $logoIconPath = DB::table('site_settings')->where('setting_key', 'logo_icon_path')->value('setting_value');
            $logoUrl = $logoPath ? asset($logoPath) : asset('assets/images/logo-dark.png');
            $logoIconUrl = $logoIconPath ? asset($logoIconPath) : ($logoPath ? asset($logoPath) : asset('assets/images/logo-dark.png'));
        @endphp
        <a href="/dashboard" class="fs-18 fw-semibold d-flex align-items-center">
            <!-- Normal logo for expanded sidebar - Light theme -->
            <img style="max-width: 150px; max-height: 150px; width: auto; height: auto;" 
                 class="pe-app-sidebar-logo-default d-none" 
                 alt="Logo" 
                 src="{{ $logoUrl }}">
            <!-- Normal logo for expanded sidebar - Dark theme -->
            <img style="max-width: 150px; max-height: 150px; width: auto; height: auto;" 
                 class="pe-app-sidebar-logo-light d-none" 
                 alt="Logo" 
                 src="{{ $logoUrl }}">
            <!-- Icon logo for collapsed sidebar - Light theme -->
            <img style="max-width: 40px; max-height: 40px; width: auto; height: auto;" 
                 class="pe-app-sidebar-logo-minimize d-none" 
                 alt="Logo Icon" 
                 src="{{ $logoIconUrl }}">
            <!-- Icon logo for collapsed sidebar - Dark theme -->
            <img style="max-width: 40px; max-height: 40px; width: auto; height: auto;" 
                 class="pe-app-sidebar-logo-minimize-light d-none" 
                 alt="Logo Icon" 
                 src="{{ $logoIconUrl }}">
        </a>
        <!--end::Brand Image-->
    </div> 
    <nav class="pe-app-sidebar-menu nav nav-pills" data-simplebar id="sidebar-simplebar">
        <ul class="pe-main-menu list-unstyled">
            @php($menuIdPrefix = '')
            @include('partials.menu-items')
        </ul>
    </nav>
</aside>
