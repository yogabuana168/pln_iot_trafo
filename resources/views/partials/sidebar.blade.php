<aside class="pe-app-sidebar" id="sidebar">
    <div class="pe-app-sidebar-logo px-6 d-flex align-items-center position-relative">
        <!--begin::Brand Image-->
        @php
            $logoPath = DB::table('site_settings')->where('setting_key', 'logo_path')->value('setting_value');
            $logoIconPath = DB::table('site_settings')->where('setting_key', 'logo_icon_path')->value('setting_value');
            $logoUrl = $logoPath ? asset($logoPath) : asset('assets/images/logo-dark.png');
            $logoIconUrl = $logoIconPath ? asset($logoIconPath) : ($logoPath ? asset($logoPath) : asset('assets/images/logo-dark.png'));
        @endphp
        <a href="/dashboard" class="fs-18 fw-semibold">
            <!-- Normal logo for expanded sidebar -->
            <img style="max-width: 150px; max-height: 150px; width: auto; height: auto;" class="pe-app-sidebar-logo-default d-none" alt="Logo" src="{{ $logoUrl }}">
            <img style="max-width: 150px; max-height: 150px; width: auto; height: auto;" class="pe-app-sidebar-logo-light d-none" alt="Logo" src="{{ $logoUrl }}">
            <!-- Icon logo for collapsed sidebar -->
            <img style="max-width: 40px; max-height: 40px; width: auto; height: auto;" class="pe-app-sidebar-logo-minimize d-none" alt="Logo Icon" src="{{ $logoIconUrl }}">
            <img style="max-width: 40px; max-height: 40px; width: auto; height: auto;" class="pe-app-sidebar-logo-minimize-light d-none" alt="Logo Icon" src="{{ $logoIconUrl }}">
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
