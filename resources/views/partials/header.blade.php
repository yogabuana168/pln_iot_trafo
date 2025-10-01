<!-- Begin Header -->
<header class="app-header" id="appHeader">
    <div class="container-fluid w-100">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <div class="d-inline-flex align-items-center gap-3">
                    <button type="button" class="vertical-toggle btn btn-light-light text-muted icon-btn fs-5 rounded-pill" id="toggleSidebar">
                        <i class="bi bi-arrow-bar-left header-icon"></i>
                    </button>
                    <button type="button" class="horizontal-toggle btn btn-light-light text-muted icon-btn fs-5 rounded-pill d-none" id="toggleHorizontal">
                        <i class="ri-menu-2-line header-icon"></i>
                    </button>
                </div>
            </div>
            <div class="flex-shrink-0 d-flex align-items-center gap-1">
                <button type="button" class="btn header-btn d-none d-md-block" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">
                    <i class="bi bi-search"></i>
                </button>
                <button class="btn header-btn d-none d-md-block" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                    <i class="bi bi-gear"></i>
                </button>
                <div class="dark-mode-btn" id="toggleMode">
                    <button class="btn header-btn active" id="lightModeBtn">
                        <i class="bi bi-brightness-high"></i>
                    </button>
                    <button class="btn header-btn" id="darkModeBtn">
                        <i class="bi bi-moon-stars"></i>
                    </button>
                </div>
                <div class="dropdown pe-dropdown-mega d-none d-md-block">
                    <button class="btn header-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i>
                    </button>
                    <div class="dropdown-menu dropdown-mega-md header-dropdown-menu pe-noti-dropdown-menu p-0">
                        <div class="p-3 border-bottom">
                            <h6 class="d-flex align-items-center mb-0">Notification</h6>
                        </div>
                        <div class="p-3"></div>
                    </div>
                </div>
                <div class="dropdown pe-dropdown-mega d-none d-md-block">
                    @php
                        $currentUser = DB::table('users')->where('role', '!=', 'super_admin')->orderByDesc('id')->first();
                        $headerName = $currentUser->name ?? 'User';
                        $headerEmail = $currentUser->email ?? '';
                        $avatarPath = $currentUser && !empty($currentUser->avatar_path) && file_exists(public_path($currentUser->avatar_path)) ? asset($currentUser->avatar_path) : asset('assets/images/dashboard/3d-rendering-avatar-min.png');
                    @endphp
                    <button class="header-profile-btn btn gap-1 text-start" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="header-btn btn position-relative">
                            <img src="{{ $avatarPath }}" alt="Avatar Image" class="img-fluid rounded-circle">
                            <span class="position-absolute translate-middle badge border border-light rounded-circle bg-success"><span class="visually-hidden">unread messages</span></span>
                        </span>
                        <div class="d-none d-lg-block pe-2">
                            <span class="d-block mb-0 fs-13 fw-semibold">{{ $headerName }}</span>
                            <span class="d-block mb-0 fs-12 text-muted">{{ $headerEmail }}</span>
                        </div>
                    </button>
                    <div class="dropdown-menu dropdown-mega-sm header-dropdown-menu p-3">
                        <div class="border-bottom pb-2 mb-2 d-flex align-items-center gap-2">
                            <img src="{{ $avatarPath }}" alt="Avatar Image" class="avatar-md">
                            <div>
                                <a href="javascript:void(0)">
                                    <h6 class="mb-0 lh-base">{{ $headerName }}</h6>
                                </a>
                                <p class="mb-0 fs-13 text-muted">{{ $headerEmail }}</p>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-1 border-bottom pb-1">
                            <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-1"></i> View Profile</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)"><i class="bi bi-gear me-1"></i> Settings</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)"><i class="bi bi-award me-1"></i> Subscription</a></li>
                        </ul>
                        <ul class="list-unstyled mb-1 border-bottom pb-1">
                            <li><a class="dropdown-item" href="javascript:void(0)"><i class="bi bi-clock me-1"></i> ChangLog</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)"><i class="bi bi-people me-1"></i> Team</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)"><i class="bi bi-headset me-1"></i> Support</a></li>
                        </ul>
                        <ul class="list-unstyled mb-0">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-1"></i> Sign Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- END Header -->

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 bg-transparent">
            <div class="d-flex justify-content-between align-items-center bg-body">
                <div class="d-flex align-items-center border-0 px-3">
                    <i class="bi bi-search me-2"></i>
                    <input class="d-flex w-full py-3 bg-transparent border-0 focus-ring" placeholder="Search Here.." autocomplete="off" autocorrect="off" spellcheck="false" aria-autocomplete="list" role="combobox" aria-expanded="true" type="text">
                </div>
                <button type="button" class="btn-close pe-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-body mt-4">
                <p class="font-normal mb-2">Searching For...</p>
                <span class="badge bg-light-subtle border text-body">Analytics <i class="ri-close-line"></i></span>
                <span class="badge bg-light-subtle border text-body">Project <i class="ri-close-line"></i></span>
                <span class="badge bg-light-subtle border text-body">Eccomerce <i class="ri-close-line"></i></span>
                <span class="badge bg-light-subtle border text-body">CRM <i class="ri-close-line"></i></span>
                <span class="badge bg-light-subtle border text-body">Logistics <i class="ri-close-line"></i></span>
                <span class="badge bg-light-subtle border text-body">Academy <i class="ri-close-line"></i></span>
            </div>
        </div>
    </div>
</div>
