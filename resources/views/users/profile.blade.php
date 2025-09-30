@extends('partials.layouts.master')

@section('title', 'Profile - ' . $user->name)
@section('pagetitle', 'User Profile')

@section('content')
    <div class="card overflow-hidden">
        <div class="card-body h-176px"
            style="background-image: url('{{ asset('assets/images/background.png') }}');background-repeat: no-repeat;background-position: right;">
        </div>
        <div class="mt-2">
            <div class="card-body p-5">
                <div class="d-flex float-end gap-2 flex-shrink-0">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="bi bi-pencil me-1"></i>Edit Profile
                    </button>
                </div>
                <div class="d-flex flex-wrap align-items-start gap-5">
                    <div class="mt-n12 flex-shrink-0">
                        <div class="position-relative d-inline-block">
                            @php
                                $avatarSrc = asset('assets/images/dashboard/3d-rendering-avatar-min.png'); // Default
                                if ($user->avatar_path && file_exists(public_path($user->avatar_path))) {
                                    $avatarSrc = asset($user->avatar_path);
                                }
                            @endphp
                            <img src="{{ $avatarSrc }}" 
                                alt="Avatar Image" class="h-128px w-128px border border-4 border-light-subtle shadow-lg">
                            <span class="position-absolute profile-dot bg-success rounded-circle">
                                <span class="visually-hidden">online status</span>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="mb-5">
                            <h5 class="mb-1">
                                {{ $user->name }}
                                        @if($user->role === 'super_admin')
                                            <i class="bi bi-patch-check-fill fs-16 ms-1 text-danger"></i>
                                        @elseif($user->role === 'admin')
                                    <i class="bi bi-patch-check-fill fs-16 ms-1 text-info"></i>
                                @endif
                            </h5>
                            <p class="text-muted fs-12 mb-0">
                                {{ $position->name ?? 'No Position' }} 
                                @if($department)
                                    • {{ $department->name }}
                                @endif
                            </p>
                        </div>
                        
                        <!-- Stats Section -->
                        <div class="w-50 border-dashed border border-1">
                            <div class="p-4 d-flex">
                                <div class="d-flex flex-column justify-content-center gap-1 w-208px text-center border-end border-dark border-opacity-20">
                                    <h5 class="mb-0 lh-1">{{ $user->role === 'admin' ? 'Admin' : 'User' }}</h5>
                                    <span class="text-muted lh-sm fs-12">Role</span>
                                </div>
                                <div class="d-flex flex-column justify-content-center gap-1 w-208px text-center border-end border-dark border-opacity-20">
                                    <h5 class="mb-0 lh-1">{{ $user->status ? 'Active' : 'Inactive' }}</h5>
                                    <span class="text-muted lh-sm fs-12">Status</span>
                                </div>
                                <div class="d-flex flex-column justify-content-center gap-1 w-208px text-center">
                                    <h5 class="mb-0 lh-1">{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('M Y') : 'Unknown' }}</h5>
                                    <span class="text-muted lh-sm fs-12">Joined</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="row g-5 mt-2">
                            <div class="col-md-4 col-xl-3">
                                <div class="d-flex gap-2">
                                    <i class="bi bi-envelope fs-16"></i>
                                    <p class="text-muted mb-2">Email</p>
                                </div>
                                <h6 class="mb-0">{{ $user->email }}</h6>
                            </div>
                            <div class="col-md-4 col-xl-3">
                                <div class="d-flex gap-2">
                                    <i class="bi bi-telephone fs-16"></i>
                                    <p class="text-muted mb-2">Phone</p>
                                </div>
                                <h6 class="mb-0">{{ $user->phone ?? 'Not provided' }}</h6>
                            </div>
                            <div class="col-md-4 col-xl-3">
                                <div class="d-flex gap-2">
                                    <i class="bi bi-building fs-16"></i>
                                    <p class="text-muted mb-2">Department</p>
                                </div>
                                <h6 class="mb-0">{{ $department->name ?? 'Not assigned' }}</h6>
                            </div>
                            <div class="col-md-4 col-xl-3">
                                <div class="d-flex gap-2">
                                    <i class="bi bi-people fs-16"></i>
                                    <p class="text-muted mb-2">Group</p>
                                </div>
                                <h6 class="mb-0">{{ $userGroup->name ?? 'Not assigned' }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Job Information -->
        <div class="col-12">
            <div class="card">
                <div class="card-header align-items-center">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title mb-0">Job Information</h5>
                        <button class="btn btn-outline-light text-muted btn-sm" data-bs-toggle="modal" data-bs-target="#editJobInfoModal">
                            <i class="bi bi-pencil"></i>Edit Info
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-box table-responsive">
                        <table class="table text-nowrap align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Employee Code</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Group</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Last Login</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary">{{ $user->employee_code ?? 'N/A' }}</span></td>
                                    <td>{{ $position->name ?? 'Not assigned' }}</td>
                                    <td>{{ $department->name ?? 'Not assigned' }}</td>
                                    <td><span class="badge bg-info-subtle text-info">{{ ucfirst($user->role) }}</span></td>
                                    <td>{{ $userGroup->name ?? 'Not assigned' }}</td>
                                    <td>
                                        @if($user->status)
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Never' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Skills Overview -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">System Access</h5>
                    <div class="dropdown">
                        <a href="javascript:void(0)" data-bs-toggle="dropdown" class="text-muted">
                            <i class="bi bi-three-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:void(0)">View</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)">Edit</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-medium">Dashboard Access</span>
                                <span class="fw-medium">{{ in_array($user->role, ['super_admin', 'admin']) ? '100%' : '80%' }}</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar progress-bar-striped progress-bar-animated {{ in_array($user->role, ['super_admin', 'admin']) ? 'bg-success' : 'bg-primary' }}"
                                    style="width: {{ in_array($user->role, ['super_admin', 'admin']) ? '100' : '80' }}%"></div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-medium">User Management</span>
                                <span class="fw-medium">{{ in_array($user->role, ['super_admin', 'admin']) ? '100%' : '0%' }}</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar progress-bar-striped progress-bar-animated {{ in_array($user->role, ['super_admin', 'admin']) ? 'bg-success' : 'bg-secondary' }}"
                                    style="width: {{ in_array($user->role, ['super_admin', 'admin']) ? '100' : '0' }}%"></div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-medium">Settings Access</span>
                                <span class="fw-medium">{{ $user->role === 'super_admin' ? '100%' : ($user->role === 'admin' ? '80%' : '50%') }}</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar progress-bar-striped progress-bar-animated {{ $user->role === 'super_admin' ? 'bg-danger' : ($user->role === 'admin' ? 'bg-success' : 'bg-warning') }}"
                                    style="width: {{ $user->role === 'super_admin' ? '100' : ($user->role === 'admin' ? '80' : '50') }}%"></div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-medium">Reports</span>
                                <span class="fw-medium">{{ in_array($user->role, ['super_admin', 'admin', 'user']) ? '90%' : '30%' }}</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar progress-bar-striped progress-bar-animated {{ in_array($user->role, ['super_admin', 'admin', 'user']) ? 'bg-info' : 'bg-secondary' }}"
                                    style="width: {{ in_array($user->role, ['super_admin', 'admin', 'user']) ? '90' : '30' }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Activity Log -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Activity</h5>
                    <div class="dropdown">
                        <a href="javascript:void(0)" data-bs-toggle="dropdown" class="text-muted">
                            <i class="bi bi-three-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:void(0)">View All</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)">Export</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div data-simplebar class="px-5 mx-n5" style="height: 355px;">
                        <div class="d-flex gap-3 border p-4 rounded mb-4">
                            <img src="{{ $avatarSrc }}"
                                class="avatar-lg flex-shrink-0 rounded-circle" alt="Avatar Image">
                            <div class="flex-grow-1">
                                <span class="float-end fs-12 fw-medium">{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('M d, Y') : 'Unknown' }}</span>
                                <h6 class="mb-1 fs-15">{{ $user->name }}</h6>
                                <span class="text-muted">
                                    <i class="bi bi-person-plus text-success me-1 fs-16"></i>
                                    Account created successfully
                                </span>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-3 border p-4 rounded mb-4">
                            <img src="{{ $avatarSrc }}"
                                class="avatar-lg flex-shrink-0 rounded-circle" alt="Avatar Image">
                            <div class="flex-grow-1">
                                <span class="float-end fs-12 fw-medium">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('M d, Y') : 'Never' }}</span>
                                <h6 class="mb-1 fs-15">System</h6>
                                <span class="text-muted">
                                    <i class="bi bi-box-arrow-in-right text-primary me-1 fs-16"></i>
                                    {{ $user->last_login_at ? 'Last login' : 'Never logged in' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-3 border p-4 rounded mb-4">
                            <img src="{{ $avatarSrc }}"
                                class="avatar-lg flex-shrink-0 rounded-circle" alt="Avatar Image">
                            <div class="flex-grow-1">
                                <span class="float-end fs-12 fw-medium">Today</span>
                                <h6 class="mb-1 fs-15">Profile Updated</h6>
                                <span class="text-muted">
                                    <i class="bi bi-pencil text-info me-1 fs-16"></i>
                                    Profile information updated
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Team Members -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card mb-6">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Team Members{{ isset($userGroup->name) ? ' - ' . $userGroup->name : '' }}</h5>
                    <div class="dropdown">
                        <a href="javascript:void(0)" data-bs-toggle="dropdown" class="text-muted">
                            <i class="bi bi-three-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:void(0)">View All</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)">Add Member</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div data-simplebar style="height: 395px;">
                        @if(empty($user->group_id))
                            <div class="p-5 text-center text-muted">
                                <i class="bi bi-people fs-48 mb-3 d-block"></i>
                                <p>User belum tergabung dalam group manapun.</p>
                                <small>Silakan assign user ke salah satu group pada halaman User Group.</small>
                            </div>
                        @else
                        @php
                            $teamMembers = \DB::table('users')
                                ->where('group_id', $user->group_id)
                                ->where('id', '!=', $user->id)
                                ->select('id','name','email','position_id','avatar_path','role')
                                ->orderBy('name')
                                ->limit(10)
                                ->get();
                        @endphp
                        
                        @forelse($teamMembers as $member)
                            @php
                                $memberPosition = \DB::table('positions')->where('id', $member->position_id)->first();
                            @endphp
                            <div class="d-flex gap-3 border-bottom p-5">
                                @php
                                    $memberAvatar = ($member->avatar_path ?? null) && file_exists(public_path($member->avatar_path)) ? asset($member->avatar_path) : asset('assets/images/dashboard/3d-rendering-avatar-min.png');
                                @endphp
                                <img src="{{ $memberAvatar }}"
                                    class="avatar-lg flex-shrink-0 rounded-circle" alt="Avatar Image">
                                <div class="flex-grow-1">
                                    <button class="btn btn-light-light text-muted btn-sm rounded float-end">Message</button>
                                    <h6 class="mb-1 fs-15">{{ $member->name }}</h6>
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        <span class="badge bg-primary-subtle text-primary">{{ $memberPosition->name ?? 'No Position' }}</span>
                                        <span class="badge bg-info-subtle text-info">{{ ucwords(str_replace('_',' ', $member->role ?? 'user')) }}</span>
                                    </div>
                                    <div class="mt-1">
                                        <small class="text-muted">{{ $member->email }}</small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-center text-muted">
                                <i class="bi bi-people fs-48 mb-3 d-block"></i>
                                <p>No team members found</p>
                            </div>
                        @endforelse
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close icon-btn-sm" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ri-close-large-line fw-semibold"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" name="phone" value="{{ $user->phone }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Position</label>
                            <select class="form-select" name="position_id">
                                <option value="">Pilih Posisi</option>
                                @foreach(($positions ?? []) as $pos)
                                    <option value="{{ $pos->id }}" {{ $user->position_id == $pos->id ? 'selected' : '' }}>{{ $pos->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="department_id">
                                <option value="">Pilih Departemen</option>
                                @foreach(($departments ?? []) as $dep)
                                    <option value="{{ $dep->id }}" {{ $user->department_id == $dep->id ? 'selected' : '' }}>{{ $dep->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Group</label>
                            <select class="form-select" name="group_id">
                                <option value="">Pilih Group</option>
                                @foreach(($groups ?? []) as $g)
                                    <option value="{{ $g->id }}" {{ $user->group_id == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Avatar</label>
                            <input type="file" class="form-control" name="avatar" accept="image/*">
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Upload avatar baru untuk mengganti avatar saat ini. Kosongkan jika tidak ingin mengubah.
                                </small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveEditProfile">Save Changes</button>
            </div>
        </div>
    </div>
</div>
<!--End Edit Profile Modal-->

<!-- Edit Job Info Modal -->
<div class="modal fade" id="editJobInfoModal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editJobInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editJobInfoModalLabel">Edit Job Information</h5>
                <button type="button" class="btn-close icon-btn-sm" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ri-close-large-line fw-semibold"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="editJobInfoForm">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Employee Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="employee_code" value="{{ $user->employee_code }}" required>
                            <div class="form-text">Unique employee identification code</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Position <span class="text-danger">*</span></label>
                            <select class="form-select" name="position_id" required>
                                <option value="">Pilih Posisi</option>
                                @foreach(($positions ?? []) as $pos)
                                    <option value="{{ $pos->id }}" {{ $user->position_id == $pos->id ? 'selected' : '' }}>{{ $pos->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Department <span class="text-danger">*</span></label>
                            <select class="form-select" name="department_id" required>
                                <option value="">Pilih Departemen</option>
                                @foreach(($departments ?? []) as $dep)
                                    <option value="{{ $dep->id }}" {{ $user->department_id == $dep->id ? 'selected' : '' }}>{{ $dep->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" name="role" required>
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                @if($user->role == 'super_admin')
                                    <option value="super_admin" selected>Super Admin</option>
                                @endif
                            </select>
                            <div class="form-text">User role determines access permissions</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Group <span class="text-danger">*</span></label>
                            <select class="form-select" name="group_id" required>
                                <option value="">Pilih Group</option>
                                @foreach(($groups ?? []) as $g)
                                    <option value="{{ $g->id }}" {{ $user->group_id == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <div class="form-text">Active users can login to the system</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveEditJobInfo">
                    <i class="bi bi-check-lg me-1"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
<!--End Edit Job Info Modal-->
@endsection

@section('js')
    <script type="module" src="{{ asset('assets/js/app.js') }}"></script>
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

            // Edit Profile Form Submission
            document.getElementById('saveEditProfile')?.addEventListener('click', function(){
                const form = document.getElementById('editProfileForm');
                const formData = new FormData(form);
                
                // Check required fields
                const requiredFields = ['name', 'email', 'phone'];
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
                const submitBtn = document.getElementById('saveEditProfile');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

                // Submit to server
                fetch('/profile/update', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success notification
                        showSuccessNotification(data.message || 'Profile berhasil diperbarui!');
                        
                        // Close modal
                        const modalEl = document.getElementById('editProfileModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        modal?.hide();

                        // Refresh page to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Show error notification
                        showErrorNotification(data.message || 'Gagal memperbarui profile');
                        
                        // Log validation errors if any
                        if (data.errors) {
                            console.error('Validation errors:', data.errors);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorNotification('Terjadi kesalahan saat memperbarui profile');
                })
                .finally(() => {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Save Changes';
                });
            });

            // Edit Job Info Form Submission
            document.getElementById('saveEditJobInfo')?.addEventListener('click', function(){
                const form = document.getElementById('editJobInfoForm');
                const formData = new FormData(form);
                
                // Check required fields
                const requiredFields = ['employee_code', 'position_id', 'department_id', 'role', 'group_id', 'status'];
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
                const submitBtn = document.getElementById('saveEditJobInfo');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

                // Submit to server
                fetch('/profile/update-job-info', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success notification
                        showSuccessNotification(data.message || 'Job information berhasil diperbarui!');
                        
                        // Close modal
                        const modalEl = document.getElementById('editJobInfoModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        modal?.hide();

                        // Refresh page to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Show error notification
                        showErrorNotification(data.message || 'Gagal memperbarui job information');
                        
                        // Log validation errors if any
                        if (data.errors) {
                            console.error('Validation errors:', data.errors);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorNotification('Terjadi kesalahan saat memperbarui job information');
                })
                .finally(() => {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Save Changes';
                });
            });
        });
    </script>
@endsection
