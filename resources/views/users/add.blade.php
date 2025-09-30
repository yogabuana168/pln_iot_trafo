@extends('partials.layouts.master')

@section('title', 'Add User')
@section('pagetitle', 'Add User')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"> Basic Datatable </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Tabel data pengguna menggunakan DataTables Bootstrap 5 (search, sort, pagination).</p>

                    <div id="actionBar" class="mb-3">
                        <div class="input-group">
                            <button class="btn btn-primary" type="button" id="applyAction" data-bs-toggle="modal" data-bs-target="#confirmActionModal">Apply</button>
                            <select class="form-select" id="bulkAction" aria-label="Bulk action selector">
                                <option value="">No Action</option>
                                <option value="status">Status</option>
                                <option value="delete">Delete</option>
                            </select>
                        </div>
                    </div>
                    <table id="users_table" class="table table-sm table-nowrap table-striped table-bordered align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th style="width:50px">
                                        <div class="form-check form-check-sm mb-0">
                                            <input class="form-check-input" type="checkbox" value="" id="selectAllUsers">
                                            <label class="form-check-label" for="selectAllUsers"></label>
                                        </div>
                                    </th>
                                    <th style="width:60px">No</th>
                                    <th>Nama</th>
                                    <th>Posisi</th>
                                    <th>Departemen</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users ?? [] as $i => $u)
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-sm mb-0"> 
                                                <input class="form-check-input" type="checkbox" value="{{ $u->id }}" id="select_{{ $u->id }}">
                                                <label class="form-check-label" for="select_{{ $u->id }}"></label>
                                            </div>
                                        </td>
                                                    <td>{{ $i+1 }}</td>
                                                    <td>
                                                        <a href="/profile/{{ $u->id }}" class="text-decoration-none">
                                                            {{ $u->name }}
                                                        </a>
                                                    </td>
                                        <td>{{ $u->position_name ?? '-' }}</td>
                                        <td>{{ $u->department_name ?? '-' }}</td>
                                        <td class="text-capitalize">{{ $u->role }}</td>
                                        <td>
                                            @if((int)($u->status) === 1)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Belum ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- start:: Centered Modal -->
    <div class="modal fade" id="confirmActionModal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmActionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmActionLabel">Do you want to perform this action?</h5>
                    <button type="button" class="btn-close icon-btn-sm" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ri-close-large-line fw-semibold"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0" id="confirmMessage">You are about to apply action to selected rows.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="confirmYes">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <!--End modal-->
    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserLabel">Create User</h5>
                    <button type="button" class="btn-close icon-btn-sm" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ri-close-large-line fw-semibold"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createUserForm">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Employee Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="employee_code" placeholder="E.g. NIP/NIK" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" id="emailInput" placeholder="name@company.com" required>
                                <div class="invalid-feedback" id="emailError"></div>
                                <div class="valid-feedback" id="emailSuccess"></div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" placeholder="Full name" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="phone" id="phoneInput" placeholder="08xxxxxxxxxx" required>
                                <div class="invalid-feedback" id="phoneError"></div>
                                <div class="valid-feedback" id="phoneSuccess"></div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Position <span class="text-danger">*</span></label>
                                <select class="form-select" name="position_id" required>
                                    <option value="">Pilih Posisi</option>
                                    @foreach(($positions ?? []) as $pos)
                                        <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Department <span class="text-danger">*</span></label>
                                <select class="form-select" name="department_id" required>
                                    <option value="">Pilih Departemen</option>
                                    @foreach(($departments ?? []) as $dep)
                                        <option value="{{ $dep->id }}">{{ $dep->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                            <select class="form-select" name="role" required>
                                                <option value="">Pilih Role</option>
                                                <option value="admin">Admin</option>
                                                <option value="user">User</option>
                                            </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Group <span class="text-danger">*</span></label>
                                <select class="form-select" name="user_group_id" required>
                                    <option value="">Pilih Group</option>
                                    @foreach(($groups ?? []) as $g)
                                        <option value="{{ $g->id }}">{{ $g->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password" autocomplete="new-password" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password_confirmation" autocomplete="new-password" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Avatar</label>
                                <input type="file" class="form-control" name="avatar" accept="image/*">
                                <div class="form-text">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Avatar bersifat opsional. Jika tidak diupload, akan menggunakan avatar default.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveCreateUser">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!--End Create User Modal-->
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = new DataTable('#users_table', {
                responsive: true,
                pageLength: 10,
                lengthChange: true,
                order: [[1, 'asc']],
                columnDefs: [
                    { orderable: false, targets: 0 }
                ],
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                pagingType: 'full_numbers',
                language: {
                    paginate: {
                        previous: '<i class="ri-arrow-left-s-line fw-semibold"></i>',
                        next: '<i class="ri-arrow-right-s-line fw-semibold"></i>'
                    }
                },
                dom: '<"row mb-3 align-items-center"<"col-12 col-xl-6 dt-actions"><"col-12 col-xl-6 text-xl-end"f>>t<"row align-items-center mt-3"<"col-12 col-xl-6"l><"col-12 col-xl-6 text-xl-end"p>>',
                createdRow: function(row) {
                    row.querySelectorAll('td, th').forEach(function(cell){
                        cell.classList.add('py-2','px-2');
                    });
                }
            });

            function applyPaginationClass() {
                const ul = document.querySelector('#users_table_paginate ul.pagination');
                if (ul) {
                    ul.classList.remove('pagination-dark', 'pagination-light');
                    ul.classList.add('pagination', 'pagination-primary', 'pagination-sm', 'mb-0');
                }
            }

            applyPaginationClass();
            // ensure header cells have same height
            document.querySelectorAll('#users_table thead th').forEach(function(cell){
                cell.classList.add('py-2','px-2');
            });
            table.on('draw', () => {
                applyPaginationClass();
                syncHeaderCheckbox();
                // shrink controls
                document.querySelector('.dataTables_filter input')?.classList.add('form-control-sm');
                document.querySelector('.dataTables_length select')?.classList.add('form-select-sm');
            });

            // Move our action bar into the DataTables header row (left side), aligning with search on the right
            const dtActions = document.querySelector('.dt-actions');
            const actionBar = document.getElementById('actionBar');
            if (dtActions && actionBar) {
                dtActions.append(actionBar);
            }

            // Inject Create User button at the end of the search block
            function placeCreateButton() {
                const filter = document.querySelector('.dataTables_filter');
                if (!filter) return;
                if (document.getElementById('createUserBtn')) return;
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.id = 'createUserBtn';
                btn.className = 'btn btn-primary btn-sm ms-2';
                btn.textContent = 'Create User';
                btn.setAttribute('data-bs-toggle', 'modal');
                btn.setAttribute('data-bs-target', '#createUserModal');
                filter.appendChild(btn);
            }
            placeCreateButton();
            table.on('draw', placeCreateButton);

            // Select all handler
            const selectAll = document.getElementById('selectAllUsers');
            const tbody = document.querySelector('#users_table tbody');

            function setPageCheckboxes(checked) {
                table.rows({ page: 'current' }).nodes().to$().each(function () {
                    const cb = this.querySelector('input.form-check-input[type="checkbox"]');
                    if (cb) cb.checked = checked;
                });
            }

            function syncHeaderCheckbox() {
                const boxes = document.querySelectorAll('#users_table tbody input.form-check-input[type="checkbox"]');
                const total = boxes.length;
                const checked = Array.from(boxes).filter(cb => cb.checked).length;
                selectAll.checked = total > 0 && checked === total;
            }

            selectAll.addEventListener('change', () => {
                setPageCheckboxes(selectAll.checked);
            });

            tbody.addEventListener('change', (e) => {
                if (e.target && e.target.matches('input.form-check-input[type="checkbox"]')) {
                    syncHeaderCheckbox();
                }
            });

            const applyBtn = document.getElementById('applyAction');
            const confirmBtn = document.getElementById('confirmYes');
            const actionSelect = document.getElementById('bulkAction');
            const msg = document.getElementById('confirmMessage');

            applyBtn.addEventListener('click', () => {
                const action = actionSelect.value || 'none';
                const selected = Array.from(document.querySelectorAll('#users_table tbody .form-check-input:checked'));
                msg.textContent = `Action: ${action.toUpperCase()} | Selected: ${selected.length} row(s)`;
            });

            confirmBtn.addEventListener('click', () => {
                const action = actionSelect.value;
                const selected = Array.from(document.querySelectorAll('#users_table tbody .form-check-input:checked'));
                if (!action || selected.length === 0) return;

                // Get user IDs
                const userIds = selected.map(chk => chk.value);

                if (action === 'delete') {
                    // Send delete request to backend
                    fetch('/users/bulk-delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ user_ids: userIds })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove rows from table
                            selected.forEach(chk => {
                                const row = chk.closest('tr');
                                table.row(row).remove();
                            });
                            table.draw(false);
                            
                            // Show success notification
                            showNotification('success', data.message || 'Users deleted successfully');
                            
                            // Uncheck header checkbox
                            document.getElementById('select_all').checked = false;
                        } else {
                            showNotification('error', data.message || 'Failed to delete users');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('error', 'An error occurred while deleting users');
                    });
                }

                if (action === 'status') {
                    // Send status update request to backend
                    fetch('/users/bulk-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ user_ids: userIds })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update status badges
                            selected.forEach(chk => {
                                const userId = chk.value;
                                const row = chk.closest('tr');
                                const badgeCell = row.querySelector('td:last-child');
                                const isActive = badgeCell?.querySelector('.bg-success');
                                
                                if (isActive) {
                                    badgeCell.innerHTML = '<span class="badge bg-secondary">Inactive</span>';
                                } else {
                                    badgeCell.innerHTML = '<span class="badge bg-success">Active</span>';
                                }
                            });
                            
                            showNotification('success', data.message || 'User status updated successfully');
                            
                            // Uncheck all
                            selected.forEach(chk => chk.checked = false);
                            document.getElementById('select_all').checked = false;
                        } else {
                            showNotification('error', data.message || 'Failed to update user status');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('error', 'An error occurred while updating status');
                    });
                }

                // close modal
                const modalEl = document.getElementById('confirmActionModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal?.hide();
            });

            // Notification function
            function showNotification(type, message) {
                const notification = document.createElement('div');
                notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
                notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                notification.innerHTML = `
                    <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 5000);
            }

            // Smart validation for email and phone
            let emailValidationTimeout;
            let phoneValidationTimeout;

            // Email validation
            document.getElementById('emailInput')?.addEventListener('input', function() {
                clearTimeout(emailValidationTimeout);
                const email = this.value.trim();
                
                if (email.length > 0) {
                    emailValidationTimeout = setTimeout(() => {
                        checkEmailDuplicate(email);
                    }, 800); // Debounce 800ms
                } else {
                    clearEmailValidation();
                }
            });

            // Phone validation
            document.getElementById('phoneInput')?.addEventListener('input', function() {
                clearTimeout(phoneValidationTimeout);
                const phone = this.value.trim();
                
                if (phone.length > 0) {
                    phoneValidationTimeout = setTimeout(() => {
                        checkPhoneDuplicate(phone);
                    }, 800); // Debounce 800ms
                } else {
                    clearPhoneValidation();
                }
            });

            function checkEmailDuplicate(email) {
                fetch(`/users/check-duplicate?email=${encodeURIComponent(email)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.ok && data.data.email.exists) {
                            showFieldError('emailInput', 'emailError', data.data.email.message);
                        } else {
                            showFieldSuccess('emailInput', 'emailSuccess', 'Email tersedia');
                        }
                    })
                    .catch(error => {
                        console.error('Email validation error:', error);
                    });
            }

            function checkPhoneDuplicate(phone) {
                fetch(`/users/check-duplicate?phone=${encodeURIComponent(phone)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.ok && data.data.phone.exists) {
                            showFieldError('phoneInput', 'phoneError', data.data.phone.message);
                        } else {
                            showFieldSuccess('phoneInput', 'phoneSuccess', 'Nomor HP tersedia');
                        }
                    })
                    .catch(error => {
                        console.error('Phone validation error:', error);
                    });
            }

            function showFieldError(inputId, errorId, message) {
                const input = document.getElementById(inputId);
                const errorDiv = document.getElementById(errorId);
                const successDiv = document.getElementById(inputId.replace('Input', 'Success'));
                
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                errorDiv.textContent = message;
                successDiv.textContent = '';
            }

            function showFieldSuccess(inputId, successId, message) {
                const input = document.getElementById(inputId);
                const errorDiv = document.getElementById(inputId.replace('Input', 'Error'));
                const successDiv = document.getElementById(successId);
                
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                errorDiv.textContent = '';
                successDiv.textContent = message;
            }

            function clearEmailValidation() {
                const input = document.getElementById('emailInput');
                const errorDiv = document.getElementById('emailError');
                const successDiv = document.getElementById('emailSuccess');
                
                input.classList.remove('is-valid', 'is-invalid');
                errorDiv.textContent = '';
                successDiv.textContent = '';
            }

            function clearPhoneValidation() {
                const input = document.getElementById('phoneInput');
                const errorDiv = document.getElementById('phoneError');
                const successDiv = document.getElementById('phoneSuccess');
                
                input.classList.remove('is-valid', 'is-invalid');
                errorDiv.textContent = '';
                successDiv.textContent = '';
            }

            // Enhanced validation for Create User
            document.getElementById('saveCreateUser')?.addEventListener('click', function(){
                const form = document.getElementById('createUserForm');
                const formData = new FormData(form);
                
                // Check required fields
                const requiredFields = ['employee_code', 'name', 'email', 'phone', 'position_id', 'department_id', 'role', 'user_group_id', 'password', 'password_confirmation'];
                const missingFields = [];
                
                requiredFields.forEach(field => {
                    const value = formData.get(field);
                    if (!value || value.trim() === '') {
                        missingFields.push(field.replace('_', ' '));
                    }
                });
                
                // Password confirmation check
                const password = formData.get('password');
                const passwordConfirmation = formData.get('password_confirmation');
                if (password !== passwordConfirmation) {
                    alert('Password dan Confirm Password tidak sama.');
                    return;
                }
                
                if (missingFields.length > 0) {
                    alert('Field wajib diisi: ' + missingFields.join(', '));
                    return;
                }

                // Check for validation errors (duplicate email/phone)
                const emailInput = document.getElementById('emailInput');
                const phoneInput = document.getElementById('phoneInput');
                
                if (emailInput && emailInput.classList.contains('is-invalid')) {
                    showErrorNotification('Email sudah digunakan, silakan gunakan email lain');
                    return;
                }
                
                if (phoneInput && phoneInput.classList.contains('is-invalid')) {
                    showErrorNotification('Nomor HP sudah digunakan, silakan gunakan nomor lain');
                    return;
                }
                
                // Disable button to prevent double submission
                const submitBtn = document.getElementById('saveCreateUser');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

                // Submit to server
                fetch('/users/create', {
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
                        showSuccessNotification(data.message || 'User berhasil dibuat!');
                        
                        // Reset form and clear validations
                        form.reset();
                        clearEmailValidation();
                        clearPhoneValidation();
                        
                        // Close modal
                        const modalEl = document.getElementById('createUserModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        modal?.hide();

                        // Refresh table data (optional - you might want to reload the page)
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Show error notification
                        showErrorNotification(data.message || 'Gagal membuat user');
                        
                        // Log validation errors if any
                        if (data.errors) {
                            console.error('Validation errors:', data.errors);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorNotification('Terjadi kesalahan saat menyimpan user');
                })
                .finally(() => {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Save';
                });
            });
        });

        // Success notification function
        function showSuccessNotification(message) {
            // Create toast notification
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
            
            // Remove toast element after it's hidden
            toastElement.addEventListener('hidden.bs.toast', () => {
                toastElement.remove();
            });
        }

        function showWarningNotification(message) {
            const toastContainer = document.getElementById('toast-container') || createToastContainer();
            const toastId = 'toast-' + Date.now();
            const toastHtml = `
                <div class="toast align-items-center text-white bg-warning border-0" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="bi bi-exclamation-triangle me-2"></i>${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 4000 });
            toast.show();
            toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
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
    </script>
@endsection


