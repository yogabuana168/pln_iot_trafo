@extends('partials.layouts.master')
@section('title', 'WA Setting')
@section('pagetitle', 'WA Setting')
@section('content')
<style>
.wa-container-wrapper {
    position: relative;
    min-height: 600px;
    margin-top: 20px;
    margin-bottom: 80px;
}
.source-panel {
    width: 100%;
    background: white;
    border: 2px solid #dee2e6;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    height: 600px;
    overflow-y: auto;
}
.source-panel-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 12px;
    border-radius: 10px 10px 0 0;
    font-weight: 600;
    position: sticky;
    top: 0;
    z-index: 10;
    font-size: 0.9rem;
}
.source-item {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 8px 10px;
    margin: 8px;
    cursor: move;
    transition: all 0.2s;
    font-size: 0.8rem;
}
.source-item:hover {
    background: #e7f3ff;
    border-color: #0d6efd;
    transform: translateX(3px);
}
.source-item.dragging { opacity: 0.4; }
.template-source-item {
    background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
    color: white;
    border: none;
}
.template-source-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37,211,102,0.3);
}
.canvas-area {
    position: relative;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border-radius: 12px;
    height: 600px;
    overflow: auto;
}
.add-container-btn {
    position: fixed;
    bottom: 80px;
    left: calc(50% - 80px);
    z-index: 1001;
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
}
.auto-align-btn {
    position: fixed;
    bottom: 80px;
    left: calc(50% + 80px);
    z-index: 1001;
    box-shadow: 0 6px 20px rgba(111,66,193,0.4);
}
.notification-container {
    position: absolute;
    width: 260px;
    background: white;
    border: 2px solid #0d6efd;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    cursor: move;
}
.container-header {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    color: white;
    padding: 8px 10px;
    border-radius: 10px 10px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: move;
    font-size: 0.85rem;
}
.container-body {
    padding: 10px;
    max-height: 280px;
    overflow-y: auto;
}
.template-slot {
    background: #f0fff4;
    border: 2px dashed #25d366;
    border-radius: 8px;
    padding: 10px;
    text-align: center;
    min-height: 50px;
    margin-bottom: 10px;
    font-size: 0.8rem;
}
.template-slot.filled {
    background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
    color: white;
    border-style: solid;
}
.recipients-slot {
    background: #f8f9fa;
    border: 2px dashed #6c757d;
    border-radius: 8px;
    padding: 10px;
    min-height: 80px;
    font-size: 0.8rem;
}
.recipients-slot.drag-over {
    background: #e7f3ff;
    border-color: #0d6efd;
}
.recipient-item {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 5px 8px;
    border-radius: 6px;
    margin-bottom: 5px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.75rem;
}
.recipient-item .remove-btn {
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.7rem;
}
.save-all-btn {
    position: absolute;
    top: -50px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1002;
    box-shadow: 0 6px 20px rgba(40,167,69,0.4);
}
#containerNameInput.is-invalid {
    border-color: #dc3545;
}
#containerNameInput.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}
</style>

<div class="wa-container-wrapper">
    <button class="btn btn-success btn-sm save-all-btn" id="saveAllBtn" style="display:none;">
        <i class="bi bi-check-lg me-2"></i>Save All Configurations
    </button>

    <div class="row g-3">
        <!-- Left Panel: Users & Groups -->
        <div class="col-12 col-lg-3">
            <div class="source-panel">
                <div class="source-panel-header">
                    <i class="bi bi-people me-2"></i>Users & Groups
                </div>
                <div class="p-2">
                    <input type="text" class="form-control form-control-sm mb-2" id="searchRecipients" placeholder="🔍 Search...">
                </div>
                <ul class="nav nav-tabs nav-justified" style="padding: 0 8px;">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#groupsTab" style="font-size:0.75rem; padding: 6px 8px;">Groups</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#usersTab" style="font-size:0.75rem; padding: 6px 8px;">Users</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="groupsTab">
                        <div id="groupsList"></div>
                    </div>
                    <div class="tab-pane fade" id="usersTab">
                        <div id="usersList"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Center: Canvas Area -->
        <div class="col-12 col-lg-6">
            <div class="canvas-area" id="canvasArea">
                <div class="text-center text-muted" style="padding-top: 30%; font-size: 1rem;">
                    <i class="bi bi-plus-circle" style="font-size: 3rem; opacity: 0.3;"></i>
                    <p class="mb-0">Click "Add Container" to start</p>
                    <p class="small">Drag templates and recipients into containers</p>
                </div>
            </div>
        </div>

        <!-- Right Panel: Templates -->
        <div class="col-12 col-lg-3">
            <div class="source-panel">
                <div class="source-panel-header" style="background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);">
                    <i class="bi bi-whatsapp me-2"></i>WA Templates
                </div>
                <div class="p-2">
                    <input type="text" class="form-control form-control-sm mb-2" id="searchTemplates" placeholder="🔍 Search...">
                </div>
                <div id="templatesList"></div>
            </div>
        </div>
    </div>

    <button class="btn btn-primary add-container-btn" id="addContainerBtn">
        <i class="bi bi-plus-circle me-2"></i>Add Container
    </button>
    
    <button class="btn btn-secondary auto-align-btn" id="autoAlignBtn" style="display:none;">
        <i class="bi bi-grid-3x3-gap me-2"></i>Auto Align
    </button>
</div>

<!-- Modal for Container Name -->
<div class="modal fade" id="containerNameModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-box me-2"></i>Create New Container</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="containerNameInput" class="form-label">Container Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="containerNameInput" placeholder="e.g. Critical Alerts, Daily Reports, etc." autofocus>
                <small class="text-muted">Give this container a meaningful name to identify its purpose</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmCreateContainer">
                    <i class="bi bi-plus-circle me-2"></i>Create Container
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('assets/js/wa-containers.js') }}?v={{ time() }}"></script>
@endsection
