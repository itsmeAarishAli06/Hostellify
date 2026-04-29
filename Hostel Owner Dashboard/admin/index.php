<?php 
  session_start();
  require_once "../../config.php";
  $owner_id = $_SESSION['owner_id'];

  // 1. Get hostel_id
  $stmt = $conn->prepare("SELECT id FROM hostel WHERE owner_id = ?");
  $stmt->bind_param("i", $owner_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $hostel = $result->fetch_assoc();
  $hostel_id = $hostel['id'] ?? null;

  // 2. Total Rooms
  $stmt = $conn->prepare("SELECT COUNT(*) as total FROM room WHERE hostel_id = ?");
  $stmt->bind_param("i", $hostel_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $total_rooms = $result->fetch_assoc()['total'];

  // 3. Total Students (Approved bookings)
  $stmt = $conn->prepare("SELECT COUNT(*) as total FROM booking WHERE hostel_id = ? AND status = 'Approved'");
  $stmt->bind_param("i", $hostel_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $total_students = $result->fetch_assoc()['total'];

  // 4. Available Beds (capacity - occupied)
  $stmt = $conn->prepare("SELECT SUM(capacity - occupied) as available FROM room WHERE hostel_id = ?");
  $stmt->bind_param("i", $hostel_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $available_beds = $result->fetch_assoc()['available'] ?? 0;

  // 5. Pending Applications
  $stmt = $conn->prepare("SELECT COUNT(*) as total FROM booking WHERE hostel_id = ? AND status = 'Pending'");
  $stmt->bind_param("i", $hostel_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $pending_applications = $result->fetch_assoc()['total'];

  // 6. Approved Applications
  $stmt = $conn->prepare("SELECT COUNT(*) as total FROM booking WHERE hostel_id = ? AND status = 'Approved'");
  $stmt->bind_param("i", $hostel_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $approved_applications = $result->fetch_assoc()['total'];

  // 7 Rejected Applications
  $stmt = $conn->prepare("SELECT COUNT(*) as total FROM booking WHERE hostel_id = ? AND status = 'Rejected'");
  $stmt->bind_param("i", $hostel_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $rejected_applications = $result->fetch_assoc()['total'];

  // 8. Total Complaints
  $stmt = $conn->prepare("SELECT COUNT(*) as total FROM complaints WHERE hostel_id = ?");
  $stmt->bind_param("i", $hostel_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $total_complaints = $result->fetch_assoc()['total'];

  // 9. Resolved Complaints
  $stmt = $conn->prepare("SELECT COUNT(*) as total FROM complaints WHERE hostel_id = ? AND status = 'resolved'");
  $stmt->bind_param("i", $hostel_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $resolved_complaints = $result->fetch_assoc()['total'];

  // 10. Resolved Complaints
  $stmt = $conn->prepare("SELECT COUNT(*) as total FROM complaints WHERE hostel_id = ? AND status = 'pending'");
  $stmt->bind_param("i", $hostel_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $pending_complaints = $result->fetch_assoc()['total'];

  // 11 total occupied and left
  $stmt = $conn->prepare("SELECT SUM(capacity) as total_capacity, SUM(occupied) as total_occupied FROM room WHERE hostel_id = ?");
  $stmt->bind_param("i", $hostel_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  $total_capacity = $row['total_capacity'] ?? 0;
  $total_occupied = $row['total_occupied'] ?? 0;
  $available_seats = $total_capacity - $total_occupied;

  include("header.php"); 
?>

<style>
body{
  overflow-x: hidden;
}
.stat-card { border-radius: 14px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.07); transition: transform .25s, box-shadow .25s; }
.stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 28px rgba(0,188,212,0.15); }
.stat-icon { width: 52px; height: 52px; border-radius: 13px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
.stat-value { font-size: 2rem; font-weight: 700; line-height: 1.1; }
.stat-trend { font-size: .78rem; font-weight: 700; margin-top: 6px; }
.stat-trend.up { color: #28a745; }
.stat-trend.down { color: #dc3545; }
.section-card { border-radius: 14px; border: 1px solid #e9ecef; box-shadow: 0 4px 18px rgba(0,0,0,0.06); }
.section-header { padding: 15px 20px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; justify-content: space-between; }
.section-header h6 { margin: 0; font-weight: 700; font-size: .95rem; }
.tbl th { font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #888; background: #f8f9fa; padding: 10px 14px; }
.tbl td { padding: 12px 14px; font-size: .87rem; vertical-align: middle; border-color: #f0f0f0; }
.badge-s { padding: 4px 12px; border-radius: 50px; font-size: .73rem; font-weight: 700; }
.badge-s.success { background: rgba(40,167,69,.13); color: #1a6e30; }
.badge-s.warning { background: rgba(255,193,7,.18); color: #856404; }
.badge-s.danger  { background: rgba(220,53,69,.13); color: #961b24; }
.badge-s.info    { background: rgba(0,188,212,.13); color: #007b8a; }
.avatar-sm { width: 32px; height: 32px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: .78rem; color: #fff; }
.qa-card { border-radius: 14px; border: 1px solid #e9ecef; padding: 24px 16px; text-align: center; transition: all .25s; cursor: pointer; text-decoration: none; display: block; }
.qa-card:hover { border-color: #00bcd4; box-shadow: 0 6px 22px rgba(0,188,212,.15); transform: translateY(-3px); }
.qa-icon { width: 60px; height: 60px; border-radius: 16px; margin: 0 auto 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
.progress-bar-custom { height: 8px; border-radius: 20px; }
.btn-sm-approve { background: rgba(40,167,69,.1); color: #1a6e30; border: 1px solid rgba(40,167,69,.25); padding: 4px 12px; border-radius: 7px; font-size: .75rem; font-weight: 700; cursor: pointer; transition: all .2s; }
.btn-sm-approve:hover { background: #28a745; color: #fff; }
.btn-sm-reject { background: rgba(220,53,69,.1); color: #961b24; border: 1px solid rgba(220,53,69,.25); padding: 4px 12px; border-radius: 7px; font-size: .75rem; font-weight: 700; cursor: pointer; transition: all .2s; }
.btn-sm-reject:hover { background: #dc3545; color: #fff; }
</style>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h2 class="fw-bold mb-1" style="color:#1a202c;">Dashboard</h2>
    <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0" style="font-size:.8rem;">
      <li class="breadcrumb-item"><a href="index.php" style="color:#00bcd4;">Home</a></li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol></nav>
  </div>
  <div style="font-size:.82rem;color:#888;"><i class="fas fa-calendar-alt me-1" style="color:#00bcd4;"></i><?php echo date('D, d M Y'); ?></div>
</div>

<!-- STAT CARDS -->
<div class="row g-3 mb-4">
  <div class="col-xl-3 col-md-6">
    <div class="card stat-card p-3">
      <div class="d-flex align-items-center gap-3">
        <div class="stat-icon" style="background:rgba(0,188,212,.12);color:#00bcd4;"><i class="fas fa-user-graduate"></i></div>
        <div>
          <div class="stat-value" style="color:#1a202c;"><?php echo $total_students;?></div>
          <div style="font-size:.82rem;color:#888;font-weight:600;">Total Students</div>
          <div class="stat-trend up"><i class="fas fa-arrow-up me-1"></i>Active Users</div>
        </div>
      </div>
      <div style="height:3px;background:rgba(0,188,212,.15);border-radius:10px;margin-top:14px;"><div style="height:100%;width:75%;background:#00bcd4;border-radius:10px;"></div></div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="card stat-card p-3">
      <div class="d-flex align-items-center gap-3">
        <div class="stat-icon" style="background:rgba(40,167,69,.12);color:#28a745;"><i class="fas fa-door-open"></i></div>
        <div>
          <div class="stat-value" style="color:#1a202c;"><?php echo $total_rooms;?></div>
          <div style="font-size:.82rem;color:#888;font-weight:600;">Total Rooms</div>
          <div class="stat-trend up"><i class="fas fa-arrow-up me-1"></i>Room Capacity</div>
        </div>
      </div>
      <div style="height:3px;background:rgba(40,167,69,.15);border-radius:10px;margin-top:14px;"><div style="height:100%;width:60%;background:#28a745;border-radius:10px;"></div></div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="card stat-card p-3">
      <div class="d-flex align-items-center gap-3">
        <div class="stat-icon" style="background:rgba(255,193,7,.15);color:#d4a017;"><i class="fas fa-check-circle"></i></div>
        <div>
          <div class="stat-value" style="color:#1a202c;"><?php echo $available_beds;?></div>
          <div style="font-size:.82rem;color:#888;font-weight:600;">Available Beds</div>
          <div class="stat-trend down"><i class="fas fa-arrow-down me-1"></i>Still Left</div>
        </div>
      </div>
      <div style="height:3px;background:rgba(255,193,7,.15);border-radius:10px;margin-top:14px;"><div style="height:100%;width:30%;background:#ffc107;border-radius:10px;"></div></div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="card stat-card p-3">
      <div class="d-flex align-items-center gap-3">
        <div class="stat-icon" style="background:rgba(220,53,69,.12);color:#dc3545;"><i class="fas fa-clock"></i></div>
        <div>
          <div class="stat-value" style="color:#1a202c;"><?php echo $pending_applications;?></div>
          <div style="font-size:.82rem;color:#888;font-weight:600;">Pending Applications</div>
          <div class="stat-trend up" style="color:#dc3545;"><i class="fas fa-arrow-up me-1"></i>Till today</div>
        </div>
      </div>
      <div style="height:3px;background:rgba(220,53,69,.12);border-radius:10px;margin-top:14px;"><div style="height:100%;width:45%;background:#dc3545;border-radius:10px;"></div></div>
    </div>
  </div>
</div>

<!-- Complaints Count + Occupancy -->
<div class="row g-3 mb-4">
  <div class="col-xl-3 col-md-6">
    <div class="card stat-card p-3 h-100">
      <div class="d-flex align-items-center gap-3">
        <div class="stat-icon" style="background:rgba(111,66,193,.12);color:#6f42c1;"><i class="fas fa-comment-dots"></i></div>
        <div>
          <div class="stat-value" style="color:#1a202c;"><?php echo $total_complaints;?></div>
          <div style="font-size:.82rem;color:#888;font-weight:600;">Complaints Count</div>
          <div class="stat-trend down" style="color:#28a745;"><i class="fas fa-arrow-down me-1"></i><?php echo $resolved_complaints;?> Resolved </div>
        </div>
      </div>
      <div style="height:3px;background:rgba(111,66,193,.12);border-radius:10px;margin-top:14px;"><div style="height:100%;width:35%;background:#6f42c1;border-radius:10px;"></div></div>
    </div>
  </div>
  <div class="col-xl-9">
    <div class="card section-card h-100">
      <div class="section-header">
        <h6><i class="fas fa-chart-bar me-2" style="color:#00bcd4;"></i>Occupancy Overview</h6>
        <span class="badge-s info">Live</span>
      </div>
      <div class="p-3">
        <div class="mb-3">
          <div class="d-flex justify-content-between mb-1"><span style="font-size:.83rem;font-weight:600;">Hostel Allotment</span><strong style="font-size:.83rem;">
            <?php
              echo $total_occupied;
            ?> 
            Students</strong></div>
          <div class="progress" style="height:8px;border-radius:20px;"><div class="progress-bar" style="width:<?php echo $total_occupied; ?>%;background:#00bcd4;border-radius:20px;"></div></div>
        </div>
        <div class="mb-3">
          <div class="d-flex justify-content-between mb-1"><span style="font-size:.83rem;font-weight:600;">Applications Approved</span><strong style="font-size:.83rem;">
            <?php
              $total = $pending_applications + $approved_applications + $rejected_applications;  
              $percentage = ($total > 0) ? ($approved_applications / $total) * 100 : 0;
              echo round($percentage, 1); // 1 decimal point tak round karega
            ?>%
            </strong></div>
          <div class="progress" style="height:8px;border-radius:20px;"><div class="progress-bar" style="width:<?php echo $percentage ;?>%;background:#28a745;border-radius:20px;"></div></div>
        </div>
        <div>
          <div class="d-flex justify-content-between mb-1"><span style="font-size:.83rem;font-weight:600;">Complaints Resolved</span>
          <strong style="font-size:.83rem;">
            <?php    
            // Check karein ke total 0 na ho
            $total = $pending_complaints + $resolved_complaints;
            $percentage = ($total > 0) ? ($resolved_complaints / $total) * 100 : 0;
            echo round($percentage, 1); // 1 decimal point tak round karega
            // ?> %
          </strong>
          </div>
          <div class="progress" style="height:8px;border-radius:20px;"><div class="progress-bar" style="width:<?php echo $percentage;?>%;background:#6f42c1;border-radius:20px;"></div></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- QUICK ACTIONS -->
<h6 class="fw-bold mb-3" style="color:#1a202c;font-size:1rem;"><i class="fas fa-bolt me-2" style="color:#00bcd4;"></i>Quick Actions</h6>
<div class="row g-3 mb-4">
  <div class="col-md-3 col-6">
    <a href="hostel_profile.php" class="qa-card">
      <div class="qa-icon" style="background:rgba(0,188,212,.1);color:#00bcd4;"><i class="fas fa-building"></i></div>
      <div style="font-weight:700;font-size:.9rem;color:#1a202c;">Hostel Profile</div>
      <div style="font-size:.78rem;color:#888;margin-top:3px;">Edit hostel details</div>
    </a>
  </div>
  <div class="col-md-3 col-6">
    <a href="hostels.php" class="qa-card">
      <div class="qa-icon" style="background:rgba(40,167,69,.1);color:#28a745;"><i class="fas fa-door-open"></i></div>
      <div style="font-weight:700;font-size:.9rem;color:#1a202c;">Manage Rooms</div>
      <div style="font-size:.78rem;color:#888;margin-top:3px;">View & add rooms</div>
    </a>
  </div>
  <div class="col-md-3 col-6">
    <a href="My_students.php" class="qa-card">
      <div class="qa-icon" style="background:rgba(255,193,7,.1);color:#d4a017;"><i class="fas fa-user-graduate"></i></div>
      <div style="font-weight:700;font-size:.9rem;color:#1a202c;">Students</div>
      <div style="font-size:.78rem;color:#888;margin-top:3px;">View all students</div>
    </a>
  </div>
  <div class="col-md-3 col-6">
    <a href="complaints.php" class="qa-card">
      <div class="qa-icon" style="background:rgba(220,53,69,.1);color:#dc3545;"><i class="fas fa-comment-dots"></i></div>
      <div style="font-weight:700;font-size:.9rem;color:#1a202c;">Complaints</div>
      <div style="font-size:.78rem;color:#888;margin-top:3px;">View all complaints</div>
    </a>
  </div>
</div>

<!-- RECENT ACTIVITY + PENDING APPLICATIONS
<div class="row g-3">
  <div class="col-lg-7">
    <div class="card section-card">
      <div class="section-header">
        <h6><i class="fas fa-history me-2" style="color:#00bcd4;"></i>Recent Activity</h6>
        <a href="students.php" style="color:#00bcd4;font-size:.8rem;font-weight:700;text-decoration:none;">View All</a>
      </div>
      <div class="table-responsive">
        <table class="table tbl mb-0">
          <thead><tr><th>Student</th><th>Action</th><th>Time</th><th>Status</th></tr></thead>
          <tbody>
            <tr>
              <td><div class="d-flex align-items-center gap-2"><div class="avatar-sm" style="background:#00bcd4;">MF</div>Mohammad Farooq</div></td>
              <td>New Registration</td>
              <td style="color:#888;font-size:.8rem;">2 hrs ago</td>
              <td><span class="badge-s success">Done</span></td>
            </tr>
            <tr>
              <td><div class="d-flex align-items-center gap-2"><div class="avatar-sm" style="background:#28a745;">SA</div>Sara Ahmed</div></td>
              <td>Room Assigned</td>
              <td style="color:#888;font-size:.8rem;">5 hrs ago</td>
              <td><span class="badge-s info">Active</span></td>
            </tr>
            <tr>
              <td><div class="d-flex align-items-center gap-2"><div class="avatar-sm" style="background:#6f42c1;">AK</div>Ali Khan</div></td>
              <td>Complaint Filed</td>
              <td style="color:#888;font-size:.8rem;">1 day ago</td>
              <td><span class="badge-s warning">Pending</span></td>
            </tr>
            <tr>
              <td><div class="d-flex align-items-center gap-2"><div class="avatar-sm" style="background:#fd7e14;">ZB</div>Zara Baig</div></td>
              <td>Application Submitted</td>
              <td style="color:#888;font-size:.8rem;">1 day ago</td>
              <td><span class="badge-s warning">Pending</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card section-card h-100">
      <div class="section-header">
        <h6><i class="fas fa-file-alt me-2" style="color:#00bcd4;"></i>Pending Applications</h6>
        <span class="badge-s warning">7 Pending</span>
      </div>
      <div class="table-responsive">
        <table class="table tbl mb-0">
          <thead><tr><th>Student</th><th>Date</th><th>Action</th></tr></thead>
          <tbody>
            <tr>
              <td><div class="d-flex align-items-center gap-2"><div class="avatar-sm" style="background:#fd7e14;">ZB</div>Zara Baig</div></td>
              <td style="font-size:.8rem;color:#888;">Feb 26</td>
              <td>
                <button class="btn-sm-approve me-1">✓</button>
                <button class="btn-sm-reject">✕</button>
              </td>
            </tr>
            <tr>
              <td><div class="d-flex align-items-center gap-2"><div class="avatar-sm" style="background:#20c997;">HM</div>Hassan Malik</div></td>
              <td style="font-size:.8rem;color:#888;">Feb 27</td>
              <td>
                <button class="btn-sm-approve me-1">✓</button>
                <button class="btn-sm-reject">✕</button>
              </td>
            </tr>
            <tr>
              <td><div class="d-flex align-items-center gap-2"><div class="avatar-sm" style="background:#e83e8c;">NU</div>Nadia Usman</div></td>
              <td style="font-size:.8rem;color:#888;">Feb 28</td>
              <td>
                <button class="btn-sm-approve me-1">✓</button>
                <button class="btn-sm-reject">✕</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div> -->

<?php include("footer.php"); ?>