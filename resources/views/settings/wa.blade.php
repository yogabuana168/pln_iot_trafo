@extends('partials.layouts.master')

@section('title', 'WA Setting')
@section('pagetitle', 'WA Setting')

@section('content')
    <div class="row mt-4">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bi bi-whatsapp me-2"></i>WA Notification Settings</h5>
                    <button class="btn btn-primary btn-sm" id="waSaveBtn"><i class="bi bi-check-lg me-1"></i>Save</button>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Audience</label>
                        <div class="d-flex gap-3 flex-wrap">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="audienceType" id="audAll" value="all" checked>
                                <label class="form-check-label" for="audAll">All Users</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="audienceType" id="audGroup" value="group">
                                <label class="form-check-label" for="audGroup">By User Group</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="audienceType" id="audUsers" value="users">
                                <label class="form-check-label" for="audUsers">Specific Users</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 d-none" id="groupSelectWrap">
                        <label class="form-label">Select Groups</label>
                        <select class="form-select" id="groupSelect" multiple></select>
                        <small class="text-muted">Pilih satu atau lebih group.</small>
                    </div>

                    <div class="mb-3 d-none" id="userSelectWrap">
                        <label class="form-label">Select Users</label>
                        <select class="form-select" id="userSelect" multiple></select>
                        <small class="text-muted">Pilih user penerima notifikasi.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Template</label>
                        <select class="form-select" id="templateSelect"></select>
                        <small class="text-muted">Template diambil dari halaman Notification Templates.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const audAll = document.getElementById('audAll');
  const audGroup = document.getElementById('audGroup');
  const audUsers = document.getElementById('audUsers');
  const groupWrap = document.getElementById('groupSelectWrap');
  const userWrap = document.getElementById('userSelectWrap');
  const groupSelect = document.getElementById('groupSelect');
  const userSelect = document.getElementById('userSelect');
  const templateSelect = document.getElementById('templateSelect');
  const saveBtn = document.getElementById('waSaveBtn');

  function refreshVisibility(){
    groupWrap.classList.toggle('d-none', !audGroup.checked);
    userWrap.classList.toggle('d-none', !audUsers.checked);
  }
  [audAll,audGroup,audUsers].forEach(el=> el.addEventListener('change', refreshVisibility));

  // Load options and existing setting
  fetch('/api/wa/audience-options').then(r=>r.json()).then(data=>{
    groupSelect.innerHTML = (data.groups||[]).map(g=>`<option value="${g.id}">${g.name}</option>`).join('');
    userSelect.innerHTML = (data.users||[]).map(u=>`<option value="${u.id}">${u.name} &lt;${u.email}&gt;</option>`).join('');
    templateSelect.innerHTML = '<option value="">Pilih Template</option>' + (data.templates||[]).filter(t=>t.type==='whatsapp' && t.status==='active').map(t=>`<option value="${t.id}">${t.name}</option>`).join('');
  }).then(()=>{
    return fetch('/api/wa/setting').then(r=>r.json()).then(s=>{
      if(!s) return; 
      if(s.audience_type==='group') audGroup.checked=true; else if(s.audience_type==='users') audUsers.checked=true; else audAll.checked=true;
      refreshVisibility();
      const ids = s.audience_ids||[];
      if (Array.isArray(ids)) {
        if (s.audience_type==='group') Array.from(groupSelect.options).forEach(o=>{ o.selected = ids.includes(parseInt(o.value)); });
        if (s.audience_type==='users') Array.from(userSelect.options).forEach(o=>{ o.selected = ids.includes(parseInt(o.value)); });
      }
      if (s.template_id) templateSelect.value = String(s.template_id);
    });
  });

  saveBtn.addEventListener('click', ()=>{
    const audience_type = audGroup.checked ? 'group' : (audUsers.checked ? 'users' : 'all');
    let audience_ids = [];
    if (audience_type==='group') audience_ids = Array.from(groupSelect.selectedOptions).map(o=>parseInt(o.value));
    if (audience_type==='users') audience_ids = Array.from(userSelect.selectedOptions).map(o=>parseInt(o.value));
    const template_id = templateSelect.value ? parseInt(templateSelect.value) : null;
    fetch('/api/wa/setting/save', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify({ audience_type, audience_ids, template_id }) })
      .then(r=>r.json()).then(()=>{ alert('Saved'); });
  });
});
</script>
@endsection
