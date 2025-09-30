@php($p = isset($menuIdPrefix) ? $menuIdPrefix : '')

<li class="pe-menu-title">Main</li>
<li class="pe-slide">
    <a href="/dashboard" class="pe-nav-link">
        <i class="bi bi-speedometer2 pe-nav-icon"></i>
        <span class="pe-nav-content">Dashboard</span>
    </a>
 </li>
 <li class="pe-menu-title">Application</li>
 <li class="pe-slide">
     <a href="/monitoring/trafo" class="pe-nav-link">
         <i class="bi bi-binoculars pe-nav-icon"></i>
         <span class="pe-nav-content">Monitoring Trafo</span>
     </a>
 </li>

 <li class="pe-menu-title">Setting</li>
 <li class="pe-slide pe-has-sub">
     <a href="#{{ $p }}collapseSettings" class="pe-nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="{{ $p }}collapseSettings">
         <i class="bi bi-gear pe-nav-icon"></i>
         <span class="pe-nav-content">System Setting</span>
         <i class="ri-arrow-down-s-line pe-nav-arrow"></i>
     </a>
     <ul class="pe-slide-menu collapse" id="{{ $p }}collapseSettings">
         <li class="pe-slide-item">
             <a href="/settings/system" class="pe-nav-link">
                 <i class="bi bi-gear pe-nav-icon"></i>
                 <span class="pe-nav-content">General Setting</span>
             </a>
         </li>
         <li class="pe-slide-item">
             <a href="/settings/theme" class="pe-nav-link">
                 <i class="ri ri-t-shirt-2-line pe-nav-icon"></i>
                 <span class="pe-nav-content">Theme Setting</span>
             </a>
         </li>
         <li class="pe-slide-item">
             <a href="/settings/site" class="pe-nav-link">
                 <i class="ri ri-pages-line pe-nav-icon"></i>
                 <span class="pe-nav-content">Site Setup</span>
             </a>
         </li>
         <li class="pe-slide-item">
             <a href="/settings/wa" class="pe-nav-link">
                 <i class="ri ri-whatsapp-line pe-nav-icon"></i>
                 <span class="pe-nav-content">WA Setting</span>
             </a>
         </li>
        <li class="pe-slide-item">
            <a href="/settings/notifications" class="pe-nav-link">
                <i class="bi bi-bell pe-nav-icon"></i>
                <span class="pe-nav-content">Notifikasi Template</span>
            </a>
        </li>
        <li class="pe-slide-item">
            <a href="/settings/transformator" class="pe-nav-link">
                <i class="ri ri-plug-2-line pe-nav-icon"></i>
                <span class="pe-nav-content">Transformator</span>
            </a>
        </li>
                <li class="pe-slide-item">
                    <a href="/settings/devices" class="pe-nav-link">
                        <i class="ri ri-cpu-line pe-nav-icon"></i>
                        <span class="pe-nav-content">Master Data Device</span>
                    </a>
                </li>
     </ul>
 </li>
  <li class="pe-slide pe-has-sub">
      <a href="#{{ $p }}collapseUser" class="pe-nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="{{ $p }}collapseUser">
          <i class="bi bi-people-fill pe-nav-icon"></i>
          <span class="pe-nav-content">User</span>
          <i class="ri-arrow-down-s-line pe-nav-arrow"></i>
      </a>
      <ul class="pe-slide-menu collapse" id="{{ $p }}collapseUser">
          <li class="pe-slide-item">
              <a href="/users/add" class="pe-nav-link">
                  <i class="bi bi-person-add pe-nav-icon"></i>
                  <span class="pe-nav-content">Add User</span>
              </a>
          </li>
          <li class="pe-slide-item">
              <a href="/users/master" class="pe-nav-link">
                  <i class="bi bi-clipboard-data pe-nav-icon"></i>
                  <span class="pe-nav-content">Master Data Employ</span>
              </a>
          </li>
            <li class="pe-slide-item">
                <a href="/users/group" class="pe-nav-link">
                    <i class="ri ri-user-community-line pe-nav-icon"></i>
                    <span class="pe-nav-content">User Group</span>
                </a>
            </li>
            <li class="pe-slide-item">
                <a href="/profile" class="pe-nav-link">
                    <i class="bi bi-person-circle pe-nav-icon"></i>
                    <span class="pe-nav-content">My Profile</span>
                </a>
            </li>
      </ul>
  </li>


