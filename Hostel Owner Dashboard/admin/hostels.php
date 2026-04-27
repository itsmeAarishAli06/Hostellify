<?php include("header.php"); ?>

<style>
body{
  overflow-x: hidden;
}  
.tbl-card { border-radius: 14px; border: 1px solid #e9ecef; box-shadow: 0 4px 18px rgba(0,0,0,0.06); overflow: hidden; }
.tbl-card-head { padding: 15px 20px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
.tbl-card-head h6 { margin: 0; font-weight: 700; font-size: .95rem; }
.tbl th { font-size: .74rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #888; background: #f8f9fa; padding: 10px 14px; white-space: nowrap; }
.tbl td { padding: 12px 14px; font-size: .87rem; vertical-align: middle; border-color: #f0f0f0; }
.tbl tbody tr:hover { background: #f8f9fa; }
.badge-s { padding: 4px 12px; border-radius: 50px; font-size: .73rem; font-weight: 700; }
.badge-s.success { background: rgba(40,167,69,.13); color: #1a6e30; }
.badge-s.danger  { background: rgba(220,53,69,.13); color: #961b24; }
.badge-s.warning { background: rgba(255,193,7,.18); color: #856404; }
.badge-type-ac  { background: rgba(0,188,212,.12); color: #007b8a; padding: 4px 11px; border-radius: 50px; font-size: .73rem; font-weight: 700; }
.badge-type-nac { background: rgba(108,117,125,.12); color: #495057; padding: 4px 11px; border-radius: 50px; font-size: .73rem; font-weight: 700; }
.room-id { color: #00bcd4; font-weight: 700; font-size: .95rem; }
.btn-icon { width: 30px; height: 30px; border: none; border-radius: 7px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; font-size: .78rem; transition: all .15s; }
.btn-icon.edit { background: rgba(0,188,212,.1); color: #00bcd4; }
.btn-icon.edit:hover { background: #00bcd4; color: #fff; }
.btn-icon.del { background: rgba(220,53,69,.1); color: #dc3545; }
.btn-icon.del:hover { background: #dc3545; color: #fff; }
.btn-add { background: #00bcd4; color: #fff; border: none; padding: 8px 18px; border-radius: 9px; font-weight: 700; font-size: .85rem; cursor: pointer; transition: all .2s; display: inline-flex; align-items: center; gap: 6px; }
.btn-add:hover { background: #0097a7; }
.filter-inp { border: 1.5px solid #dee2e6; border-radius: 8px; padding: 6px 12px; font-size: .83rem; outline: none; transition: border .2s; font-family: inherit; }
.filter-inp:focus { border-color: #00bcd4; }
.stat-mini { text-align: center; padding: 12px; background: #fff; border-radius: 11px; border: 1px solid #e9ecef; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
.stat-mini .v { font-weight: 700; font-size: 1.5rem; }
.stat-mini .l { font-size: .73rem; color: #888; font-weight: 600; }
.occ-bar { height: 6px; background: #e9ecef; border-radius: 10px; overflow: hidden; min-width: 55px; }
.occ-fill { height: 100%; border-radius: 10px; }
/* Modal */
.modal-content { border-radius: 14px; border: none; }
.modal-header { background: #1a1f2e; color: #fff; border-radius: 14px 14px 0 0; }
.modal-header .btn-close { filter: invert(1); }
.modal-title { font-weight: 700; font-size: 1rem; }
.input-modal { border: 1.5px solid #dee2e6; border-radius: 9px; padding: 8px 12px; font-size: .88rem; width: 100%; outline: none; font-family: inherit; transition: border .2s; }
.input-modal:focus { border-color: #00bcd4; box-shadow: 0 0 0 3px rgba(0,188,212,.1); }
.btn-modal-save { background: #00bcd4; color: #fff; border: none; padding: 9px 22px; border-radius: 9px; font-weight: 700; cursor: pointer; font-size: .88rem; transition: all .2s; }
.btn-modal-save:hover { background: #0097a7; }
</style>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 class="fw-bold mb-1" style="color:#1a202c;">Rooms Management</h4>
    <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0" style="font-size:.8rem;">
      <li class="breadcrumb-item"><a href="index.php" style="color:#00bcd4;">Dashboard</a></li>
      <li class="breadcrumb-item active">Rooms</li>
    </ol></nav>
  </div>
</div>

<!-- Mini Stats -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3"><div class="stat-mini"><div class="v" style="color:#1a202c;">40</div><div class="l">Total Rooms</div></div></div>
  <div class="col-6 col-md-3"><div class="stat-mini"><div class="v" style="color:#28a745;">28</div><div class="l">Occupied</div></div></div>
  <div class="col-6 col-md-3"><div class="stat-mini"><div class="v" style="color:#00bcd4;">12</div><div class="l">Available</div></div></div>
  <div class="col-6 col-md-3"><div class="stat-mini"><div class="v" style="color:#ffc107;">3</div><div class="l">Maintenance</div></div></div>
</div>

<!-- Table Card -->
<div class="card tbl-card">
  <div class="tbl-card-head">
    <h6><i class="fas fa-door-open me-2" style="color:#00bcd4;"></i>All Rooms</h6>
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <input type="text" class="filter-inp" placeholder="Search room ID..." id="searchRoom" oninput="filterRooms()"/>
      <select class="filter-inp" id="filterType" onchange="filterRooms()">
        <option value="">All Types</option>
        <option value="AC">AC</option>
        <option value="Non AC">Non AC</option>
      </select>
      <select class="filter-inp" id="filterStatus" onchange="filterRooms()">
        <option value="">All Status</option>
        <option value="Available">Available</option>
        <option value="Full">Full</option>
        <option value="Maintenance">Maintenance</option>
      </select>
      <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addRoomModal">
        <i class="fas fa-plus"></i> Add Room
      </button>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table tbl mb-0" id="roomTable">
      <thead>
        <tr>
          <th>Room ID</th>
          <th>Type</th>
          <th>Capacity</th>
          <th>Occupied</th>
          <th>Occupancy</th>
          <th>Status</th>
          <th>Rent/Month</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="roomBody">
        <!-- Rows injected by JS below -->
      </tbody>
    </table>
  </div>
  <div class="d-flex justify-content-between align-items-center px-3 py-2" style="border-top:1px solid #f0f0f0;font-size:.8rem;color:#888;">
    <span id="roomCount">Showing all rooms</span>
    <div class="d-flex gap-1">
      <button class="btn btn-sm btn-outline-secondary">← Prev</button>
      <button class="btn btn-sm" style="background:#00bcd4;color:#fff;border-color:#00bcd4;">1</button>
      <button class="btn btn-sm btn-outline-secondary">Next →</button>
    </div>
  </div>
</div>

<!-- ADD ROOM MODAL -->
<div class="modal fade" id="addRoomModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-plus-circle me-2" style="color:#00bcd4;"></i>Add New Room</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row g-3">
          <div class="col-6"><label style="font-weight:700;font-size:.82rem;display:block;margin-bottom:5px;">Room Number</label><input type="text" class="input-modal" placeholder="e.g. R-041"/></div>
          <div class="col-6"><label style="font-weight:700;font-size:.82rem;display:block;margin-bottom:5px;">Floor</label><input type="text" class="input-modal" placeholder="Ground / 1st / 2nd"/></div>
          <div class="col-6"><label style="font-weight:700;font-size:.82rem;display:block;margin-bottom:5px;">Room Type</label><select class="input-modal"><option>AC</option><option>Non AC</option></select></div>
          <div class="col-6"><label style="font-weight:700;font-size:.82rem;display:block;margin-bottom:5px;">Capacity</label><input type="number" class="input-modal" placeholder="Max students" min="1"/></div>
          <div class="col-6"><label style="font-weight:700;font-size:.82rem;display:block;margin-bottom:5px;">Monthly Rent (PKR)</label><input type="number" class="input-modal" placeholder="e.g. 8000"/></div>
          <div class="col-6"><label style="font-weight:700;font-size:.82rem;display:block;margin-bottom:5px;">Status</label><select class="input-modal"><option>Available</option><option>Full</option><option>Maintenance</option></select></div>
        </div>
      </div>
      <div class="modal-footer border-0 px-4 pb-4">
        <button class="btn-modal-save"><i class="fas fa-save me-2"></i>Save Room</button>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script>
const rooms = [
  { id:'R-001', type:'AC',     cap:4, occ:4, status:'Full',        rent:10000 },
  { id:'R-002', type:'Non AC', cap:3, occ:2, status:'Available',   rent:6500  },
  { id:'R-003', type:'AC',     cap:2, occ:1, status:'Available',   rent:12000 },
  { id:'R-004', type:'Non AC', cap:4, occ:4, status:'Full',        rent:6000  },
  { id:'R-005', type:'AC',     cap:2, occ:0, status:'Available',   rent:11000 },
  { id:'R-006', type:'Non AC', cap:3, occ:3, status:'Full',        rent:6500  },
  { id:'R-007', type:'Non AC', cap:4, occ:0, status:'Maintenance', rent:6000  },
  { id:'R-008', type:'AC',     cap:2, occ:2, status:'Full',        rent:12000 },
  { id:'R-009', type:'Non AC', cap:3, occ:1, status:'Available',   rent:6500  },
  { id:'R-010', type:'AC',     cap:4, occ:3, status:'Available',   rent:10000 },
];

function statusBadge(s){
  if(s==='Full')        return '<span class="badge-s danger">Full</span>';
  if(s==='Available')   return '<span class="badge-s success">Available</span>';
  return '<span class="badge-s warning">Maintenance</span>';
}
function typeBadge(t){
  return t==='AC'
    ? `<span class="badge-type-ac"><i class="fas fa-snowflake me-1"></i>AC</span>`
    : `<span class="badge-type-nac"><i class="fas fa-fan me-1"></i>Non AC</span>`;
}
function occColor(o,c){ const p=(o/c)*100; return p===100?'#dc3545':p>=75?'#ffc107':'#28a745'; }

function renderRooms(data){
  const tbody = document.getElementById('roomBody');
  tbody.innerHTML = data.map(r=>{
    const pct = Math.round((r.occ/r.cap)*100);
    return `<tr>
      <td><span class="room-id">${r.id}</span></td>
      <td>${typeBadge(r.type)}</td>
      <td><i class="fas fa-users me-1" style="color:#ccc;"></i>${r.cap}</td>
      <td>${r.occ} / ${r.cap}</td>
      <td><div style="display:flex;align-items:center;gap:7px;">
        <div class="occ-bar"><div class="occ-fill" style="width:${pct}%;background:${occColor(r.occ,r.cap)};"></div></div>
        <span style="font-size:.75rem;color:#888;">${pct}%</span>
      </div></td>
      <td>${statusBadge(r.status)}</td>
      <td style="font-weight:700;">PKR ${r.rent.toLocaleString()}</td>
      <td>
        <button class="btn-icon edit me-1" title="Edit"><i class="fas fa-edit"></i></button>
        <button class="btn-icon del" title="Delete" onclick="this.closest('tr').remove()"><i class="fas fa-trash"></i></button>
      </td>
    </tr>`;
  }).join('');
  document.getElementById('roomCount').textContent = `Showing ${data.length} room${data.length!==1?'s':''}`;
}

function filterRooms(){
  const q      = document.getElementById('searchRoom').value.toLowerCase();
  const type   = document.getElementById('filterType').value;
  const status = document.getElementById('filterStatus').value;
  renderRooms(rooms.filter(r=>
    r.id.toLowerCase().includes(q) &&
    (type===''   || r.type===type) &&
    (status==='' || r.status===status)
  ));
}

renderRooms(rooms);
</script>

<?php include("footer.php"); ?>