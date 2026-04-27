<?php 
ob_start();
session_start();

require_once "../../config.php";

  $stmt = $conn->prepare("SELECT id FROM hostel WHERE owner_id = ?"); // Removed extra )
  $stmt->bind_param('i', $_SESSION['owner_id']);
  $stmt->execute();

  $result = $stmt->get_result();
  $hostel = $result->fetch_assoc();
  $hostel_id = $hostel['id'] ?? null; 
  if ($hostel_id == null) {
    header("location: hostel_profile.php");
    exit();
  }

include("header.php");  
// --- STAT COUNTS ---

$total_res       = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM room WHERE hostel_id = $hostel_id");
$total           = $total_res ? mysqli_fetch_assoc($total_res)['cnt'] : 0;

$occupied_res    = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM room WHERE hostel_id = $hostel_id AND status = 'Full'");
$occupied_count  = $occupied_res ? mysqli_fetch_assoc($occupied_res)['cnt'] : 0;

$available_res   = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM room WHERE hostel_id = $hostel_id AND status = 'Available'");
$available_count = $available_res ? mysqli_fetch_assoc($available_res)['cnt'] : 0;

$maint_res       = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM room WHERE hostel_id = $hostel_id AND status = 'Maintenance'");
$maint_count     = $maint_res ? mysqli_fetch_assoc($maint_res)['cnt'] : 0;

// --- ALL ROOMS --- PK column is 'id' (not room_id)
$rooms_res   = mysqli_query($conn, "SELECT * FROM room WHERE hostel_id = $hostel_id ORDER BY id ASC");
$rooms_count = mysqli_num_rows($rooms_res); // store BEFORE the while loop consumes the pointer
?>

<style>
body{
  overflow-x: hidden;
}  
.tbl-card { border-radius: 14px; border: 1px solid #e9ecef; box-shadow: 0 4px 18px rgba(0,0,0,0.06); overflow: hidden; background: #fff; }
.tbl-card-head { padding: 16px 20px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
.tbl-card-head h6 { margin: 0; font-weight: 700; font-size: .95rem; }
.tbl thead th { font-size: .74rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #888; background: #f8f9fa; padding: 11px 16px; white-space: nowrap; border-bottom: 2px solid #e9ecef; }
.tbl tbody td { padding: 13px 16px; font-size: .88rem; vertical-align: middle; border-color: #f4f4f4; }
.tbl tbody tr:hover { background: #f8fdff; }
.badge-status { padding: 5px 13px; border-radius: 50px; font-size: .73rem; font-weight: 700; }
.badge-status.available   { background: rgba(40,167,69,.12);  color: #1a6e30; }
.badge-status.full        { background: rgba(220,53,69,.12);  color: #961b24; }
.badge-status.maintenance { background: rgba(255,193,7,.18);  color: #856404; }
.badge-ac  { background: rgba(0,188,212,.12); color: #007b8a; padding: 5px 12px; border-radius: 50px; font-size: .73rem; font-weight: 700; }
.badge-nac { background: rgba(108,117,125,.1); color: #495057; padding: 5px 12px; border-radius: 50px; font-size: .73rem; font-weight: 700; }
.room-id { font-weight: 800; font-size: .95rem; color: #00bcd4; }
.occ-wrap { display:flex; align-items:center; gap:8px; }
.occ-bar  { flex:1; min-width:70px; height:7px; background:#e9ecef; border-radius:20px; overflow:hidden; }
.occ-fill { height:100%; border-radius:20px; }
.occ-pct  { font-size:.75rem; color:#888; font-weight:700; min-width:28px; }
.btn-edit { width:32px; height:32px; border:none; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; background:rgba(0,188,212,.1); color:#00bcd4; cursor:pointer; font-size:.82rem; transition:all .18s; }
.btn-edit:hover { background:#00bcd4; color:#fff; }
.btn-del  { width:32px; height:32px; border:none; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; background:rgba(220,53,69,.1); color:#dc3545; cursor:pointer; font-size:.82rem; transition:all .18s; }
.btn-del:hover { background:#dc3545; color:#fff; }
.btn-add { background:#00bcd4; color:#fff; border:none; padding:9px 20px; border-radius:9px; font-weight:700; font-size:.85rem; cursor:pointer; display:inline-flex; align-items:center; gap:7px; transition:all .2s; }
.btn-add:hover { background:#0097a7; box-shadow:0 4px 14px rgba(0,188,212,.3); }
.filter-inp { border:1.5px solid #dee2e6; border-radius:8px; padding:7px 12px; font-size:.83rem; outline:none; transition:border .2s; font-family:inherit; }
.filter-inp:focus { border-color:#00bcd4; }
.stat-mini { text-align:center; padding:14px 10px; background:#fff; border-radius:12px; border:1px solid #e9ecef; box-shadow:0 2px 10px rgba(0,0,0,0.05); }
.stat-mini .v { font-weight:800; font-size:1.6rem; line-height:1.1; }
.stat-mini .l { font-size:.73rem; color:#888; font-weight:600; margin-top:3px; }
.modal-content { border-radius:14px; border:none; box-shadow:0 20px 50px rgba(0,0,0,0.15); }
.modal-header  { background:#1a1f2e; color:#fff; border-radius:14px 14px 0 0; padding:16px 22px; }
.modal-header .btn-close { filter:invert(1); }
.modal-title { font-weight:700; font-size:1rem; }
.inp { border:1.5px solid #dee2e6; border-radius:9px; padding:8px 12px; font-size:.88rem; width:100%; outline:none; font-family:inherit; transition:border .2s; }
.inp:focus { border-color:#00bcd4; box-shadow:0 0 0 3px rgba(0,188,212,.1); }
.form-lbl { font-weight:700; font-size:.82rem; display:block; margin-bottom:5px; color:#333; }
.btn-save-modal { background:#00bcd4; color:#fff; border:none; padding:9px 22px; border-radius:9px; font-weight:700; font-size:.88rem; cursor:pointer; transition:all .2s; }
.btn-save-modal:hover { background:#0097a7; }
</style>

<!-- PAGE HEADER -->
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 class="fw-bold mb-1" style="color:#1a202c;">Rooms Management</h4>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0" style="font-size:.8rem;">
        <li class="breadcrumb-item"><a href="index.php" style="color:#00bcd4;">Dashboard</a></li>
        <li class="breadcrumb-item active">Rooms</li>
      </ol>
    </nav>
  </div>
  <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addRoomModal">
    <i class="fas fa-plus"></i> Add Room
  </button>
</div>

<!-- STAT CARDS -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="stat-mini">
      <div class="v" style="color:#1a202c;"><?= $total ?></div>
      <div class="l"><i class="fas fa-door-open me-1" style="color:#00bcd4;"></i>Total Rooms</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-mini">
      <div class="v" style="color:#28a745;"><?= $occupied_count ?></div>
      <div class="l"><i class="fas fa-user-check me-1" style="color:#28a745;"></i>Occupied</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-mini">
      <div class="v" style="color:#00bcd4;"><?= $available_count ?></div>
      <div class="l"><i class="fas fa-check-circle me-1" style="color:#00bcd4;"></i>Available</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-mini">
      <div class="v" style="color:#ffc107;"><?= $maint_count ?></div>
      <div class="l"><i class="fas fa-tools me-1" style="color:#ffc107;"></i>Maintenance</div>
    </div>
  </div>
</div>

<!-- ROOMS TABLE -->
<div class="card tbl-card">
  <div class="tbl-card-head">
    <h6><i class="fas fa-list me-2" style="color:#00bcd4;"></i>All Rooms</h6>
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <input type="text" class="filter-inp" placeholder="Search Room ID..." id="searchRoom" oninput="filterRooms()"/>
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
    </div>
  </div>

  <div class="table-responsive">
    <table class="table tbl mb-0">
      <thead>
        <tr>
          <th>Room Number</th>
          <th>Type (AC / Non AC)</th>
          <th>Capacity</th>
          <th>Occupied</th>
          <th>Occupancy</th>
          <th>Status</th>
          <th>Rent / Month</th>
          <th style="text-align:center;">Edit</th>
          <th style="text-align:center;">Delete</th>
        </tr>
      </thead>
      <tbody id="roomBody">

        <?php if ($rooms_count === 0): ?>
          <tr>
            <td colspan="9" class="text-center py-4" style="color:#aaa;">
              <i class="fas fa-door-open me-2"></i>No rooms added yet.
            </td>
          </tr>
        <?php else: ?>
          <?php while ($r = mysqli_fetch_assoc($rooms_res)):
            $room_id_display = 'R-' . str_pad($r['room_number'], 3, '0', STR_PAD_LEFT);
            $pct       = $r['capacity'] > 0 ? round(($r['occupied'] / $r['capacity']) * 100) : 0;
            $bar_color = $pct === 100 ? '#dc3545' : ($pct >= 70 ? '#ffc107' : '#28a745');

            $type_badge = $r['room_type'] === 'AC'
              ? '<span class="badge-ac"><i class="fas fa-snowflake me-1"></i>AC</span>'
              : '<span class="badge-nac"><i class="fas fa-fan me-1"></i>Non AC</span>';

            if ($r['status'] === 'Available') {
              $status_badge = '<span class="badge-status available"><i class="fas fa-check me-1"></i>Available</span>';
            } elseif ($r['status'] === 'Full') {
              $status_badge = '<span class="badge-status full"><i class="fas fa-times me-1"></i>Full</span>';
            } else {
              $status_badge = '<span class="badge-status maintenance"><i class="fas fa-tools me-1"></i>Maintenance</span>';
            }
          ?>
          <tr
            data-id="<?= $room_id_display ?>"
            data-type="<?= htmlspecialchars($r['room_type']) ?>"
            data-status="<?= htmlspecialchars($r['status']) ?>"
          >
            <td><span class="room-id"><?= $room_id_display ?></span></td>
            <td><?= $type_badge ?></td>
            <td><i class="fas fa-users me-1" style="color:#ccc;"></i><?= $r['capacity'] ?></td>
            <td><strong><?= $r['occupied'] ?></strong> <span style="color:#aaa;">/ <?= $r['capacity'] ?></span></td>
            <td>
              <div class="occ-wrap">
                <div class="occ-bar">
                  <div class="occ-fill" style="width:<?= $pct ?>%;background:<?= $bar_color ?>;"></div>
                </div>
                <span class="occ-pct"><?= $pct ?>%</span>
              </div>
            </td>
            <td><?= $status_badge ?></td>
            <td><strong>PKR <?= number_format($r['price_monthly']) ?></strong></td>
            <td style="text-align:center;">
              <button class="btn-edit" title="Edit"
                onclick="openEdit(
                  <?= $r['id'] ?>,
                  '<?= $room_id_display ?>',
                  '<?= $r['room_type'] ?>',
                  <?= $r['capacity'] ?>,
                  <?= $r['price_monthly'] ?>,
                  '<?= $r['status'] ?>'
                )">
                <i class="fas fa-edit"></i>
              </button>
            </td>
            <td style="text-align:center;">
              <button class="btn-del" title="Delete"
                onclick="deleteRoom(<?= $r['id'] ?>, '<?= $room_id_display ?>')">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          <?php endwhile; ?>
        <?php endif; ?>

      </tbody>
    </table>
  </div>

  <div class="d-flex justify-content-between align-items-center px-4 py-2"
       style="border-top:1px solid #f0f0f0; font-size:.8rem; color:#888;">
    <span id="roomCount">Showing <?= $rooms_count ?> room<?= $rooms_count !== 1 ? 's' : '' ?></span>
  </div>
</div>


<!-- ======== ADD ROOM MODAL ======== -->
<div class="modal fade" id="addRoomModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-plus-circle me-2" style="color:#00bcd4;"></i>Add New Room</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form method="POST" action="../../Backend/backend.php">
          <div class="row g-3">
            <div class="col-6">
              <label class="form-lbl">Room Number <span style="color:#dc3545;">*</span></label>
              <input type="text" class="inp" name="room_number" placeholder="e.g. 101" required/>
            </div>
            <div class="col-6">
              <label class="form-lbl">Type <span style="color:#dc3545;">*</span></label>
              <select class="inp" name="room_type" required>
                <option value="">-- Select --</option>
                <option value="AC">AC</option>
                <option value="Non AC">Non AC</option>
              </select>
            </div>
            <div class="col-6">
              <label class="form-lbl">Capacity <span style="color:#dc3545;">*</span></label>
              <input type="number" class="inp" name="capacity" placeholder="Max students" min="1" required/>
            </div>
            <div class="col-6">
              <label class="form-lbl">Monthly Rent (PKR) <span style="color:#dc3545;">*</span></label>
              <input type="number" class="inp" name="price_monthly" placeholder="e.g. 8000" required/>
            </div>
            <div class="col-12">
              <label class="form-lbl">Status</label>
              <select class="inp" name="status">
                <option value="Available">Available</option>
                <option value="Maintenance">Maintenance</option>
              </select>
            </div>
          </div>
          <div class="mt-4 d-flex gap-2">
            <button type="submit" name = "save_room" class="btn-save-modal"><i class="fas fa-save me-2"></i>Save Room</button>
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- ======== EDIT ROOM MODAL ======== -->
<div class="modal fade" id="editRoomModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-edit me-2" style="color:#00bcd4;"></i>Edit Room</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form method="POST" action="../../Backend/backend.php">
          <input type="hidden" name="room_id" id="edit_db_id"/>
          <div class="row g-3">
            <div class="col-6">
              <label class="form-lbl">Room ID</label>
              <input type="text" class="inp" id="edit_room_display" readonly style="background:#f8f9fa;"/>
            </div>
            <div class="col-6">
              <label class="form-lbl">Type</label>
              <select class="inp" name="room_type" id="edit_type">
                <option value="AC">AC</option>
                <option value="Non AC">Non AC</option>
              </select>
            </div>
            <div class="col-6">
              <label class="form-lbl">Capacity</label>
              <input type="number" class="inp" name="capacity" id="edit_capacity" min="1"/>
            </div>
            <div class="col-6">
              <label class="form-lbl">Rent (PKR)</label>
              <input type="number" class="inp" name="price_monthly" id="edit_rent"/>
            </div>
            <div class="col-12">
              <label class="form-lbl">Status</label>
              <select class="inp" name="status" id="edit_status">
                <option value="Available">Available</option>
                <option value="Full">Full</option>
                <option value="Maintenance">Maintenance</option>
              </select>
            </div>
          </div>
          <div class="mt-4 d-flex gap-2">
            <button type="submit" name="room_data_update" class="btn-save-modal"><i class="fas fa-save me-2"></i>Update Room</button>
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- SUCCESS TOAST -->
<div id="toast" style="position:fixed;bottom:24px;right:24px;background:#00bcd4;color:#fff;
  padding:12px 22px;border-radius:10px;font-weight:700;font-size:.88rem;
  box-shadow:0 6px 20px rgba(0,188,212,.4);opacity:0;transition:opacity .3s;z-index:9999;">
  <i class="fas fa-check-circle me-2"></i><span id="toastMsg">Done!</span>
</div>


<script>
function filterRooms() {
  const q      = document.getElementById('searchRoom').value.toLowerCase();
  const type   = document.getElementById('filterType').value;
  const status = document.getElementById('filterStatus').value;

  const rows = document.querySelectorAll('#roomBody tr[data-id]');
  let visible = 0;

  rows.forEach(row => {
    const matchId     = row.dataset.id.toLowerCase().includes(q);
    const matchType   = type   === '' || row.dataset.type   === type;
    const matchStatus = status === '' || row.dataset.status === status;

    if (matchId && matchType && matchStatus) {
      row.style.display = '';
      visible++;
    } else {
      row.style.display = 'none';
    }
  });

  document.getElementById('roomCount').textContent =
    `Showing ${visible} room${visible !== 1 ? 's' : ''}`;
}

function openEdit(dbid, displayId, type, capacity, rent, status) {
  document.getElementById('edit_db_id').value        = dbid;
  document.getElementById('edit_room_display').value = displayId;
  document.getElementById('edit_type').value         = type;
  document.getElementById('edit_capacity').value     = capacity;
  document.getElementById('edit_rent').value         = rent;
  document.getElementById('edit_status').value       = status;
  new bootstrap.Modal(document.getElementById('editRoomModal')).show();
}

function deleteRoom(dbid, label) {
  if (!confirm(`Delete room ${label}? This cannot be undone.`)) return;
  window.location.href = `../../Backend/backend.php?del_room=${dbid}`;
}
</script>

<?php include("footer.php"); ?>


















