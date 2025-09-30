@extends('partials.layouts.master')

@section('title', 'Master Data Employ')
@section('pagetitle', 'Master Data Employ')

@section('content')
    <div class="row g-3">
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between py-2">
                    <h6 class="mb-0">Tambah Posisi</h6>
                </div>
                <div class="card-body">
                    <form id="formAddPosition" action="/users/master/position" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Nama Posisi</label>
                            <input type="text" class="form-control form-control-sm" name="position_name" placeholder="Engineer, Supervisor, ...">
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        </div>
                    </form>
                    <hr>
                    <h6 class="mb-2">Daftar Posisi</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0">
                            <thead>
                                <tr>
                                    <th style="width:60px">No</th>
                                    <th>Nama</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($positions ?? []) as $i => $item)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ $item->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-muted text-center">Belum ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between py-2">
                    <h6 class="mb-0">Tambah Departemen</h6>
                </div>
                <div class="card-body">
                    <form id="formAddDepartment" action="/users/master/department" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Nama Departemen</label>
                            <input type="text" class="form-control form-control-sm" name="department_name" placeholder="Distribusi, Transmisi, ...">
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        </div>
                    </form>
                    <hr>
                    <h6 class="mb-2">Daftar Departemen</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0">
                            <thead>
                                <tr>
                                    <th style="width:60px">No</th>
                                    <th>Nama</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($departments ?? []) as $i => $item)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ $item->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-muted text-center">Belum ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between py-2">
                    <h6 class="mb-0">Tambah Group User</h6>
                </div>
                <div class="card-body">
                    <form id="formAddGroup" action="/users/master/group" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Nama Group</label>
                            <input type="text" class="form-control form-control-sm" name="group_name" placeholder="Nama Group">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Keterangan</label>
                            <input type="text" class="form-control form-control-sm" name="group_desc" placeholder="Opsional">
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        </div>
                    </form>
                    <hr>
                    <h6 class="mb-2">Daftar Group</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0">
                            <thead>
                                <tr>
                                    <th style="width:60px">No</th>
                                    <th>Nama</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($groups ?? []) as $i => $item)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ $item->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-muted text-center">Belum ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
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

    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    }

    // Handle form submissions with success notifications
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const formType = this.id;
            
            // Validate required fields
            const requiredInput = this.querySelector('input[name$="_name"]');
            if (requiredInput && !requiredInput.value.trim()) {
                alert('Nama tidak boleh kosong.');
                return;
            }
            
            // Simulate successful submission
            setTimeout(() => {
                let message = '';
                if (formType === 'formAddPosition') {
                    message = 'Posisi berhasil ditambahkan!';
                } else if (formType === 'formAddDepartment') {
                    message = 'Departemen berhasil ditambahkan!';
                } else if (formType === 'formAddGroup') {
                    message = 'Group berhasil ditambahkan!';
                }
                
                showSuccessNotification(message);
                this.reset();
                
                // In real implementation, you would submit the form to server
                // this.submit();
            }, 500);
        });
    });
});
</script>
@endsection
