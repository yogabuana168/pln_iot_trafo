<aside class="pe-app-sidebar horizontal-sidebar" id="horizontal-aside">
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
    <nav class="pe-app-sidebar-menu nav nav-pills">
        <ul class="pe-horizontal-menu list-unstyled" id="horizontal-menu">
            @php($menuIdPrefix = 'h-')
            @include('partials.menu-items')
        </ul>
    </nav>
</aside>
<div class="sidebar-backdrop" id="sidebar-backdrop"></div>


