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
.avatar-c { width: 34px; height: 34px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: .78rem; color: #fff; flex-shrink: 0; }
.btn-approve { background: rgba(40,167,69,.1); color: #1a6e30; border: 1.5px solid rgba(40,167,69,.25); padding: 5px 14px; border-radius: 7px; font-weight: 700; font-size: .78rem; cursor: pointer; transition: all .2s; }
.btn-approve:hover { background: #28a745; color: #fff; border-color: #28a745; }
.btn-reject  { background: rgba(220,53,69,.1); color: #961b24; border: 1.5px solid rgba(220,53,69,.25); padding: 5px 14px; border-radius: 7px; font-weight: 700; font-size: .78rem; cursor: pointer; transition: all .2s; }
.btn-reject:hover  { background: #dc3545; color: #fff; border-color: #dc3545; }
.btn-view-d { background: rgba(0,188,212,.1); color: #00bcd4; border: 1.5px solid rgba(0,188,212,.2); padding: 5px 11px; border-radius: 7px; font-weight: 700; font-size: .78rem; cursor: pointer; transition: all .2s; }
.btn-view-d:hover { background: #00bcd4; color: #fff; }
.filter-inp { border: 1.5px solid #dee2e6; border-radius: 8px; padding: 6px 12px; font-size: .83rem; outline: none; transition: border .2s; font-family: inherit; }
.filter-inp:focus { border-color: #00bcd4; }
.tab-btn { padding: 6px 16px; border-radius: 8px; border: 1.5px solid #dee2e6; background: #fff; font-weight: 700; font-size: .8rem; cursor: pointer; transition: all .2s; font-family: inherit; }
.tab-btn.active { background: #00bcd4; color: #fff; border-color: #00bcd4; }
.tab-btn:not(.active):hover { border-color: #00bcd4; color: #00bcd4; }
.stat-mini { text-align: center; padding: 12px; background: #fff; border-radius: 11px; border: 1px solid #e9ecef; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
.stat-mini .v { font-weight: 700; font-size: 1.5rem; }
.stat-mini .l { font-size: .73rem; color: #888; font-weight: 600; }
.modal-content { border-radius: 14px; border: none; }
.modal-header { background: #1a1f2e; color: #fff; border-radius: 14px 14px 0 0; }
.modal-header .btn-close { filter: invert(1); }
.modal-title { font-weight: 700; font-size: 1rem; }
.detail-label { font-size: .73rem; color: #888; font-weight: 700; margin-bottom: 3px; }
.detail-val { 
  font-weight: 700;
  font-size: .9rem;
  color: #1a202c; }
button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h2 class="fw-bold mb-1" style="color:#1a202c;">Applications</h2>
    <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0" style="font-size:.8rem;">
      <li class="breadcrumb-item"><a href="index.php" style="color:#00bcd4;">Dashboard</a></li>
      <li class="breadcrumb-item active">Applications</li>
    </ol></nav>
  </div>
</div>

<?php
  // ONE connection at the top — used everywhere below
  require_once "../../config.php";
  
  $stmt = $conn->prepare("SELECT id FROM hostel WHERE owner_id = ?"); // Removed extra )
  $stmt->bind_param('i', $_SESSION['owner_id']);
  $stmt->execute();

  $result = $stmt->get_result();
  $hostel_data = $result->fetch_assoc();
  $hostel_id = $hostel_data['id'] ?? 0; // Extract the actual number
  $stmt->close();

  // ── Mini stat counts ──────────────────────────────────────────

  $stmt = $conn->prepare("SELECT COUNT(*) FROM booking WHERE hostel_id = ?");
  $stmt->bind_param('i', $hostel_id);
  $stmt->execute();
  $stmt->bind_result($total);
  $stmt->fetch();
  $stmt->close();

  $stmt = $conn->prepare("SELECT COUNT(*) FROM booking WHERE hostel_id = ? AND status = 'Pending'");
  $stmt->bind_param('i', $hostel_id);
  $stmt->execute();
  $stmt->bind_result($pending);
  $stmt->fetch();
  $stmt->close();

  $stmt = $conn->prepare("SELECT COUNT(*) FROM booking WHERE hostel_id = ? AND status = 'Approved'");
  $stmt->bind_param('i', $hostel_id);
  $stmt->execute();
  $stmt->bind_result($approved);
  $stmt->fetch();
  $stmt->close();

  $stmt = $conn->prepare("SELECT COUNT(*) FROM booking WHERE hostel_id = ? AND status = 'Rejected'");
  $stmt->bind_param('i', $hostel_id);
  $stmt->execute();
  $stmt->bind_result($rejected);
  $stmt->fetch();
  $stmt->close();
?>

<!-- Mini Stats -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3"><div class="stat-mini"><div class="v" style="color:#00bcd4;"><?php echo $total;    ?></div><div class="l">Total</div></div></div>
  <div class="col-6 col-md-3"><div class="stat-mini"><div class="v" style="color:#ffc107;"><?php echo $pending;  ?></div><div class="l">Pending</div></div></div>
  <div class="col-6 col-md-3"><div class="stat-mini"><div class="v" style="color:#28a745;"><?php echo $approved; ?></div><div class="l">Approved</div></div></div>
  <div class="col-6 col-md-3"><div class="stat-mini"><div class="v" style="color:#dc3545;"><?php echo $rejected; ?></div><div class="l">Rejected</div></div></div>
</div>

<!-- Table -->
<div class="card tbl-card">
  <div class="tbl-card-head">
    <h6><i class="fas fa-inbox me-2" style="color:#00bcd4;"></i>All Applications</h6>
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <button class="tab-btn active" onclick="setTab('all', this)">All</button>
      <button class="tab-btn" onclick="setTab('Pending', this)">Pending</button>
      <button class="tab-btn" onclick="setTab('Approved', this)">Approved</button>
      <button class="tab-btn" onclick="setTab('Rejected', this)">Rejected</button>
      <input type="text" class="filter-inp ms-1" placeholder="Search name..." id="searchApp" oninput="filterRows()"/>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table tbl mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>Student Name</th>
          <th>Contact</th>
          <th>Email</th>
          <th>Start Date</th>
          <th>Applied On</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="appBody">

      <?php
        $stmt = $conn->prepare("SELECT * FROM booking WHERE hostel_id = ?");
        $stmt->bind_param('i', $hostel_id);
        $stmt->execute();
        $res = $stmt->get_result();

        $clrs = ['#00bcd4', '#28a745', '#fd7e14', '#6f42c1', '#dc3545', '#20c997'];
        $i    = 0;

        while ($booking = mysqli_fetch_assoc($res)) {

            // Get the student who made this booking
            $std_id = $booking['student_id'];
            $stmt1  = $conn->prepare("SELECT * FROM student WHERE id = ?");
            $stmt1->bind_param('i', $std_id);
            $stmt1->execute();
            $std = $stmt1->get_result()->fetch_assoc();
            $stmt1->close();

            // BUG FIX: Pre-calculate everything BEFORE the HEREDOC
            // because you cannot do expressions like {$i+1} inside a HEREDOC

            $rowNum      = $i + 1;
            $avatarColor = $clrs[$i % count($clrs)];
            $start_date  = !empty($booking['start_date']) ? $booking['start_date'] : 'N/A';
            $booking_id  = $booking['id'];

            // Avatar initials
            $words    = explode(' ', trim($std['name']));
            $initials = strtoupper(
                substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : '')
            );

            // Badge class
            $badgeClass = 'warning';
            if ($booking['status'] === 'Approved') $badgeClass = 'success';
            if ($booking['status'] === 'Rejected') $badgeClass = 'danger';

            // BUG FIX: {NED University} and {0333-...} are NOT variables —
            // plain text inside {} in HEREDOC breaks. Use real variables.
            // Replace these with the correct column names from your student table.
            $studentName = $std['name'];
            $studentEmail   = $std['email'];
            $studentContact = $std['contact'];
            $studentUni     = $std['university'] ?? 'N/A';
            $studentCnic    = $std['cnic'] ?? 'N/A'; 

            $bookingStatus   = $booking['status'];
            $bookingRoom     = $booking['room_type'];
            $bookingCreated  = $booking['created_at'];
            
            $isDisabled = ($bookingStatus === "Approved" || $bookingStatus === "Rejected") ? 'disabled' : '';
            
            echo <<<HTML
            <tr data-status="{$bookingStatus}"
                data-id="{$booking_id}"
                data-name="{$studentName}"
                data-cnic="{$studentCnic}"
                data-contact="{$studentContact}"
                data-email="{$studentEmail}"
                data-uni="{$studentUni}"
                data-room="{$bookingRoom}"
                data-start="{$start_date}"
                data-applied="{$bookingCreated}">

              <td style="color:#888;font-weight:600;">{$rowNum}</td>

              <td>
                <div style="display:flex;align-items:center;gap:9px;">
                  <div class="avatar-c" style="background:{$avatarColor};">{$initials}</div>
                  <div>
                    <div style="font-weight:700;">{$studentName}</div>
                    <div style="font-size:.73rem;color:#888;">{$studentUni}</div>
                  </div>
                </div>
              </td>

              <td>{$studentContact}</td>
              <td style="color:#888;">{$studentEmail}</td>
              <td style="font-size:.82rem;">{$start_date}</td>
              <td style="font-size:.82rem;color:#888;">{$bookingCreated}</td>

              <td><span class="badge-s {$badgeClass}">{$bookingStatus}</span></td>

              <td>
                <div style="display:flex;gap:5px;flex-wrap:wrap;align-items:center;">


                  <form action="../../Backend/backend.php" method="POST" style="display:flex;gap:5px;">
                    <input type="hidden" name="bookingRoom" value="{$bookingRoom}">
                    <input type="hidden" name="booking_id" value="{$booking_id}">
                    <button class="btn-approve" name="status" value="Approved" type="submit"{$isDisabled}><i class="fas fa-check me-1"></i>Approve</button>
                    <button class="btn-reject"  name="status" value="Rejected" type="submit" {$isDisabled}><i class="fas fa-times me-1"></i>Reject</button>
                  </form>
                  <button class="btn-view-d" onclick="viewDetail(this)"><i class="fas fa-eye"></i></button>
                </div>
              </td>
            </tr>
            HTML;
            $i++;
        }
      ?>

      </tbody>
    </table>
  </div>

  <div class="px-3 py-2" style="border-top:1px solid #f0f0f0;font-size:.8rem;color:#888;">
    <span id="appCount"></span>
  </div>
</div>

<!-- DETAIL MODAL -->
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-file-alt me-2" style="color:#00bcd4;"></i>Application Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4" id="detailBody"></div>
      <div class="modal-footer border-0 px-4 pb-4">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
let activeTab = 'all';

function statusBadge(status) {
  if (status === 'Approved') return '<span class="badge-s success">Approved</span>';
  if (status === 'Rejected') return '<span class="badge-s danger">Rejected</span>';
  return '<span class="badge-s warning">Pending</span>';
}

function filterRows() {
  const query = document.getElementById('searchApp').value.toLowerCase();
  const rows  = document.querySelectorAll('#appBody tr');
  let visible = 0;

  rows.forEach(row => {
    const matchesTab    = (activeTab === 'all') || (row.dataset.status === activeTab);
    const matchesSearch = row.dataset.name.toLowerCase().includes(query);
    row.style.display   = (matchesTab && matchesSearch) ? '' : 'none';
    if (matchesTab && matchesSearch) visible++;
  });

  document.getElementById('appCount').textContent =
    'Showing ' + visible + ' application' + (visible !== 1 ? 's' : '');
}

function setTab(tab, btn) {
  activeTab = tab;
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  filterRows();
}

function viewDetail(btn) {
  const row = btn.closest('tr');
  document.getElementById('detailBody').innerHTML = `
    <div class="row g-3">
      <div class="col-6"><div class="detail-label">Full Name</div><div class="detail-val">${row.dataset.name}</div></div>
      <div class="col-6"><div class="detail-label">CNIC</div><div class="detail-val">${row.dataset.cnic}</div></div>
      <div class="col-6"><div class="detail-label">Contact</div><div class="detail-val">${row.dataset.contact}</div></div>
      <div class="col-6"><div class="detail-label">Email</div><div class="detail-val">${row.dataset.email}</div></div>
      <div class="col-6"><div class="detail-label">University</div><div class="detail-val">${row.dataset.uni}</div></div>
      <div class="col-6"><div class="detail-label">Preferred Room</div><div class="detail-val" style="color:#00bcd4;">${row.dataset.room}</div></div>
      <div class="col-6"><div class="detail-label">Start Date</div><div class="detail-val">${row.dataset.start}</div></div>
      <div class="col-6"><div class="detail-label">Applied On</div><div class="detail-val">${row.dataset.applied}</div></div>
      <div class="col-12"><div class="detail-label">Status</div>${statusBadge(row.dataset.status)}</div>
    </div>`;
  new bootstrap.Modal(document.getElementById('detailModal')).show();
}

filterRows();
</script>

<?php include("footer.php"); ?>