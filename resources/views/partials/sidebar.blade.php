<aside class="pe-app-sidebar" id="sidebar">
    <div class="pe-app-sidebar-logo px-6 d-flex align-items-center position-relative">
        <!--begin::Brand Image-->
        <a href="index" class="fs-18 fw-semibold">
            <img height="30" class="pe-app-sidebar-logo-default d-none" alt="Logo" src="assets/images/logo-dark.png">
            <img height="30" class="pe-app-sidebar-logo-light d-none" alt="Logo" src="assets/images/logo-light.png">
            <img height="30" class="pe-app-sidebar-logo-minimize d-none" alt="Logo" src="assets/images/logo-md.png">
            <img height="30" class="pe-app-sidebar-logo-minimize-light d-none" alt="Logo" src="assets/images/logo-md-light.png">
            <!-- FabKin -->
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
