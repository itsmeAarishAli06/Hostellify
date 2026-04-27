<?php
/**
 * ============================================
 * STUDENTS MANAGEMENT
 * ============================================
 * Admin can view and manage all students
 */

include("header.php");

// Database connection
$conn = new mysqli("localhost","root","","hostel");

// Fetch students with booking count
$stmt = $conn->prepare( 
    "SELECT s.*, 
            COUNT(b.id) as total_bookings,
            s.created_at
     FROM student s
     LEFT JOIN booking b ON s.id = b.student_id
     GROUP BY s.id
     ORDER BY s.name ASC"
);
$stmt->execute();
$result = $stmt->get_result();
$students = [];
while($row = mysqli_fetch_assoc($result)) {
    $students[] = $row;
}

?>

<style>
.stat-badge {
    font-size: 1.1rem;
    padding: 0.6rem 1.2rem;
}

.action-btn {
    padding: 0.4rem 0.8rem;
    font-size: 0.875rem;
    transition: all 0.2s;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.table-responsive {
    border-radius: 0.5rem;
    overflow: hidden;
}

.table thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    padding: 1rem;
    font-weight: 600;
}

.table tbody tr {
    transition: all 0.2s;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
}

.status-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 0.25rem;
    font-weight: 500;
}

.modal-header {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
    color: white;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.info-row {
    border-bottom: 1px solid #e9ecef;
    padding: 0.75rem 0;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    color: #6c757d;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.info-value {
    color: #212529;
    font-weight: 600;
}
</style>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-dark mb-1">
                        <i class="fas fa-users text-primary me-2"></i>Students Management
                    </h2>
                    <p class="text-muted mb-0">View and manage all registered students</p>
                </div>
                <div>
                    <span class="badge bg-primary stat-badge">
                        <i class="fas fa-user-graduate me-1"></i><?php echo count($students); ?> Total Students
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0" 
                       placeholder="Search by name or email...">
            </div>
        </div>
        <div class="col-md-3">
            <select id="statusFilter" class="form-select">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="blocked">Blocked</option>
            </select>
        </div>
    </div>

    <!-- Table Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="fw-bold text-dark">Student Name</th>
                                <th class="fw-bold text-dark">Email</th>
                                <th class="fw-bold text-dark">Contact</th>
                                <th class="fw-bold text-dark">Address</th>
                                <th class="fw-bold text-dark">University</th>
                                <th class="fw-bold text-dark">Joined</th>
                                <th class="fw-bold text-dark">Bookings</th>
                                <th class="fw-bold text-dark">Status</th>
                                <th class="fw-bold text-dark text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php if(count($students) > 0): ?>
                                <?php foreach($students as $student): ?>
                                <tr class="student-row" 
                                    data-status="<?php echo strtolower($student['status']); ?>"
                                    data-search="<?php echo strtolower($student['name'] . ' ' . $student['email']); ?>">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white me-2" 
                                                 style="width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                                <?php echo strtoupper(substr($student['name'], 0, 1)); ?>
                                            </div>
                                            <span class="fw-bold text-dark"><?php echo htmlspecialchars($student['name']); ?></span>
                                        </div>
                                    </td>
                                    <td class="text-muted"><?php echo htmlspecialchars($student['email'] ?? "N/A"); ?></td>
                                    <td class="text-muted"><?php echo htmlspecialchars($student['contact'] ?? "N/A"); ?></td>
                                    <td class="text-muted"><?php echo htmlspecialchars($student['address'] ?? "N/A"); ?></td>
                                    <td class="text-muted"><?php echo htmlspecialchars($student['university'] ?? "N/A"); ?></td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo !empty($student['created_at']) ? date("M d, Y", strtotime($student['created_at'])) : "N/A"; ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?php echo $student['total_bookings']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if(strtolower($student['status']) == 'active'): ?>
                                            <span class="status-badge bg-success-subtle text-success">
                                                <i class="fas fa-check-circle"></i> Active
                                            </span>
                                        <?php else: ?>
                                            <span class="status-badge bg-danger-subtle text-danger">
                                                <i class="fas fa-ban"></i> Blocked
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary action-btn me-1" 
                                                onclick="viewStudent(<?php echo $student['id']; ?>)"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewModal">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        <?php if(strtolower($student['status']) == 'active'): ?>
                                            <button class="btn btn-sm btn-outline-danger action-btn" 
                                                    onclick="blockStudent(<?php echo $student['id']; ?>, '<?php echo htmlspecialchars($student['name']); ?>')"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#blockModal">
                                                <i class="fas fa-ban"></i> Block
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-success action-btn" 
                                                    onclick="unblockStudent(<?php echo $student['id']; ?>, '<?php echo htmlspecialchars($student['name']); ?>')"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#unblockModal">
                                                <i class="fas fa-check-circle"></i> Unblock
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="fas fa-user-slash text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3 mb-0 fw-bold">No students registered yet</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================================ -->
<!-- MODAL - VIEW DETAILS -->
<!-- ================================ -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-user-circle me-2"></i>Student Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="studentDetailsBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-2">Loading student details...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================================ -->
<!-- MODAL - BLOCK -->
<!-- ================================ -->
<div class="modal fade" id="blockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-ban me-2"></i>Block Student
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning border-warning" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> Blocked students cannot login or access the system
                </div>
                <p class="mb-2">Are you sure you want to block this student?</p>
                <div class="p-3 bg-light rounded">
                    <strong id="blockStudentName"></strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" onclick="submitBlock()">
                    <i class="fas fa-ban"></i> Block Student
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ================================ -->
<!-- MODAL - UNBLOCK -->
<!-- ================================ -->
<div class="modal fade" id="unblockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-check-circle me-2"></i>Unblock Student
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success border-success" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Success:</strong> This student will regain full access to the system
                </div>
                <p class="mb-2">Are you sure you want to unblock this student?</p>
                <div class="p-3 bg-light rounded">
                    <strong id="unblockStudentName"></strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-success" onclick="submitUnblock()">
                    <i class="fas fa-check-circle"></i> Unblock Student
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- JavaScript -->
<script>
let currentStudentId = null;

// View student details - Fetch real data via AJAX
function viewStudent(id) {
    currentStudentId = id;
    
    // Show loading state
    document.getElementById('studentDetailsBody').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mt-2">Loading student details...</p>
        </div>
    `;
    
    // Fetch student details
    fetch('../../Backend/backend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=get_student_details&student_id=' + id
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const student = data.student;
            document.getElementById('studentDetailsBody').innerHTML = `
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Full Name</div>
                            <div class="info-value">${student.name}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Email</div>
                            <div class="info-value">${student.email}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Contact Number</div>
                            <div class="info-value">${student.contact || 'N/A'}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Address</div>
                            <div class="info-value">${student.address || 'N/A'}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">University</div>
                            <div class="info-value">${student.university || 'N/A'}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Joined Date</div>
                            <div class="info-value">${student.created_at_formatted || 'N/A'}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Total Bookings</div>
                            <div class="info-value">
                                <span class="badge bg-primary">${student.total_bookings}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Account Status</div>
                            <div class="info-value">
                                ${student.status.toLowerCase() === 'active' 
                                    ? '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Active</span>' 
                                    : '<span class="badge bg-danger"><i class="fas fa-ban"></i> Blocked</span>'}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else {
            document.getElementById('studentDetailsBody').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> ${data.message || 'Failed to load student details'}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('studentDetailsBody').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> An error occurred while loading student details
            </div>
        `;
    });
}

function blockStudent(id, name) {
    currentStudentId = id;
    document.getElementById('blockStudentName').textContent = name;
}

function unblockStudent(id, name) {
    currentStudentId = id;
    document.getElementById('unblockStudentName').textContent = name;
}

function submitBlock() {
    if(!currentStudentId) return;
    
    // Send block request to backend
    fetch('../../Backend/backend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=block_student&student_id=' + currentStudentId
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Show success message
            alert('Student blocked successfully!');
            // Close modal and reload
            const modal = bootstrap.Modal.getInstance(document.getElementById('blockModal'));
            modal.hide();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to block student'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while blocking the student');
    });
}

function submitUnblock() {
    if(!currentStudentId) return;
    
    // Send unblock request to backend
    fetch('../../Backend/backend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=unblock_student&student_id=' + currentStudentId
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Show success message
            alert('Student unblocked successfully!');
            // Close modal and reload
            const modal = bootstrap.Modal.getInstance(document.getElementById('unblockModal'));
            modal.hide();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to unblock student'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while unblocking the student');
    });
}

// Search and Filter
document.getElementById('searchInput').addEventListener('keyup', filterTable);
document.getElementById('statusFilter').addEventListener('change', filterTable);

function filterTable() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const statusValue = document.getElementById('statusFilter').value.toLowerCase();
    const rows = document.querySelectorAll('.student-row');
    
    rows.forEach(row => {
        const searchText = row.dataset.search;
        const status = row.dataset.status;
        
        const matchSearch = searchText.includes(searchValue);
        const matchStatus = statusValue === '' || status === statusValue;
        
        if(matchSearch && matchStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

<?php
include("footer.php");
?>