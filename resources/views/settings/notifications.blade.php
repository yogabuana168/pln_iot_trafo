@extends('partials.layouts.master')

@section('title', 'Notifications Setting')
@section('pagetitle', 'Notifications')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0"><i class="bi bi-bell me-2"></i>Notification Templates</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" id="createTemplateBtn"><i class="bi bi-plus-lg me-1"></i>Create</button>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-12 col-md-3">
                        <div class="input-group">
                            <select class="form-select" id="bulkAction">
                                <option value="">No Action</option>
                                <option value="delete">Delete</option>
                                <option value="activate">Activate</option>
                                <option value="deactivate">Deactivate</option>
                            </select>
                            <button class="btn btn-outline-primary" id="applyBulk"><i class="bi bi-check2 me-1"></i>Apply</button>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <select class="form-select" id="filterType">
                            <option value="all">All</option>
                            <option value="email">Email</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="push">Push</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3 ms-auto">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="searchBox" placeholder="Search...">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th style="width:40px"><input type="checkbox" id="selectAll"></th>
                                <th>Template Name</th>
                                <th style="width:90px" class="text-center">Type</th>
                                <th style="width:160px" class="text-center">Trigger</th>
                                <th style="width:90px" class="text-center">Status</th>
                                <th style="width:120px" class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="templatesTbody">
                            <!-- rows rendered by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div class="modal fade" id="templateModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-bell me-2"></i><span id="templateModalTitle">Create Template</span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="templateForm">
          @csrf
          <input type="hidden" name="id" id="tplId">
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label">Template Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="name" id="tplName" required>
            </div>
            <div class="col-12 col-md-3">
              <label class="form-label">Type</label>
              <select class="form-select" name="type" id="tplType">
                <option value="email">Email</option>
                <option value="whatsapp">WhatsApp</option>
                <option value="push">Push</option>
              </select>
            </div>
            <div class="col-12 col-md-3">
              <label class="form-label">Status</label>
              <select class="form-select" name="status" id="tplStatus">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            <div class="col-12 col-md-3">
              <label class="form-label">Trigger Sensor</label>
              <input type="text" class="form-control" name="trigger_sensor" id="tplTrigSensor" placeholder="e.g. R">
            </div>
            <div class="col-12 col-md-3">
              <label class="form-label">Trigger Condition</label>
              <select class="form-select" name="trigger_condition" id="tplTrigCond">
                <option value="equals">Equals</option>
                <option value="not_equals">Not Equals</option>
                <option value="changed">Changed</option>
              </select>
            </div>
            <div class="col-12 col-md-3">
              <label class="form-label">Trigger Value</label>
              <input type="text" class="form-control" name="trigger_value" id="tplTrigValue" placeholder="e.g. 1">
            </div>
            <div class="col-12">
              <label class="form-label">Content</label>
              <textarea class="form-control" name="content" id="tplContent" rows="6" placeholder="Use variables like @{{name}}, @{{date}}..."></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveTemplateBtn"><i class="bi bi-check-lg me-1"></i>Save</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const tbody = document.getElementById('templatesTbody');
  const selectAll = document.getElementById('selectAll');
  const bulkAction = document.getElementById('bulkAction');
  const applyBulk = document.getElementById('applyBulk');
  const searchBox = document.getElementById('searchBox');
  const filterType = document.getElementById('filterType');
  const createBtn = document.getElementById('createTemplateBtn');

  // Simple in-memory data while backend is wired
  let templates = [];

  function fetchTemplates() {
    fetch('/settings/notifications/list')
      .then(r => r.json())
      .then(data => { templates = data.data || []; render(); })
      .catch(() => { templates = []; render(); });
  }

  function render() {
    const q = (searchBox.value || '').toLowerCase();
    const type = filterType.value;
    const rows = templates
      .filter(t => (type === 'all' || t.type === type))
      .filter(t => !q || (t.name || '').toLowerCase().includes(q))
      .map(t => rowHtml(t)).join('');
    tbody.innerHTML = rows || '<tr><td colspan="5" class="text-center text-muted py-5">No templates</td></tr>';
  }

  function rowHtml(t) {
    return `
      <tr data-id="${t.id}">
        <td><input type="checkbox" class="row-check"></td>
        <td><a href="#" class="text-decoration-none" onclick="return editTemplate(${t.id})">${escapeHtml(t.name)}</a></td>
        <td class="text-center"><span class="badge ${t.type==='email'?'bg-primary-subtle text-primary':(t.type==='whatsapp'?'bg-success-subtle text-success':'bg-info-subtle text-info')}">${t.type}</span></td>
        <td class="text-center">${formatTrigger(t)}</td>
        <td class="text-center"><span class="badge ${t.status==='active'?'bg-success-subtle text-success':'bg-secondary-subtle text-secondary'}">${t.status}</span></td>
        <td class="text-end">
          <button class="btn btn-sm btn-outline-secondary me-1" onclick="return editTemplate(${t.id})"><i class="bi bi-pencil"></i></button>
          <button class="btn btn-sm btn-outline-danger" onclick="return deleteTemplate(${t.id})"><i class="bi bi-trash"></i></button>
        </td>
      </tr>`;
  }

  function escapeHtml(s){ return (s||'').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[c])); }
  function formatTrigger(t){
    if(!(t.trigger_sensor)&&!(t.trigger_value)) return '<span class="text-muted">-</span>';
    const cond = t.trigger_condition || 'equals';
    const op = cond==='equals'?'=':(cond==='not_equals'?'≠':cond);
    return `<span class="badge bg-warning-subtle text-warning">${escapeHtml(t.trigger_sensor||'?')} ${op} ${escapeHtml(t.trigger_value||'?')}</span>`;
  }

  // Bulk
  selectAll?.addEventListener('change', function(){
    tbody.querySelectorAll('.row-check').forEach(cb => cb.checked = selectAll.checked);
  });

  applyBulk?.addEventListener('click', function(){
    const action = bulkAction.value;
    if (!action) return;
    const ids = Array.from(tbody.querySelectorAll('.row-check:checked')).map(cb => cb.closest('tr').dataset.id);
    if (ids.length === 0) return;
    fetch('/settings/notifications/bulk', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify({ action, ids })
    }).then(r => r.json()).then(()=> fetchTemplates());
  });

  // Create/Edit
  createBtn?.addEventListener('click', function(){ openModal(); });
  window.editTemplate = function(id){
    const t = templates.find(x => x.id == id);
    openModal(t); return false;
  };
  window.deleteTemplate = function(id){
    if (!confirm('Delete this template?')) return false;
    fetch('/settings/notifications/delete', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify({ id }) })
      .then(r => r.json()).then(()=> fetchTemplates());
    return false;
  };

  const modalEl = document.getElementById('templateModal');
  const modal = new bootstrap.Modal(modalEl);
  const saveBtn = document.getElementById('saveTemplateBtn');

  function openModal(data){
    document.getElementById('templateModalTitle').textContent = data ? 'Edit Template' : 'Create Template';
    document.getElementById('tplId').value = data?.id || '';
    document.getElementById('tplName').value = data?.name || '';
    document.getElementById('tplType').value = data?.type || 'email';
    document.getElementById('tplStatus').value = data?.status || 'active';
    document.getElementById('tplContent').value = data?.content || '';
    if (document.getElementById('tplTrigSensor')) {
      document.getElementById('tplTrigSensor').value = data?.trigger_sensor || '';
      document.getElementById('tplTrigCond').value = data?.trigger_condition || 'equals';
      document.getElementById('tplTrigValue').value = data?.trigger_value || '';
    }
    modal.show();
  }

  saveBtn?.addEventListener('click', function(){
    const payload = {
      id: document.getElementById('tplId').value || undefined,
      name: document.getElementById('tplName').value.trim(),
      type: document.getElementById('tplType').value,
      status: document.getElementById('tplStatus').value,
      content: document.getElementById('tplContent').value,
      trigger_sensor: (document.getElementById('tplTrigSensor')?.value || '').trim() || null,
      trigger_condition: document.getElementById('tplTrigCond')?.value || null,
      trigger_value: (document.getElementById('tplTrigValue')?.value || '').trim() || null
    };
    if (!payload.name) return;
    fetch('/settings/notifications/save', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify(payload) })
      .then(r => r.json()).then(()=> { modal.hide(); fetchTemplates(); });
  });

  searchBox?.addEventListener('input', render);
  filterType?.addEventListener('change', render);

  fetchTemplates();
});
</script>
@endsection
