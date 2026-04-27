<?php include("header.php"); 
  // approved
  require_once "../../config.php";

  $name_searched = $_GET['search'];

  $stmt = $conn->prepare("SELECT COUNT(*) as total FROM booking WHERE hostel_id = ? AND status = 'Approved'");
  $stmt->bind_param("i",$_SESSION['hostel_id']);
  $stmt->execute();
  $result = $stmt->get_result();
  $approved_students = $result->fetch_assoc()['total'];

  $stmt = $conn->prepare("SELECT COUNT(*) as total FROM booking WHERE hostel_id = ? ");
  $stmt->bind_param("i",$_SESSION['hostel_id']);
  $stmt->execute();
  $result = $stmt->get_result();
  $total_students = $result->fetch_assoc()['total'];

  
  $stmt = $conn->prepare("SELECT COUNT(DISTINCT room_id) AS assigned_rooms
  FROM booking
  WHERE hostel_id = ? AND status = 'approved';");
  $stmt->bind_param("i",$_SESSION['hostel_id']);
  $stmt->execute();
  $result = $stmt->get_result();
  $res = $result->fetch_assoc();
  $room_assigned = $res['assigned_rooms'];
?>


<!-- 
  NOTE FOR YOU: 
  1. Wrap the <tbody> content in a foreach ($students as $s) loop.
  2. Use your own filter logic in the SQL query based on $_GET or $_POST.
-->

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
.badge-s.warning { background: rgba(255,193,7,.18); color: #856404; }
.badge-room { background: rgba(0,188,212,.12); color: #007b8a; padding: 4px 12px; border-radius: 50px; font-size: .73rem; font-weight: 700; }
.avatar-c { width: 34px; height: 34px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: .78rem; color: #fff; flex-shrink: 0; }
.btn-icon { width: 30px; height: 30px; border: none; border-radius: 7px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; font-size: .78rem; transition: all .15s; }
.btn-icon.view { background: rgba(0,188,212,.1); color: #00bcd4; }
.btn-icon.view:hover { background: #00bcd4; color: #fff; }
.btn-icon.del { background: rgba(220,53,69,.1); color: #dc3545; }
.btn-icon.del:hover { background: #dc3545; color: #fff; }
.btn-add { background: #00bcd4; color: #fff; border: none; padding: 8px 18px; border-radius: 9px; font-weight: 700; font-size: .85rem; cursor: pointer; transition: all .2s; display: inline-flex; align-items: center; gap: 6px; }
.btn-add:hover { background: #0097a7; }
.filter-inp { border: 1.5px solid #dee2e6; border-radius: 8px; padding: 6px 12px; font-size: .83rem; outline: none; transition: border .2s; font-family: inherit; }
.filter-inp:focus { border-color: #00bcd4; }
.stat-mini { text-align: center; padding: 12px; background: #fff; border-radius: 11px; border: 1px solid #e9ecef; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
.stat-mini .v { font-weight: 700; font-size: 1.5rem; }
.stat-mini .l { font-size: .73rem; color: #888; font-weight: 600; }
.btn-approve { background: rgba(40,167,69,.1); color: #1a6e30; border: 1.5px solid rgba(40,167,69,.25); padding: 5px 14px; border-radius: 7px; font-weight: 700; font-size: .78rem; cursor: pointer; transition: all .2s; }
.btn-approve:hover { background: #28a745; color: #fff; border-color: #28a745; }
.btn-reject  { background: rgba(220,53,69,.1); color: #961b24; border: 1.5px solid rgba(220,53,69,.25); padding: 5px 14px; border-radius: 7px; font-weight: 700; font-size: .78rem; cursor: pointer; transition: all .2s; }
.btn-reject:hover  { background: #dc3545; color: #fff; border-color: #dc3545; }
.btn-view-d { background: rgba(0,188,212,.1); color: #00bcd4; border: 1.5px solid rgba(0,188,212,.2); padding: 5px 11px; border-radius: 7px; font-weight: 700; font-size: .78rem; cursor: pointer; transition: all .2s; }
.btn-view-d:hover { background: #00bcd4; color: #fff; }

/* View Modal Styles */
.detail-row { display: flex; padding: 12px 0; border-bottom: 1px solid #f0f0f0; }
.detail-row:last-child { border-bottom: none; }
.detail-label { font-size: .78rem; font-weight: 700; color: #6c757d; text-transform: uppercase; letter-spacing: .5px; width: 140px; flex-shrink: 0; }
.detail-value { font-size: .88rem; color: #1a202c; font-weight: 500; }
.status-badge { padding: 6px 16px; border-radius: 50px; font-size: .8rem; font-weight: 700; display: inline-block; }
.status-badge.approved { background: rgba(40,167,69,.13); color: #1a6e30; }
.status-badge.pending { background: rgba(255,193,7,.18); color: #856404; }
.status-badge.rejected { background: rgba(220,53,69,.1); color: #961b24; }
.student-avatar { width: 70px; height: 70px; border-radius: 50%; background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.8rem; font-weight: 700; margin: 0 auto 15px; box-shadow: 0 4px 12px rgba(0,188,212,0.3); }
</style>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h2 class="fw-bold mb-1" style="color:#1a202c;">Students</h2>
    <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0" style="font-size:.8rem;">
      <li class="breadcrumb-item"><a href="index.php" style="color:#00bcd4;">Dashboard</a></li>
      <li class="breadcrumb-item active">Students</li>
    </ol></nav>
  </div>
</div>

<!-- Mini Stats -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="stat-mini text-center">
            <i class="bi bi-people-fill" style="font-size: 28px; color:#00bcd4;"></i>
            <div class="v" style="color:#00bcd4;"><?PHP echo $total_students ; ?></div>
            <div class="l">Total Students</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="stat-mini text-center">
            <i class="bi bi-person-check-fill" style="font-size: 28px; color:#28a745;"></i>
            <div class="v" style="color:#28a745;"> <?PHP echo $approved_students ;?></div>
            <div class="l">Approved</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="stat-mini text-center">
            <i class="bi bi-house-door-fill" style="font-size: 28px; color:#6f42c1;"></i>
            <div class="v" style="color:#6f42c1;"><?PHP echo $room_assigned ;?></div>
            <div class="l">Rooms Assigned</div>
        </div>
    </div>

</div>

<!-- Table -->
<div class="card tbl-card">
  <div class="tbl-card-head">
    <h6><i class="fas fa-user-graduate me-2" style="color:#00bcd4;"></i>All Students</h6>
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <!-- Search and Filter (You can use these to submit a form) -->
      <form action="" method="GET" class="d-flex gap-2">
          <input type="text" name="search" class="filter-inp" placeholder="Search by name..." value="<?= $_GET['search'] ?? '' ?>"/>
          <select name="room" class="filter-inp">
            <option value="">All Rooms</option>
            <!-- Fetch rooms from DB here -->
          </select>
          <button type="submit" class="btn-icon view"><i class="fas fa-search"></i></button>
      </form>
      
      <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addStudentModal">
        <i class="fas fa-user-plus"></i> Add Student
      </button>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table tbl mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>Student</th>
          <th>Email</th>
          <th>Contact</th>
          <th>Room Assigned</th>
          <th>Join Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <?PHP 


        $stmt0 = $conn->prepare("SELECT id FROM hostel WHERE owner_id = ?");
        $stmt0->bind_param('i', $_SESSION['owner_id']);
        $stmt0->execute();

        $result = $stmt0->get_result();
        $hostel_data = $result->fetch_assoc();
        $hostel_id = $hostel_data['id'] ?? 0;
        $stmt0->close();

        $stmt = $conn->prepare("SELECT
          s.id AS student_id,              
          s.name AS student_name,
          s.email AS student_email,
          s.contact AS student_contact,
          b.created_at AS booking_created_at,      
          r.room_number AS room_number,   
          b.status AS booking_status
        FROM   booking b
        JOIN   student s ON s.id = b.student_id
        LEFT JOIN room r ON r.id = b.room_id
        WHERE  b.hostel_id = ?
        ORDER BY s.name ASC"
        );
        $stmt->bind_param('i', $hostel_id);
        $stmt->execute();
        $students = $stmt->get_result();
        $stmt->close();

        // $has_name = $name_searched ;

        if (mysqli_num_rows($students) > 0) {  
          
            $i = 1;
            while($user = mysqli_fetch_assoc($students)){
            echo "<tbody id='stuBody'>";
            echo "<tr>"; 
                echo "<td>$i</td>";
                echo "<td>" . htmlspecialchars($user['student_name']) . "</td>";
                echo "<td>" . htmlspecialchars($user['student_email']) . "</td>";
                echo "<td>" . htmlspecialchars($user['student_contact']) . "</td>";
                echo "<td>" . htmlspecialchars($user['room_number'] ?: 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($user['booking_created_at']) . "</td>";
                echo "<td>" . htmlspecialchars($user['booking_status']) . "</td>";
                echo "<td>
                  <div style='display:flex; gap:5px;'>
                    <form action='../../Backend/backend.php' method='POST' style='display:inline;'>
                      <input type='hidden' name='hostel_id' value='{$hostel_id}'>
                      <input type='hidden' name='student_id' value='{$user['student_id']}'>
                      <button class='btn-reject' name='block' value='Rejected' type='submit'><i class='fas fa-times me-1'></i>Delete Record</button>
                    </form>
                    <button class='btn-view-d' onclick='viewDetail(this)' 
                      data-name='" . htmlspecialchars($user['student_name']) . "'
                      data-email='" . htmlspecialchars($user['student_email']) . "'
                      data-contact='" . htmlspecialchars($user['student_contact']) . "'
                      data-room='" . htmlspecialchars($user['room_number'] ?: 'Not Assigned') . "'
                      data-joindate='" . htmlspecialchars($user['booking_created_at']) . "'
                      data-status='" . htmlspecialchars($user['booking_status']) . "'>
                      <i class='fas fa-eye'></i>
                    </button>
                  </div>
                </td>";
            echo "</tr>";
            echo "</tbody>";
            $i++;
      }}
        else {
            echo "
            <tbody id='stuBody'>
              <tr>
                <td colspan='8' class='text-center py-4 text-muted'>No records found. Please fetch data from DB.</td>
              </tr>
            </tbody>";
        }
      ?> 

    </table>
  </div>
</div>

<!-- VIEW STUDENT MODAL -->
<div class="modal fade" id="viewStudentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:14px; border:none;">
      <div class="modal-header" style="background:#1a1f2e; color:#fff; border-radius:14px 14px 0 0;">
        <h5 class="modal-title fw-bold"><i class="fas fa-user-circle me-2" style="color:#00bcd4;"></i>Student Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
      </div>
      <div class="modal-body p-4">
        <!-- Student Avatar -->
        <div class="text-center mb-3">
          <div class="student-avatar" id="studentInitial">A</div>
          <h5 class="fw-bold mb-0" id="studentName">-</h5>
        </div>

        <!-- Student Details -->
        <div style="background:#f8f9fa; border-radius:10px; padding:20px;">
          <div class="detail-row">
            <div class="detail-label">Email</div>
            <div class="detail-value" id="studentEmail">-</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Contact</div>
            <div class="detail-value" id="studentContact">-</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Room No.</div>
            <div class="detail-value" id="studentRoom">-</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Join Date</div>
            <div class="detail-value" id="studentJoinDate">-</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Status</div>
            <div class="detail-value">
              <span class="status-badge" id="studentStatus">-</span>
            </div>
          </div>
        </div>

        <div class="d-flex gap-2 mt-4 justify-content-end">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ADD STUDENT MODAL -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:14px; border:none;">
      <div class="modal-header" style="background:#1a1f2e; color:#fff; border-radius:14px 14px 0 0;">
        <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2" style="color:#00bcd4;"></i>Add Student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
      </div>
      <div class="modal-body p-4">
        <form method="POST" action="../../Backend/backend.php">

          <!-- hostel_id so backend knows which hostel -->
          <input type="hidden" name="hostel_id" value="<?php echo $hostel_id; ?>">

          <div class="row g-3">
            <div class="col-12">
              <label class="form-lbl fw-bold" style="font-size:.82rem;">Full Name <span class="text-danger">*</span></label>
              <input type="text" class="filter-inp w-100" name="name" placeholder="Enter student name" required/>
            </div>
            <div class="col-12">
              <label class="form-lbl fw-bold" style="font-size:.82rem;">Email <span class="text-danger">*</span></label>
              <input type="email" class="filter-inp w-100" name="email" placeholder="Enter email" required/>
            </div>
            <div class="col-md-6">
              <label class="form-lbl fw-bold" style="font-size:.82rem;">Contact <span class="text-danger">*</span></label>
              <input type="text" class="filter-inp w-100" name="contact" placeholder="03-XXX-XXXXX" required/>
            </div>
            <div class="col-md-6">
              <label class="form-lbl fw-bold" style="font-size:.82rem;">Password <span class="text-danger">*</span></label>
              <input type="password" class="filter-inp w-100" name="password" placeholder="Set password" required/>
            </div>
            <div class="col-12">
              <label class="form-lbl fw-bold" style="font-size:.82rem;">Address</label>
              <input type="text" class="filter-inp w-100" name="address" placeholder="Enter address"/>
            </div>
            <div class="col-12">
  <label class="form-lbl fw-bold" style="font-size:.82rem;">Room Type</label>

  <div style="display:flex; gap:10px; margin-top:5px;">
    
    <?php
          // AC rooms
          $stmt = $conn->prepare("SELECT COUNT(*) as total FROM room WHERE hostel_id = ? AND capacity - occupied > 0 AND room_type = 'AC'");
          $stmt->bind_param("i", $_SESSION['hostel_id']);
          $stmt->execute();
          $AC_left = $stmt->get_result()->fetch_assoc()['total'];

          // Non-AC rooms
          $stmt = $conn->prepare("SELECT COUNT(*) as total FROM room WHERE hostel_id = ? AND capacity - occupied > 0 AND room_type = 'Non AC'");
          $stmt->bind_param("i", $_SESSION['hostel_id']);
          $stmt->execute();
          $Non_AC_left = $stmt->get_result()->fetch_assoc()['total'];
          ?>

          <!-- AC Option -->
          <label>
            <input type="radio" name="room_type" value="AC" <?php echo ($AC_left == 0) ? 'disabled' : ''; ?>>
            AC (<?php echo $AC_left; ?> left)
          </label>

          <!-- Non-AC Option -->
          <label>
            <input type="radio" name="room_type" value="Non AC" <?php echo ($Non_AC_left == 0) ? 'disabled' : ''; ?>>
            Non AC (<?php echo $Non_AC_left; ?> left)
          </label>

        </div>
      </div>
          </div>

          <div class="d-flex gap-2 mt-4">
            <button type="submit" name="add_student" class="btn-add">
              <i class="fas fa-save"></i> Save Student
            </button>
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script>
// Function to view student details in modal
function viewDetail(btn) {
  // Get data from button's data attributes
  const name = btn.getAttribute('data-name');
  const email = btn.getAttribute('data-email');
  const contact = btn.getAttribute('data-contact');
  const room = btn.getAttribute('data-room');
  const joinDate = btn.getAttribute('data-joindate');
  const status = btn.getAttribute('data-status');

  // Populate modal fields
  document.getElementById('studentName').textContent = name;
  document.getElementById('studentEmail').textContent = email;
  document.getElementById('studentContact').textContent = contact;
  document.getElementById('studentRoom').textContent = room;
  document.getElementById('studentJoinDate').textContent = joinDate;
  
  // Set status badge
  const statusBadge = document.getElementById('studentStatus');
  statusBadge.textContent = status;
  
  // Apply status-specific styling
  statusBadge.className = 'status-badge';
  if (status.toLowerCase() === 'approved') {
    statusBadge.classList.add('approved');
  } else if (status.toLowerCase() === 'pending') {
    statusBadge.classList.add('pending');
  } else if (status.toLowerCase() === 'rejected') {
    statusBadge.classList.add('rejected');
  }

  // Set student initial in avatar (first letter of name)
  const initial = name.charAt(0).toUpperCase();
  document.getElementById('studentInitial').textContent = initial;

  // Show the modal
  const modal = new bootstrap.Modal(document.getElementById('viewStudentModal'));
  modal.show();
}
</script>

<?php include("footer.php"); ?>