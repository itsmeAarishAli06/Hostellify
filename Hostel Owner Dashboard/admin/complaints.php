<?php include("header.php"); ?>

<style>
body{
  overflow-x: hidden;
} 
.tbl-card { 
  border-radius: 14px;
  border: 1px solid #e9ecef; 
  box-shadow: 0 4px 18px rgba(0,0,0,0.06); 
  overflow: hidden; 
}
.tbl-card-head {
  padding: 15px 20px; 
  border-bottom: 1px solid #f0f0f0; 
  display: flex; 
  align-items: center; 
  justify-content: space-between; 
  flex-wrap: wrap; gap: 10px; 
}
.tbl-card-head h6 { 
  margin: 0; 
  font-weight: 700; 
  font-size: .95rem; 
}
.tbl th { 
  font-size: .74rem; 
  font-weight: 700; 
  text-transform: uppercase; 
  letter-spacing: .6px; 
  color: #888; background: #f8f9fa; 
  padding: 10px 14px; 
  white-space: nowrap; 
}
.tbl td { 
  padding: 12px 14px; 
  font-size: .87rem; 
  vertical-align: middle; 
  border-color: #f0f0f0; 
}
.tbl tbody tr:hover { 
  background: #f8f9fa;
 }
.badge-s { 
  padding: 4px 12px; 
  border-radius: 50px; 
  font-size: .73rem; 
  font-weight: 700;
 }
.badge-s.success { background: rgba(40,167,69,.13); 
color: #1a6e30; 
}
.badge-s.danger  { background: rgba(220,53,69,.13); 
color: #961b24; 
}
.badge-s.warning { background: rgba(255,193,7,.18); 
color: #856404; 
}
.badge-s.info    { background: rgba(0,188,212,.13); 
color: #007b8a; 
}
.badge-priority { padding: 3px 10px; border-radius: 50px; font-size: .71rem; font-weight: 700; }
.badge-priority.high   { background: rgba(220,53,69,.13); 
  color: #961b24; 
}
.badge-priority.medium { background: rgba(255,193,7,.18); color: #856404; }
.badge-priority.low    { background: rgba(40,167,69,.13); color: #1a6e30; }
.avatar-c { width: 34px; height: 34px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: .78rem; color: #fff; flex-shrink: 0; }
.badge-room { background: rgba(0,188,212,.12); color: #007b8a; padding: 3px 10px; border-radius: 50px; font-size: .73rem; font-weight: 700; }
.comp-title { font-weight: 700; font-size: .87rem; color: #1a202c; }
.comp-desc  { font-size: .76rem; color: #888; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.btn-resolve { background: rgba(40,167,69,.1); color: #1a6e30; border: 1.5px solid rgba(40,167,69,.25); padding: 5px 13px; border-radius: 7px; font-weight: 700; font-size: .78rem; cursor: pointer; transition: all .2s; display: inline-flex; align-items: center; gap: 4px; }
.btn-resolve:hover { background: #28a745; color: #fff; border-color: #28a745; }
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
.input-modal { border: 1.5px solid #dee2e6; border-radius: 9px; padding: 8px 12px; font-size: .88rem; width: 100%; outline: none; font-family: inherit; transition: border .2s; resize: vertical; min-height: 80px; }
.input-modal:focus { border-color: #00bcd4; box-shadow: 0 0 0 3px rgba(0,188,212,.1); }
.btn-modal-save { background: #28a745; color: #fff; border: none; padding: 9px 22px; border-radius: 9px; font-weight: 700; cursor: pointer; font-size: .88rem; transition: all .2s; }
.btn-modal-save:hover { background: #218838; }
.detail-label { font-size: .72rem; color: #888; font-weight: 700; margin-bottom: 3px; }
.detail-val { font-weight: 700; font-size: .9rem; color: #1a202c; }
.toast-msg { position: fixed; bottom: 24px; right: 24px; background: #28a745; color: #fff; padding: 12px 22px; border-radius: 10px; font-weight: 700; font-size: .88rem; box-shadow: 0 6px 20px rgba(40,167,69,.35); opacity: 0; transition: opacity .3s; z-index: 9999; }
</style>

<?php
require_once "../../config.php";
$owner_id = $_SESSION['owner_id'];

// Find the hostel ID
$stmt0 = $conn->prepare("SELECT id FROM hostel WHERE owner_id = ?");
$stmt0->bind_param("i", $owner_id);
$stmt0->execute();
$row = $stmt0->get_result();
$data0 = $row->fetch_assoc(); 
$hostel_id = $data0['id'];
$stmt0->close();

// Get stats for mini cards
$stmt_stats = $conn->prepare("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'Open' THEN 1 ELSE 0 END) as open_count,
        SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) as progress_count,
        SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) as resolved_count
    FROM complaints 
    WHERE hostel_id = ?
");
$stmt_stats->bind_param("i", $hostel_id);
$stmt_stats->execute();
$stats = $stmt_stats->get_result()->fetch_assoc();
$stmt_stats->close();
?>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h2 class="fw-bold mb-1" style="color:#1a202c;">Complaints</h2>
    <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0" style="font-size:.8rem;">
      <li class="breadcrumb-item"><a href="index.php" style="color:#00bcd4;">Dashboard</a></li>
      <li class="breadcrumb-item active">Complaints</li>
    </ol></nav>
  </div>
</div>

<!-- Mini Stats -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3"><div class="stat-mini"><div class="v" style="color:#00bcd4;"><?php echo $stats['total'] ?? 0; ?></div><div class="l">Total</div></div></div>
  <div class="col-6 col-md-3"><div class="stat-mini"><div class="v" style="color:#ffc107;"><?php echo $stats['open_count'] ?? 0; ?></div><div class="l">Open</div></div></div>
  <div class="col-6 col-md-3"><div class="stat-mini"><div class="v" style="color:#6f42c1;"><?php echo $stats['progress_count'] ?? 0; ?></div><div class="l">In Progress</div></div></div>
  <div class="col-6 col-md-3"><div class="stat-mini"><div class="v" style="color:#28a745;"><?php echo $stats['resolved_count'] ?? 0; ?></div><div class="l">Resolved</div></div></div>
</div>

<!-- Table -->
<div class="card tbl-card">
  <div class="tbl-card-head">
    <h6><i class="fas fa-comment-dots me-2" style="color:#00bcd4;"></i>All Complaints</h6>
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <button class="tab-btn active" onclick="setTab('all',this)">All</button>
      <button class="tab-btn" onclick="setTab('Open',this)">Open</button>
      <button class="tab-btn" onclick="setTab('In Progress',this)">In Progress</button>
      <button class="tab-btn" onclick="setTab('Resolved',this)">Resolved</button>
      <input type="text" class="filter-inp ms-1" placeholder="Search..." id="searchComp" oninput="renderComp()"/>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table tbl mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>Student Name</th>
          <th>Room</th>
          <th>Complaint Title</th>
          <th>Priority</th>
          <th>Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="compBody">
<?php
// Get all complaints with triple join
$stmt1 = $conn->prepare("
    SELECT 
        c.*, 
        s.name AS student_name,
        s.contact AS student_contact,
        r.room_number,
        b.room_id 
    FROM complaints c
    INNER JOIN student s ON c.student_id = s.id
    LEFT JOIN booking b ON (c.student_id = b.student_id AND c.hostel_id = b.hostel_id)
    LEFT JOIN room r ON b.room_id = r.id
    WHERE c.hostel_id = ?
    ORDER BY c.created_at DESC
");

$stmt1->bind_param("i", $hostel_id);
$stmt1->execute();
$result = $stmt1->get_result();

$i = 1;
while ($c = $result->fetch_assoc()) {
    $complaint_id = $c['id'];
    $student = htmlspecialchars($c['student_name']);
    $room_number = $c['room_number'] ? 'R-' . $c['room_number'] : 'N/A';
    $subject = htmlspecialchars($c['subject']);
    $message = htmlspecialchars($c['message']);
    $priority = strtolower($c['level']);  // high, medium, low
    $priority_display = ucfirst($priority);
    $status = $c['status'];  // Open, In Progress, Resolved
    $date = date("M j, Y", strtotime($c['created_at']));
    
    // Determine status badge class
    $status_class = 'warning';  // default
    if ($status == 'Resolved') $status_class = 'success';
    elseif ($status == 'In Progress') $status_class = 'info';
    
    echo "
      <tr>
        <td style='color:#888;font-weight:600;'>{$i}</td>
        <td><span style='font-weight:700;'>{$student}</span></td>
        <td><span class='badge-room'><i class='fas fa-door-open me-1'></i>{$room_number}</span></td>
        <td>
          <div class='comp-title'>{$subject}</div>
          <div class='comp-desc'>{$message}</div>
        </td>
        <td><span class='badge-priority {$priority}'><i class='fas fa-exclamation-circle me-1'></i>{$priority_display}</span></td>
        <td style='font-size:.82rem;color:#888;'>{$date}</td>
        <td><span class='badge-s {$status_class}'>{$status}</span></td>
        <td>
          <div style='display:flex;gap:5px;'>
            ";
            
    // Only show resolve button if not already resolved
    if ($status != 'Resolved') {
        echo "
            <form action='../../Backend/backend.php' method='POST' style='display:inline;'>
              <input type='hidden' name='complaint_id' value='{$complaint_id}'>
              <button class='btn-resolve' type='submit' name='resolve_complaint'>
                <i class='fas fa-check-circle'></i> Resolve
              </button>
            </form>";
    }
    
    echo "
            <button class='btn-view-d' onclick='viewComplaint({$complaint_id})'>
              <i class='fas fa-eye'></i>
            </button>
          </div>
        </td>
      </tr>";
    
    $i++;
}
$stmt1->close();
?>
      </tbody>
    </table>
  </div>
  <div class="px-3 py-2" style="border-top:1px solid #f0f0f0;font-size:.8rem;color:#888;">
    <span id="compCount">Showing all complaints</span>
  </div>
</div>

<!-- SINGLE VIEW MODAL - Populated dynamically with JavaScript -->
<div class="modal fade" id="viewComplaintModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-comment-dots me-2" style="color:#00bcd4;"></i>Complaint Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row g-3">
          <div class="col-6">
            <div class="detail-label">Student</div>
            <div class="detail-val" id="modal-student">-</div>
          </div>
          <div class="col-6">
            <div class="detail-label">Room</div>
            <div class="detail-val" style="color:#00bcd4;" id="modal-room">-</div>
          </div>
          <div class="col-12">
            <div class="detail-label">Subject</div>
            <div class="detail-val" id="modal-subject">-</div>
          </div>
          <div class="col-12">
            <div class="detail-label">Message</div>
            <div style="background:#f8f9fa;padding:12px;border-radius:9px;font-size:.87rem;line-height:1.6;color:#444;" id="modal-message">
              -
            </div>
          </div>
          <div class="col-4">
            <div class="detail-label">Priority</div>
            <span id="modal-priority-badge">-</span>
          </div>
          <div class="col-4">
            <div class="detail-label">Date</div>
            <div class="detail-val" id="modal-date">-</div>
          </div>
          <div class="col-4">
            <div class="detail-label">Status</div>
            <span id="modal-status-badge">-</span>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0 px-4 pb-4">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
// Store complaint data for modal viewing
const complaintsData = <?php 
// Re-fetch data for JavaScript
$stmt2 = $conn->prepare("
    SELECT 
        c.*, 
        s.name AS student_name,
        r.room_number
    FROM complaints c
    INNER JOIN student s ON c.student_id = s.id
    LEFT JOIN booking b ON (c.student_id = b.student_id AND c.hostel_id = b.hostel_id)
    LEFT JOIN room r ON b.room_id = r.id
    WHERE c.hostel_id = ?
");
$stmt2->bind_param("i", $hostel_id);
$stmt2->execute();
$result2 = $stmt2->get_result();

$complaints_array = [];
while ($row = $result2->fetch_assoc()) {
    $complaints_array[] = [
        'id' => $row['id'],
        'student' => $row['student_name'],
        'room' => $row['room_number'] ? 'R-' . $row['room_number'] : 'N/A',
        'subject' => $row['subject'],
        'message' => $row['message'],
        'priority' => strtolower($row['level']),
        'priority_display' => ucfirst($row['level']),
        'status' => $row['status'],
        'date' => date("M j, Y", strtotime($row['created_at']))
    ];
}
$stmt2->close();
echo json_encode($complaints_array);
?>;

// View complaint in modal
function viewComplaint(id) {
    const complaint = complaintsData.find(c => c.id === id);
    if (!complaint) return;
    
    // Populate modal fields
    document.getElementById('modal-student').textContent = complaint.student;
    document.getElementById('modal-room').textContent = complaint.room;
    document.getElementById('modal-subject').textContent = complaint.subject;
    document.getElementById('modal-message').textContent = complaint.message;
    document.getElementById('modal-date').textContent = complaint.date;
    
    // Priority badge
    document.getElementById('modal-priority-badge').innerHTML = 
        `<span class="badge-priority ${complaint.priority}"><i class="fas fa-exclamation-circle me-1"></i>${complaint.priority_display}</span>`;
    
    // Status badge
    let statusClass = 'warning';
    if (complaint.status === 'Resolved') statusClass = 'success';
    else if (complaint.status === 'In Progress') statusClass = 'info';
    
    document.getElementById('modal-status-badge').innerHTML = 
        `<span class="badge-s ${statusClass}">${complaint.status}</span>`;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('viewComplaintModal'));
    modal.show();
}

// Filter tabs
function setTab(tab, btn){
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    // TODO: add filtering logic here later
}
</script>

<?php include("footer.php"); ?>