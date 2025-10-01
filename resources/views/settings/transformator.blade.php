@extends('partials.layouts.master')

@section('title', 'Transformator Setting')
@section('pagetitle', 'Transformator')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0"><i class="ri ri-plug-2-line me-2"></i>Transformator</h5>
                <span class="text-muted">Placeholder</span>
            </div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-12 col-md-3">
                        <div class="input-group input-group-sm">
                            <select class="form-select" id="trafoBulkAction">
                                <option value="">No Action</option>
                                <option value="delete">Delete</option>
                            </select>
                            <button class="btn btn-outline-primary" id="trafoApplyBulk">Apply</button>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <select class="form-select form-select-sm" id="trafoPerPage">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4 ms-auto">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="trafoSearch" placeholder="Search name/code/location...">
                            <button class="btn btn-primary" id="trafoCreateBtn"><i class="bi bi-plus-lg me-1"></i>Create</button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th style="width:32px"><input type="checkbox" id="trafoSelectAll" class="form-check-input"></th>
                                <th>Kode Aset</th>
                                <th style="width:120px">Nomor Seri</th>
                                <th>Merk</th>
                                <th style="width:100px">Type</th>
                                <th style="width:100px">Kapasitas</th>
                                <th>Lokasi</th>
                                <th style="width:150px">Gardu Induk</th>
                                <th style="width:120px">GPS</th>
                                <th style="width:120px" class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="trafoTbody"></tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3" id="trafoPager">
                    <small class="text-muted" id="trafoSummary"></small>
                    <div class="btn-group btn-group-sm" id="trafoNav"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const tbody = document.getElementById('trafoTbody');
  const search = document.getElementById('trafoSearch');
  const perPage = document.getElementById('trafoPerPage');
  const selectAll = document.getElementById('trafoSelectAll');
  const bulkSel = document.getElementById('trafoBulkAction');
  const bulkBtn = document.getElementById('trafoApplyBulk');
  const nav = document.getElementById('trafoNav');
  const summary = document.getElementById('trafoSummary');
  const createBtn = document.getElementById('trafoCreateBtn');

  let state = { page: 1, q: '', per: 10 };

  function fetchList(){
    const url = `/api/transformator?page=${state.page}&q=${encodeURIComponent(state.q)}&per_page=${state.per}`;
    fetch(url).then(r=>r.json()).then(render);
  }

  function render(p){
    const rows = (p.data||[]).map(r => {
      const gps = (r.koordinat_lat && r.koordinat_long) ? `${r.koordinat_lat}, ${r.koordinat_long}` : '-';
      const typeStr = r.type ? `${r.type.kapasitas_kva||''} kVA` : '-';
      const garduStr = r.gardu_induk ? `${r.gardu_induk.nama_gi||''}` : (r.gardu || '-');
      return `
      <tr data-id="${r.id}">
        <td><input type="checkbox" class="row-check form-check-input"></td>
        <td>${escapeHtml(r.kode_aset||'')}</td>
        <td>${escapeHtml(r.nomor_seri||'')}</td>
        <td>${escapeHtml(r.merk||'')}</td>
        <td><span class="badge bg-secondary">${escapeHtml(typeStr)}</span></td>
        <td><small class="text-muted">${escapeHtml(r.lokasi||'-')}</small></td>
        <td><small class="text-muted">${escapeHtml(garduStr)}</small></td>
        <td><small class="text-muted">${escapeHtml(gps)}</small></td>
        <td class="text-end">
          <button class="btn btn-sm btn-outline-secondary me-1" onclick="return trafoEdit(${r.id})"><i class="bi bi-pencil"></i></button>
          <button class="btn btn-sm btn-outline-danger" onclick="return trafoDelete(${r.id})"><i class="bi bi-trash"></i></button>
        </td>
      </tr>`;
    }).join('');
    tbody.innerHTML = rows || '<tr><td colspan="9" class="text-center text-muted py-4">No data</td></tr>';
    summary.textContent = `Showing ${p.from||0}-${p.to||0} of ${p.total||0}`;
    renderPager(p);
  }

  function renderPager(p){
    nav.innerHTML = '';
    const mk = (label, disabled, page) => `<button class="btn btn-outline-secondary ${disabled?'disabled':''}" data-page="${page}">${label}</button>`;
    nav.insertAdjacentHTML('beforeend', mk('«', !p.prev_page_url, 1));
    nav.insertAdjacentHTML('beforeend', mk('‹', !p.prev_page_url, Math.max(1,(p.current_page||1)-1)));
    nav.insertAdjacentHTML('beforeend', `<span class="btn btn-outline-secondary disabled">${p.current_page||1}/${p.last_page||1}</span>`);
    nav.insertAdjacentHTML('beforeend', mk('›', !p.next_page_url, Math.min(p.last_page||1,(p.current_page||1)+1)));
    nav.insertAdjacentHTML('beforeend', mk('»', !p.next_page_url, p.last_page||1));
    nav.querySelectorAll('button[data-page]').forEach(b=>{
      b.addEventListener('click', ()=>{ if(b.classList.contains('disabled')) return; state.page=parseInt(b.dataset.page,10)||1; fetchList(); });
    });
  }

  function escapeHtml(s){ return (s||'').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[c])); }

  selectAll.addEventListener('change', ()=>{ tbody.querySelectorAll('.row-check').forEach(cb=> cb.checked = selectAll.checked); });
  bulkBtn.addEventListener('click', ()=>{
    const act = bulkSel.value; if(!act) return;
    const ids = Array.from(tbody.querySelectorAll('.row-check:checked')).map(cb=>cb.closest('tr').dataset.id);
    if(ids.length===0) return;
    fetch('/api/transformator/bulk', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}, body: JSON.stringify({ action: act, ids }) })
      .then(r=>r.json()).then(()=> fetchList());
  });

  createBtn.addEventListener('click', ()=> {
    console.log('Create button clicked');
    openModal();
  });
  window.trafoEdit = function(id){
    fetch(`/api/transformator/show/${id}`).then(r=>r.json()).then(res=>{
      if(!res.success) return;
      openModal(res.data);
    });
    return false;
  };
  window.trafoDelete = function(id){ if(!confirm('Delete this transformer?')) return false; fetch('/api/transformator/delete',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,}, body: JSON.stringify({id})}).then(r=>r.json()).then(()=>fetchList()); return false; };

  // Modal
  const modalEl = document.createElement('div');
  modalEl.className='modal fade'; modalEl.id='trafoModal'; modalEl.innerHTML=`
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Transformer</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <form id="trafoForm">
          <input type="hidden" id="tfId">
          <div class="row">
            <div class="col-md-6 mb-2"><label class="form-label">Kode Aset</label><input class="form-control form-control-sm" id="tfKodeAset" required></div>
            <div class="col-md-6 mb-2"><label class="form-label">Nomor Seri</label><input class="form-control form-control-sm" id="tfNomorSeri"></div>
            <div class="col-md-6 mb-2"><label class="form-label">Merk</label>
                <select class="form-select form-select-sm" id="tfMerk">
                    <option value="">Pilih Merk</option>
                    <option value="Trafindo">Trafindo</option>
                    <option value="Unindo">Unindo</option>
                    <option value="Shimazu">Shimazu</option>
                    <option value="Centrado">Centrado</option>
                    <option value="Hitachi ABB (Hitachi Energy)">Hitachi ABB (Hitachi Energy)</option>
                    <option value="Schneider Electric">Schneider Electric</option>
                    <option value="WIKA">WIKA</option>
                    <option value="Indotrafo">Indotrafo</option>
                    <option value="PLN Enjiniring / internal">PLN Enjiniring / internal</option>
                    <option value="__custom__">Custom...</option>
                </select>
                <input type="text" class="form-control form-control-sm mt-2 d-none" id="tfMerkCustom" placeholder="Ketik merk sendiri">
            </div>
            <div class="col-md-6 mb-2"><label class="form-label">Tahun Operasi</label><select class="form-select form-select-sm" id="tfTahunOperasi"></select></div>
            <div class="col-md-6 mb-2"><label class="form-label">Feeder/Penyulang</label><input class="form-control form-control-sm" id="tfPenyulang"></div>
            <div class="col-md-6 mb-2"><label class="form-label">Gardu Induk</label>
                <select class="form-select form-select-sm" id="tfGarduIndukId">
                    <option value="">Pilih Gardu Induk</option>
                </select>
            </div>
            <div class="col-12 mb-2"><label class="form-label">Lokasi (alamat singkat)</label><input class="form-control form-control-sm" id="tfLokasi"></div>
            <div class="col-md-6 mb-2"><label class="form-label">Status</label><select class="form-select form-select-sm" id="tfStatus">
                <option value="">Pilih Status</option>
                <option value="Active">Active</option>
                <option value="Standby">Standby</option>
                <option value="Rusak">Rusak</option>
            </select></div>
            <div class="col-md-6 mb-2"><label class="form-label">Type Transformator</label><select class="form-select form-select-sm" id="tfTypeId"><option value="">Pilih Type</option></select></div>
            <div class="col-12 mb-2"><label class="form-label">Keterangan</label><textarea class="form-control form-control-sm" id="tfKeterangan" rows="2"></textarea></div>
          </div>
              <p class="mt-2 small text-muted">Catatan: Koordinat GPS akan diisi otomatis oleh perangkat IoT pada saat pengiriman data, tidak perlu diinput manual.</p>
        </form>
      </div>
      <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary" id="tfSave">Save</button></div>
    </div>
  </div>`;
  document.body.appendChild(modalEl);
  const modal = new bootstrap.Modal(modalEl, { backdrop: 'static', keyboard: false });
  console.log('Modal created:', modal);
  document.getElementById('tfSave').addEventListener('click', ()=>{
    const payload={
      id: document.getElementById('tfId').value||undefined,
      kode_aset: document.getElementById('tfKodeAset').value.trim(),
      nomor_seri: document.getElementById('tfNomorSeri').value.trim()||null,
      merk: (function(){
        const sel = document.getElementById('tfMerk');
        const v = sel.value;
        if (v === '__custom__') {
          return (document.getElementById('tfMerkCustom').value||'').trim() || null;
        }
        return v || null;
      })(),
      tahun_operasi: parseInt(document.getElementById('tfTahunOperasi').value)||null,
      lokasi: document.getElementById('tfLokasi').value.trim()||null,
      penyulang: document.getElementById('tfPenyulang').value.trim()||null,
      gardu: document.getElementById('tfGardu').value.trim()||null,
      status: document.getElementById('tfStatus').value.trim()||null,
      keterangan: document.getElementById('tfKeterangan').value.trim()||null,
      type_id: document.getElementById('tfTypeId').value||null,
      gardu_induk_id: document.getElementById('tfGarduIndukId').value||null
    };
    if(!payload.kode_aset) return;
    fetch('/api/transformator/save',{ method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify(payload)}).then(r=>r.json()).then(()=>{ modal.hide(); fetchList(); });
  });

  // Toggle custom merk input
  (function(){
    const sel = document.getElementById('tfMerk');
    const custom = document.getElementById('tfMerkCustom');
    sel.addEventListener('change', ()=>{
      if (sel.value === '__custom__') {
        custom.classList.remove('d-none');
        custom.focus();
      } else {
        custom.classList.add('d-none');
      }
    });
  })();
  
  // Load type transformator dropdown
  function loadTypes(){
    fetch('/api/type-transformator').then(r=>r.json()).then(list=>{
      const sel = document.getElementById('tfTypeId');
      sel.innerHTML = '<option value="">Pilih Type</option>' + list.map(t=>`<option value="${t.id}">${escapeHtml(String(t.kapasitas_kva||''))} kVA - ${escapeHtml(String(t.tegangan_primer_kv||''))}kV / ${escapeHtml(String(t.tegangan_sekunder_v||''))}V</option>`).join('');
    });
  }
  
  // Load gardu induk dropdown
  function loadGarduInduk(){
    fetch('/api/gardu-induk')
      .then(r=>r.json())
      .then(list=>{
        console.log('Gardu Induk list loaded:', list);
        const sel = document.getElementById('tfGarduIndukId');
        sel.innerHTML = '<option value="">Pilih Gardu Induk</option>' + list.map(g=>`<option value="${g.id}">${escapeHtml(String(g.nama_gi||''))} - ${escapeHtml(String(g.kode_gi||''))}</option>`).join('');
      })
      .catch(error => {
        console.error('Error loading gardu induk:', error);
      });
  }
  
  function openModal(data){ 
    console.log('Opening modal with data:', data);
    document.getElementById('tfId').value=data?.id||''; 
    document.getElementById('tfKodeAset').value=data?.kode_aset||''; 
    document.getElementById('tfNomorSeri').value=data?.nomor_seri||''; 
    document.getElementById('tfMerk').value=data?.merk||''; 
    document.getElementById('tfTahunOperasi').value=data?.tahun_operasi||''; 
    document.getElementById('tfPenyulang').value=data?.penyulang||''; 
    document.getElementById('tfGardu').value=data?.gardu||''; 
    document.getElementById('tfLokasi').value=data?.lokasi||''; 
    document.getElementById('tfStatus').value=data?.status||''; 
    document.getElementById('tfTypeId').value=data?.type_id||''; 
    document.getElementById('tfGarduIndukId').value=data?.gardu_induk_id||''; 
    document.getElementById('tfKeterangan').value=data?.keterangan||''; 
    loadTypes();
    loadGarduInduk();
    console.log('About to show modal');
    modal.show(); 
  }

  // Events
  search.addEventListener('input', ()=>{ state.page=1; state.q=search.value; fetchList(); });
  perPage.addEventListener('change', ()=>{ state.page=1; state.per=parseInt(perPage.value,10)||10; fetchList(); });

  // init
  fetchList();
  // populate tahun operasi dropdown (1900..current year)
  (function(){
    const sel = document.getElementById('tfTahunOperasi');
    const now = new Date().getFullYear();
    let opts = '<option value="">Pilih Tahun</option>';
    for(let y=now; y>=1900; y--){ opts += `<option value="${y}">${y}</option>`; }
    sel.innerHTML = opts;
  })();
});
</script>
@endsection


