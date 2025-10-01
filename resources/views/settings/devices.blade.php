@extends('partials.layouts.master')

@section('title', 'Data Type Transformator')
@section('pagetitle', 'Data Type Transformator')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0"><i class="ri ri-cpu-line me-2"></i>Data Type Transformator</h5>
                <span class="text-muted">Kelola standar teknis</span>
            </div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-12 col-md-3">
                        <div class="input-group input-group-sm">
                            <select class="form-select" id="typeBulkAction">
                                <option value="">No Action</option>
                                <option value="delete">Delete</option>
                            </select>
                            <button class="btn btn-outline-primary" id="typeApplyBulk">Apply</button>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <select class="form-select form-select-sm" id="typePerPage">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4 ms-auto">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="typeSearch" placeholder="Search capacity/volt/pendingin...">
                            <button class="btn btn-primary" id="typeCreateBtn"><i class="bi bi-plus-lg me-1"></i>Create</button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th style="width:32px"><input type="checkbox" id="typeSelectAll" class="form-check-input"></th>
                                <th style="width:100px">Kapasitas (kVA)</th>
                                <th style="width:120px">Primer (kV)</th>
                                <th style="width:120px">Sekunder (V)</th>
                                <th style="width:80px">Hz</th>
                                <th style="width:100px">Impedansi (%)</th>
                                <th style="width:80px">Phasa</th>
                                <th>Pendingin</th>
                                <th>Konstruksi</th>
                                <th>Isolasi</th>
                                <th>FCO</th>
                                <th>Arrester</th>
                                <th style="width:120px" class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="typeTbody"></tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3" id="typePager">
                    <small class="text-muted" id="typeSummary"></small>
                    <div class="btn-group btn-group-sm" id="typeNav"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gardu Induk Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0"><i class="ri ri-building-line me-2"></i>Data Gardu Induk</h5>
                <span class="text-muted">Kelola data gardu induk</span>
            </div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-12 col-md-3">
                        <div class="input-group input-group-sm">
                            <select class="form-select" id="garduBulkAction">
                                <option value="">No Action</option>
                                <option value="delete">Delete</option>
                            </select>
                            <button class="btn btn-outline-primary" id="garduApplyBulk">Apply</button>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <select class="form-select form-select-sm" id="garduPerPage">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4 ms-auto">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="garduSearch" placeholder="Search nama gardu/lokasi...">
                            <button class="btn btn-primary" id="garduCreateBtn"><i class="bi bi-plus-lg me-1"></i>Create</button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th style="width:32px"><input type="checkbox" id="garduSelectAll" class="form-check-input"></th>
                                <th style="width:200px">Nama Gardu Induk</th>
                                <th style="width:250px">Alamat</th>
                                <th style="width:120px">Kode Gardu</th>
                                <th style="width:100px">Kapasitas (MVA)</th>
                                <th style="width:120px">Tegangan (kV)</th>
                                <th style="width:100px">Status</th>
                                <th style="width:150px">Tanggal Operasi</th>
                                <th style="width:120px" class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="garduTbody"></tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3" id="garduPager">
                    <small class="text-muted" id="garduSummary"></small>
                    <div class="btn-group btn-group-sm" id="garduNav"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const tbody = document.getElementById('typeTbody');
  const search = document.getElementById('typeSearch');
  const perPage = document.getElementById('typePerPage');
  const selectAll = document.getElementById('typeSelectAll');
  const bulkSel = document.getElementById('typeBulkAction');
  const bulkBtn = document.getElementById('typeApplyBulk');
  const nav = document.getElementById('typeNav');
  const summary = document.getElementById('typeSummary');
  const createBtn = document.getElementById('typeCreateBtn');

  let state = { page: 1, q: '', per: 10 };

  function fetchList(){
    const url = `/api/type-transformator/list?page=${state.page}&q=${encodeURIComponent(state.q)}&per_page=${state.per}`;
    fetch(url).then(r=>r.json()).then(render);
  }

  function render(p){
    const rows = (p.data||[]).map(r => `
      <tr data-id="${r.id}">
        <td><input type="checkbox" class="row-check form-check-input"></td>
        <td>${escapeHtml(r.kapasitas_kva)}</td>
        <td>${escapeHtml(r.tegangan_primer_kv)}</td>
        <td>${escapeHtml(r.tegangan_sekunder_v)}</td>
        <td>${escapeHtml(r.frekuensi_hz ?? '')}</td>
        <td>${escapeHtml(r.impedansi_percent ?? '')}</td>
        <td>${escapeHtml(r.jumlah_phasa)}</td>
        <td>${escapeHtml(r.pendingin ?? '')}</td>
        <td>${escapeHtml(r.jenis_konstruksi ?? '')}</td>
        <td>${escapeHtml(r.isolasi ?? '')}</td>
        <td>${escapeHtml(r.fco ?? '')}</td>
        <td>${escapeHtml(r.arrester ?? '')}</td>
        <td class="text-end">
          <button class="btn btn-sm btn-outline-secondary me-1" onclick="return typeEdit(${r.id})"><i class="bi bi-pencil"></i></button>
          <button class="btn btn-sm btn-outline-danger" onclick="return typeDelete(${r.id})"><i class="bi bi-trash"></i></button>
        </td>
      </tr>`).join('');
    tbody.innerHTML = rows || '<tr><td colspan="13" class="text-center text-muted py-4">No data</td></tr>';
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

  function escapeHtml(s){ s = (s===null||s===undefined)?'':String(s); return s.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[c])); }

  selectAll.addEventListener('change', ()=>{ tbody.querySelectorAll('.row-check').forEach(cb=> cb.checked = selectAll.checked); });
  bulkBtn.addEventListener('click', ()=>{
    const act = bulkSel.value; if(!act) return;
    const ids = Array.from(tbody.querySelectorAll('.row-check:checked')).map(cb=>cb.closest('tr').dataset.id);
    if(ids.length===0) return;
    fetch('/api/type-transformator/bulk', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}, body: JSON.stringify({ action: act, ids }) })
      .then(r=>r.json()).then(()=> fetchList());
  });

  createBtn.addEventListener('click', ()=> openModal());
  window.typeEdit = function(id){
    const tr = tbody.querySelector(`tr[data-id="${id}"]`);
    openModal({ id,
      kapasitas_kva: tr.children[1].textContent.trim(),
      tegangan_primer_kv: tr.children[2].textContent.trim(),
      tegangan_sekunder_v: tr.children[3].textContent.trim(),
      frekuensi_hz: tr.children[4].textContent.trim(),
      impedansi_percent: tr.children[5].textContent.trim(),
      jumlah_phasa: tr.children[6].textContent.trim(),
      pendingin: tr.children[7].textContent.trim(),
      jenis_konstruksi: tr.children[8].textContent.trim(),
      isolasi: tr.children[9].textContent.trim(),
      fco: tr.children[10].textContent.trim(),
      arrester: tr.children[11].textContent.trim(),
    });
    return false;
  };
  window.typeDelete = function(id){ if(!confirm('Delete this type?')) return false; fetch('/api/type-transformator/delete',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,}, body: JSON.stringify({id})}).then(r=>r.json()).then(()=>fetchList()); return false; };

  // Modal
  const modalEl = document.createElement('div');
  modalEl.className='modal fade'; modalEl.id='typeModal'; modalEl.innerHTML=`
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Type Transformator</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <form id="typeForm">
          <input type="hidden" id="tpId">
          <div class="row">
            <div class="col-md-4 mb-2"><label class="form-label">Kapasitas (kVA)</label>
              <select class="form-select form-select-sm" id="tpKva" required>
                <option value="">Pilih Kapasitas</option>
                <option>25</option><option>50</option><option>100</option><option>160</option><option>200</option>
                <option>250</option><option>315</option><option>400</option><option>500</option><option>630</option>
                <option>800</option><option>1000</option><option>1250</option><option>1600</option><option>2000</option>
                <option>2500</option>
              </select>
            </div>
            <div class="col-md-4 mb-2"><label class="form-label">Primer (kV)</label>
              <select class="form-select form-select-sm" id="tpPrimer" required>
                <option value="">Pilih Tegangan Primer</option>
                <option value="20">20</option>
                <option value="11">11</option>
                <option value="6.6">6.6</option>
              </select>
            </div>
            <div class="col-md-4 mb-2"><label class="form-label">Sekunder (V)</label>
              <select class="form-select form-select-sm" id="tpSekunder" required>
                <option value="">Pilih Tegangan Sekunder</option>
                <option value="400">400/230</option>
              </select>
            </div>
            <div class="col-md-4 mb-2"><label class="form-label">Frekuensi (Hz)</label>
              <select class="form-select form-select-sm" id="tpHz">
                <option value="50" selected>50</option>
              </select>
            </div>
            <div class="col-md-4 mb-2"><label class="form-label">Impedansi (%)</label><input type="number" step="0.01" class="form-control form-control-sm" id="tpImpedansi"></div>
            <div class="col-md-4 mb-2"><label class="form-label">Jumlah Phasa</label>
              <select class="form-select form-select-sm" id="tpPhasa">
                <option value="1">1</option>
                <option value="3" selected>3</option>
              </select>
            </div>
            <div class="col-md-4 mb-2"><label class="form-label">Pendingin</label>
              <select class="form-select form-select-sm" id="tpPendingin">
                <option value="">Pilih Pendingin</option>
                <option>ONAN</option>
                <option>Dry type</option>
              </select>
            </div>
            <div class="col-md-4 mb-2"><label class="form-label">Konstruksi</label>
              <select class="form-select form-select-sm" id="tpKonstruksi">
                <option value="">Pilih Konstruksi</option>
                <option>Trafo Tiang</option>
                <option>Gardu Beton</option>
                <option>Gardu Portal</option>
                <option>Gardu Compact</option>
              </select>
            </div>
            <div class="col-md-4 mb-2"><label class="form-label">Isolasi</label>
              <select class="form-select form-select-sm" id="tpIsolasi">
                <option value="">Pilih Isolasi</option>
                <option>Minyak</option>
                <option>Resin/Epoxy</option>
              </select>
            </div>
            <div class="col-md-6 mb-2"><label class="form-label">FCO</label>
              <select class="form-select form-select-sm" id="tpFco">
                <option value="">Pilih FCO</option>
                <option>6A</option><option>10A</option><option>16A</option><option>25A</option>
                <option>40A</option><option>63A</option><option>100A</option>
              </select>
            </div>
            <div class="col-md-6 mb-2"><label class="form-label">Arrester</label>
              <select class="form-select form-select-sm" id="tpArrester">
                <option value="">Pilih Arrester</option>
                <option>5 kA</option>
                <option>10 kA</option>
                <option>20 kA</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary" id="tpSave">Save</button></div>
    </div>
  </div>`;
  document.body.appendChild(modalEl);
  const modal = new bootstrap.Modal(modalEl, { backdrop: 'static', keyboard: false });
  document.getElementById('tpSave').addEventListener('click', ()=>{
    const payload={ id: document.getElementById('tpId').value||undefined,
      kapasitas_kva: parseInt(document.getElementById('tpKva').value)||0,
      tegangan_primer_kv: parseFloat(document.getElementById('tpPrimer').value)||0,
      tegangan_sekunder_v: 400, // fixed option 400/230
      frekuensi_hz: document.getElementById('tpHz').value?parseInt(document.getElementById('tpHz').value):50,
      impedansi_percent: document.getElementById('tpImpedansi').value?parseFloat(document.getElementById('tpImpedansi').value):null,
      jumlah_phasa: parseInt(document.getElementById('tpPhasa').value)||1,
      pendingin: document.getElementById('tpPendingin').value.trim()||null,
      jenis_konstruksi: document.getElementById('tpKonstruksi').value.trim()||null,
      isolasi: document.getElementById('tpIsolasi').value.trim()||null,
      fco: document.getElementById('tpFco').value.trim()||null,
      arrester: document.getElementById('tpArrester').value.trim()||null };
    if(!payload.kapasitas_kva || !payload.tegangan_primer_kv || !payload.tegangan_sekunder_v) return;
    fetch('/api/type-transformator/save',{ method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify(payload)}).then(r=>r.json()).then(()=>{ modal.hide(); fetchList(); });
  });
  function openModal(data){ document.getElementById('tpId').value=data?.id||''; document.getElementById('tpKva').value=data?.kapasitas_kva||''; document.getElementById('tpPrimer').value=data?.tegangan_primer_kv||''; document.getElementById('tpSekunder').value=data?.tegangan_sekunder_v||''; document.getElementById('tpHz').value=data?.frekuensi_hz||'50'; document.getElementById('tpImpedansi').value=data?.impedansi_percent||''; document.getElementById('tpPhasa').value=data?.jumlah_phasa||'1'; document.getElementById('tpPendingin').value=data?.pendingin||''; document.getElementById('tpKonstruksi').value=data?.jenis_konstruksi||''; document.getElementById('tpIsolasi').value=data?.isolasi||''; document.getElementById('tpFco').value=data?.fco||''; document.getElementById('tpArrester').value=data?.arrester||''; modal.show(); }

  // Events
  search.addEventListener('input', ()=>{ state.page=1; state.q=search.value; fetchList(); });
  perPage.addEventListener('change', ()=>{ state.page=1; state.per=parseInt(perPage.value,10)||10; fetchList(); });

  // init
  fetchList();

  // ==================== GARDU INDUK FUNCTIONALITY ====================
  const garduTbody = document.getElementById('garduTbody');
  const garduSearch = document.getElementById('garduSearch');
  const garduPerPage = document.getElementById('garduPerPage');
  const garduSelectAll = document.getElementById('garduSelectAll');
  const garduBulkSel = document.getElementById('garduBulkAction');
  const garduBulkBtn = document.getElementById('garduApplyBulk');
  const garduNav = document.getElementById('garduNav');
  const garduSummary = document.getElementById('garduSummary');
  const garduCreateBtn = document.getElementById('garduCreateBtn');

  let garduState = { page: 1, q: '', per: 10 };

  function fetchGarduList(){
    const url = `/api/gardu-induk/list?page=${garduState.page}&q=${encodeURIComponent(garduState.q)}&per_page=${garduState.per}`;
    fetch(url).then(r=>r.json()).then(renderGardu);
  }

  function renderGardu(p){
    const rows = (p.data||[]).map(r => `
      <tr data-id="${r.id}">
        <td><input type="checkbox" class="row-check form-check-input"></td>
        <td>${escapeHtml(r.nama_gi)}</td>
        <td>${escapeHtml(r.alamat ?? '')}</td>
        <td>${escapeHtml(r.kode_gi ?? '')}</td>
        <td>${escapeHtml(r.kapasitas_mva ?? '')}</td>
        <td>${escapeHtml(r.tegangan_level_in_kv ?? '')}</td>
        <td><span class="badge ${r.status === 'aktif' ? 'bg-success' : 'bg-secondary'}">${escapeHtml(r.status ?? '')}</span></td>
        <td>${escapeHtml(r.tahun_operasi ?? '')}</td>
        <td class="text-end">
          <button class="btn btn-sm btn-outline-secondary me-1" onclick="return garduEdit(${r.id})"><i class="bi bi-pencil"></i></button>
          <button class="btn btn-sm btn-outline-danger" onclick="return garduDelete(${r.id})"><i class="bi bi-trash"></i></button>
        </td>
      </tr>`).join('');
    garduTbody.innerHTML = rows || '<tr><td colspan="9" class="text-center text-muted py-4">No data</td></tr>';
    garduSummary.textContent = `Showing ${p.from||0}-${p.to||0} of ${p.total||0}`;
    renderGarduPager(p);
  }

  function renderGarduPager(p){
    garduNav.innerHTML = '';
    const mk = (label, disabled, page) => `<button class="btn btn-outline-secondary ${disabled?'disabled':''}" data-page="${page}">${label}</button>`;
    garduNav.insertAdjacentHTML('beforeend', mk('«', !p.prev_page_url, 1));
    garduNav.insertAdjacentHTML('beforeend', mk('‹', !p.prev_page_url, Math.max(1,(p.current_page||1)-1)));
    garduNav.insertAdjacentHTML('beforeend', `<span class="btn btn-outline-secondary disabled">${p.current_page||1}/${p.last_page||1}</span>`);
    garduNav.insertAdjacentHTML('beforeend', mk('›', !p.next_page_url, Math.min(p.last_page||1,(p.current_page||1)+1)));
    garduNav.insertAdjacentHTML('beforeend', mk('»', !p.next_page_url, p.last_page||1));
    garduNav.querySelectorAll('button[data-page]').forEach(b=>{
      b.addEventListener('click', ()=>{ if(b.classList.contains('disabled')) return; garduState.page=parseInt(b.dataset.page,10)||1; fetchGarduList(); });
    });
  }

  garduSelectAll.addEventListener('change', ()=>{ garduTbody.querySelectorAll('.row-check').forEach(cb=> cb.checked = garduSelectAll.checked); });
  garduBulkBtn.addEventListener('click', ()=>{
    const act = garduBulkSel.value; if(!act) return;
    const ids = Array.from(garduTbody.querySelectorAll('.row-check:checked')).map(cb=>cb.closest('tr').dataset.id);
    if(ids.length===0) return;
    fetch('/api/gardu-induk/bulk', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}, body: JSON.stringify({ action: act, ids }) })
      .then(r=>r.json()).then(()=> fetchGarduList());
  });

  garduCreateBtn.addEventListener('click', ()=> openGarduModal());
  window.garduEdit = function(id){
    const tr = garduTbody.querySelector(`tr[data-id="${id}"]`);
    openGarduModal({ id,
      nama_gi: tr.children[1].textContent.trim(),
      alamat: tr.children[2].textContent.trim(),
      kode_gi: tr.children[3].textContent.trim(),
      kapasitas_mva: tr.children[4].textContent.trim(),
      tegangan_level_in_kv: tr.children[5].textContent.trim(),
      status: tr.children[6].textContent.trim().toLowerCase(),
      tahun_operasi: tr.children[7].textContent.trim(),
    });
    return false;
  };
  window.garduDelete = function(id){ if(!confirm('Delete this gardu induk?')) return false; fetch('/api/gardu-induk/delete',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,}, body: JSON.stringify({id})}).then(r=>r.json()).then(()=>fetchGarduList()); return false; };

  // Gardu Induk Modal
  const garduModalEl = document.createElement('div');
  garduModalEl.className='modal fade'; garduModalEl.id='garduModal'; garduModalEl.innerHTML=`
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Data Gardu Induk</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <form id="garduForm">
          <input type="hidden" id="gdId">
          <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Nama Gardu Induk *</label><input type="text" class="form-control form-control-sm" id="gdNama" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Kode Gardu *</label><input type="text" class="form-control form-control-sm" id="gdKode" required></div>
            <div class="col-12 mb-3"><label class="form-label">Alamat *</label><textarea class="form-control form-control-sm" id="gdAlamat" rows="2" required></textarea></div>
            <div class="col-md-4 mb-3"><label class="form-label">Kapasitas (MVA)</label>
              <select class="form-select form-select-sm" id="gdKapasitas">
                <option value="">Pilih Kapasitas</option>
                <option value="10">10 MVA</option>
                <option value="20">20 MVA</option>
                <option value="30">30 MVA</option>
                <option value="40">40 MVA</option>
                <option value="50">50 MVA</option>
                <option value="60">60 MVA</option>
                <option value="75">75 MVA</option>
                <option value="100">100 MVA</option>
                <option value="125">125 MVA</option>
                <option value="150">150 MVA</option>
                <option value="200">200 MVA</option>
              </select>
            </div>
            <div class="col-md-4 mb-3"><label class="form-label">Tegangan Level In (kV)</label>
              <select class="form-select form-select-sm" id="gdTeganganIn">
                <option value="">Pilih Tegangan</option>
                <option value="20">20 kV</option>
                <option value="150">150 kV</option>
                <option value="275">275 kV</option>
                <option value="500">500 kV</option>
              </select>
            </div>
            <div class="col-md-4 mb-3"><label class="form-label">Tegangan Level Out (kV)</label>
              <select class="form-select form-select-sm" id="gdTeganganOut">
                <option value="">Pilih Tegangan</option>
                <option value="20">20 kV</option>
                <option value="150">150 kV</option>
                <option value="275">275 kV</option>
                <option value="500">500 kV</option>
              </select>
            </div>
            <div class="col-md-4 mb-3"><label class="form-label">Status *</label>
              <select class="form-select form-select-sm" id="gdStatus" required>
                <option value="">Pilih Status</option>
                <option value="aktif">Aktif</option>
                <option value="non_aktif">Non Aktif</option>
                <option value="maintenance">Maintenance</option>
              </select>
            </div>
            <div class="col-md-4 mb-3"><label class="form-label">Tahun Operasi</label><input type="number" class="form-control form-control-sm" id="gdTahun" min="1900" max="2100"></div>
            <div class="col-md-4 mb-3"><label class="form-label">Jumlah Bay</label><input type="number" class="form-control form-control-sm" id="gdBay" min="1"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Kabupaten</label><input type="text" class="form-control form-control-sm" id="gdKabupaten"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Provinsi</label><input type="text" class="form-control form-control-sm" id="gdProvinsi"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Koordinat Lat</label><input type="number" step="0.000001" class="form-control form-control-sm" id="gdLat" placeholder="-6.200000"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Koordinat Long</label><input type="number" step="0.000001" class="form-control form-control-sm" id="gdLong" placeholder="106.816666"></div>
            <div class="col-12 mb-3"><label class="form-label">Keterangan</label><textarea class="form-control form-control-sm" id="gdKeterangan" rows="2"></textarea></div>
          </div>
        </form>
      </div>
      <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary" id="gdSave">Save</button></div>
    </div>
  </div>`;
  document.body.appendChild(garduModalEl);
  const garduModal = new bootstrap.Modal(garduModalEl, { backdrop: 'static', keyboard: false });
  document.getElementById('gdSave').addEventListener('click', ()=>{
    const payload={ id: document.getElementById('gdId').value||undefined,
      kode_gi: document.getElementById('gdKode').value.trim(),
      nama_gi: document.getElementById('gdNama').value.trim(),
      alamat: document.getElementById('gdAlamat').value.trim(),
      koordinat_lat: document.getElementById('gdLat').value?parseFloat(document.getElementById('gdLat').value):null,
      koordinat_long: document.getElementById('gdLong').value?parseFloat(document.getElementById('gdLong').value):null,
      kabupaten: document.getElementById('gdKabupaten').value.trim()||null,
      provinsi: document.getElementById('gdProvinsi').value.trim()||null,
      tegangan_level_in_kv: document.getElementById('gdTeganganIn').value?parseFloat(document.getElementById('gdTeganganIn').value):null,
      tegangan_level_out_kv: document.getElementById('gdTeganganOut').value?parseFloat(document.getElementById('gdTeganganOut').value):null,
      jumlah_bay: document.getElementById('gdBay').value?parseInt(document.getElementById('gdBay').value):null,
      kapasitas_mva: document.getElementById('gdKapasitas').value?parseFloat(document.getElementById('gdKapasitas').value):null,
      tahun_operasi: document.getElementById('gdTahun').value?parseInt(document.getElementById('gdTahun').value):null,
      status: document.getElementById('gdStatus').value.trim(),
      keterangan: document.getElementById('gdKeterangan').value.trim()||null };
    if(!payload.nama_gi || !payload.alamat || !payload.status || !payload.kode_gi) {
      alert('Nama Gardu Induk, Kode Gardu, Alamat, dan Status harus diisi!');
      return;
    }
    fetch('/api/gardu-induk/save',{ method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify(payload)})
      .then(r=>r.json())
      .then(result=>{ 
        garduModal.hide(); 
        fetchGarduList();
        if(result.error) alert('Error: ' + result.error);
      })
      .catch(error=>{
        console.error('Save error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
      });
  });
  function openGarduModal(data){ 
    document.getElementById('gdId').value=data?.id||''; 
    document.getElementById('gdNama').value=data?.nama_gi||''; 
    document.getElementById('gdKode').value=data?.kode_gi||''; 
    document.getElementById('gdAlamat').value=data?.alamat||''; 
    document.getElementById('gdKapasitas').value=data?.kapasitas_mva||''; 
    document.getElementById('gdTeganganIn').value=data?.tegangan_level_in_kv||''; 
    document.getElementById('gdTeganganOut').value=data?.tegangan_level_out_kv||''; 
    document.getElementById('gdStatus').value=data?.status||''; 
    document.getElementById('gdTahun').value=data?.tahun_operasi||''; 
    document.getElementById('gdBay').value=data?.jumlah_bay||''; 
    document.getElementById('gdKabupaten').value=data?.kabupaten||''; 
    document.getElementById('gdProvinsi').value=data?.provinsi||''; 
    document.getElementById('gdLat').value=data?.koordinat_lat||''; 
    document.getElementById('gdLong').value=data?.koordinat_long||''; 
    document.getElementById('gdKeterangan').value=data?.keterangan||''; 
    garduModal.show(); 
  }

  // Gardu Induk Events
  garduSearch.addEventListener('input', ()=>{ garduState.page=1; garduState.q=garduSearch.value; fetchGarduList(); });
  garduPerPage.addEventListener('change', ()=>{ garduState.page=1; garduState.per=parseInt(garduPerPage.value,10)||10; fetchGarduList(); });

  // init gardu
  fetchGarduList();
});
</script>
@endsection


