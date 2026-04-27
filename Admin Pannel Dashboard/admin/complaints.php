<?php
/**
 * ============================================
 * COMPLAINTS MANAGEMENT
 * ============================================
 * Admin can view and manage all complaints
 */

include("header.php");

// Database connection
$conn = new mysqli("localhost","root","","hostel");

// Fetch all complaints with student and hostel info
$result = mysqli_query($conn, 
    "SELECT c.*, s.name as student_name, h.hostel_name as hostel_name 
     FROM complaints c 
     JOIN student s ON c.student_id = s.id 
     JOIN hostel h ON c.hostel_id = h.id 
     ORDER BY c.created_at DESC"
);

$complaints = [];
while($row = mysqli_fetch_assoc($result)) {
    $complaints[] = $row;
}

// Get unique hostels for filter
$hostel_result = mysqli_query($conn, "SELECT DISTINCT hostel_name FROM hostel ORDER BY hostel_name");
$hostels = [];
while($row = mysqli_fetch_assoc($hostel_result)) {
    $hostels[] = $row['hostel_name'];
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
    transform: scale(1.005);
}

.priority-badge, .status-badge, .type-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 0.25rem;
    font-weight: 500;
    font-size: 0.85rem;
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

.complaint-description {
    background: #f8f9fa;
    border-left: 4px solid var(--primary-blue);
    padding: 1rem;
    border-radius: 0.25rem;
}
</style>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-dark mb-1">
                        <i class="fas fa-exclamation-circle text-danger me-2"></i>Complaints Management
                    </h2>
                    <p class="text-muted mb-0">View and manage all student complaints</p>
                </div>
                <div>
                    <span class="badge bg-danger stat-badge">
                        <i class="fas fa-clipboard-list me-1"></i><?php echo count($complaints); ?> Total Complaints
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4 g-2">
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0" 
                       placeholder="Search by ID or subject...">
            </div>
        </div>
        <div class="col-md-3">
            <select id="hostelFilter" class="form-select">
                <option value="">All Hostels</option>
                <?php foreach($hostels as $hostel): ?>
                    <option value="<?php echo htmlspecialchars($hostel); ?>">
                        <?php echo htmlspecialchars($hostel); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select id="statusFilter" class="form-select">
                <option value="">All Status</option>
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Resolved">Resolved</option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="priorityFilter" class="form-select">
                <option value="">All Priorities</option>
                <option value="urgent">Urgent</option>
                <option value="Medium">Medium</option>
                <option value="Low">Low</option>
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
                                <th class="fw-bold text-dark">ID</th>
                                <th class="fw-bold text-dark">Subject</th>
                                <th class="fw-bold text-dark">From</th>
                                <th class="fw-bold text-dark">Hostel</th>
                                <th class="fw-bold text-dark">Type</th>
                                <th class="fw-bold text-dark">Filed</th>
                                <th class="fw-bold text-dark">Priority</th>
                                <th class="fw-bold text-dark">Status</th>
                                <th class="fw-bold text-dark text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php if(count($complaints) > 0): ?>
                                <?php foreach($complaints as $complaint): ?>
                                <tr class="complaint-row" 
                                    data-status="<?php echo htmlspecialchars($complaint['status']); ?>" 
                                    data-hostel="<?php echo htmlspecialchars($complaint['hostel_name']); ?>"
                                    data-priority="<?php echo htmlspecialchars($complaint['level']); ?>"
                                    data-search="<?php echo strtolower($complaint['id'] . ' ' . $complaint['subject']); ?>">
                                    <td>
                                        <span class="badge bg-dark">
                                            #<?php echo $complaint['id']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($complaint['subject']); ?></div>
                                        <small class="text-muted">
                                            <?php echo htmlspecialchars(substr($complaint['message'], 0, 50)) . '...'; ?>
                                        </small>
                                    </td>
                                    <td class="text-muted"><?php echo htmlspecialchars($complaint['student_name']); ?></td>
                                    <td class="text-muted"><?php echo htmlspecialchars($complaint['hostel_name']); ?></td>
                                    <td>
                                        <span class="type-badge bg-light text-dark border">
                                            <?php echo htmlspecialchars($complaint['complaint_type'] ?? "General"); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo date('M d, Y', strtotime($complaint['created_at'])); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php 
                                        $priority_color = 'secondary';
                                        $priority_icon = 'fa-flag';
                                        if($complaint['level'] == 'urgent') {
                                            $priority_color = 'danger';
                                            $priority_icon = 'fa-exclamation-triangle';
                                        } elseif($complaint['level'] == 'Medium') {
                                            $priority_color = 'warning';
                                        } elseif($complaint['level'] == 'Low') {
                                            $priority_color = 'success';
                                        }
                                        ?>
                                        <span class="priority-badge bg-<?php echo $priority_color; ?> text-white">
                                            <i class="fas <?php echo $priority_icon; ?>"></i> <?php echo htmlspecialchars($complaint['level']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        $status_color = 'warning';
                                        $status_icon = 'fa-clock';
                                        if($complaint['status'] == 'In Progress') {
                                            $status_color = 'info';
                                            $status_icon = 'fa-spinner';
                                        } elseif($complaint['status'] == 'Resolved') {
                                            $status_color = 'success';
                                            $status_icon = 'fa-check-circle';
                                        }
                                        ?>
                                        <span class="status-badge bg-<?php echo $status_color; ?> text-white">
                                            <i class="fas <?php echo $status_icon; ?>"></i> <?php echo htmlspecialchars($complaint['status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary action-btn" 
                                                onclick="viewComplaint(<?php echo $complaint['id']; ?>)"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewModal">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3 mb-0 fw-bold">No complaints found</p>
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
                    <i class="fas fa-file-alt me-2"></i>Complaint Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="complaintDetailsBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-2">Loading complaint details...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- JavaScript -->
<script>
let currentComplaintId = null;

// View complaint details - Fetch real data via AJAX
function viewComplaint(id) {
    currentComplaintId = id;
    
    // Show loading state
    document.getElementById('complaintDetailsBody').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mt-2">Loading complaint details...</p>
        </div>
    `;
    
    // Fetch complaint details
    fetch('../../Backend/backend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=get_complaint_details&complaint_id=' + id
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const c = data.complaint;
            
            // Determine priority color and icon
            let priorityClass = 'bg-secondary';
            let priorityIcon = 'fa-flag';
            if(c.level === 'urgent') {
                priorityClass = 'bg-danger';
                priorityIcon = 'fa-exclamation-triangle';
            } else if(c.level === 'Medium') {
                priorityClass = 'bg-warning';
            } else if(c.level === 'Low') {
                priorityClass = 'bg-success';
            }
            
            // Determine status color and icon
            let statusClass = 'bg-warning';
            let statusIcon = 'fa-clock';
            if(c.status === 'In Progress') {
                statusClass = 'bg-info';
                statusIcon = 'fa-spinner';
            } else if(c.status === 'Resolved') {
                statusClass = 'bg-success';
                statusIcon = 'fa-check-circle';
            }
            
            document.getElementById('complaintDetailsBody').innerHTML = `
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Complaint ID</div>
                            <div class="info-value">
                                <span class="badge bg-dark">#${c.id}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Status</div>
                            <div class="info-value">
                                <span class="badge ${statusClass} text-white">
                                    <i class="fas ${statusIcon}"></i> ${c.status}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Subject</div>
                            <div class="info-value">${c.subject}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Complaint Type</div>
                            <div class="info-value">
                                <span class="badge bg-light text-dark border">${c.complaint_type || 'General'}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Student Name</div>
                            <div class="info-value">${c.student_name}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Hostel Name</div>
                            <div class="info-value">${c.hostel_name}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Priority Level</div>
                            <div class="info-value">
                                <span class="badge ${priorityClass} text-white">
                                    <i class="fas ${priorityIcon}"></i> ${c.level}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Filed Date</div>
                            <div class="info-value">${c.created_at_formatted}</div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="info-row">
                            <div class="info-label mb-2">
                                <i class="fas fa-comment-alt me-1"></i>Complaint Description
                            </div>
                            <div class="complaint-description">
                                ${c.message}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else {
            document.getElementById('complaintDetailsBody').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> ${data.message || 'Failed to load complaint details'}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('complaintDetailsBody').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> An error occurred while loading complaint details
            </div>
        `;
    });
}

// Search and Filter
document.getElementById('searchInput').addEventListener('keyup', filterTable);
document.getElementById('hostelFilter').addEventListener('change', filterTable);
document.getElementById('statusFilter').addEventListener('change', filterTable);
document.getElementById('priorityFilter').addEventListener('change', filterTable);

function filterTable() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const hostelValue = document.getElementById('hostelFilter').value;
    const statusValue = document.getElementById('statusFilter').value;
    const priorityValue = document.getElementById('priorityFilter').value;
    const rows = document.querySelectorAll('.complaint-row');
    
    rows.forEach(row => {
        const searchText = row.dataset.search;
        const hostel = row.dataset.hostel;
        const status = row.dataset.status;
        const priority = row.dataset.priority;
        
        const matchSearch = searchText.includes(searchValue);
        const matchHostel = hostelValue === '' || hostel === hostelValue;
        const matchStatus = statusValue === '' || status === statusValue;
        const matchPriority = priorityValue === '' || priority === priorityValue;
        
        if(matchSearch && matchHostel && matchStatus && matchPriority) {
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