<?php
/**
 * ============================================
 * ADMIN DASHBOARD - MAIN PAGE
 * ============================================
 * Shows overview of all important statistics
 */

include("header.php");

// ============================================
// DUMMY DATA - REPLACE WITH DATABASE LATER
// ============================================
// $totalUsers = 145;
// $totalHostels = 19;

$totalComplaints = 31;

$conn = new mysqli("localhost","root","","hostel");


$totalUsers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as count FROM student")
)['count'];

$totalHostels = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as count FROM hostel")
)['count'];

$pendingApprovals = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as count FROM complaints WHERE status='pending'")
)['count'];

$totalComplaints = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as count FROM complaints")
)['count'];

$activeHostels = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as count FROM hostel")
)['count'];

// $blockedUsers = mysqli_fetch_assoc(
//     mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE status='blocked'")
// )['count'];

// Now Rescent Activities


?>

<div class="container-fluid py-5">
    <div class="row mb-5">
        <div class="col-md-12">
            <h1 class="fw-bold text-dark mb-2">Admin Dashboard</h1>
            <p class="text-muted">Welcome back! Here's what's happening today.</p>
        </div>
    </div>

    <!-- ================================ -->
    <!-- STATISTICS CARDS ROW -->
    <!-- ================================ -->
    <div class="row g-4 mb-5">
        
        <!-- Total Users Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-2">Total Registrations</p>
                            <h2 class="fw-bold text-primary mb-0"><?php echo $totalUsers; ?></h2>
                            <small class="text-success d-block mt-2">
                                <i class="fas fa-arrow-up"></i> Accounts Registered
                            </small>
                        </div>
                        <div class="p-3 rounded-circle bg-light">
                            <i class="fas fa-users text-primary" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Hostels Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-2">Total Hostels</p>
                            <h2 class="fw-bold text-success mb-0"><?php echo $totalHostels; ?></h2>
                            <small class="text-success d-block mt-2">
                                <i class="fas fa-arrow-up"></i> <?php echo $activeHostels; ?> Active Hostels
                            </small>
                        </div>
                        <div class="p-3 rounded-circle bg-light">
                            <i class="fas fa-building text-success" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Approvals Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-2">Pending Complaints</p>
                            <h2 class="fw-bold text-warning mb-0"><?php echo $pendingApprovals; ?></h2>
                            <small class="text-warning d-block mt-2">
                                <i class="fas fa-clock"></i> Awaiting for Complaint Resolve
                            </small>
                        </div>
                        <div class="p-3 rounded-circle bg-light">
                            <i class="fas fa-hourglass-half text-warning" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Complaints Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-2">Total Complaints</p>
                            <h2 class="fw-bold text-danger mb-0"><?php echo $totalComplaints; ?></h2>
                            <small class="text-danger d-block mt-2">
                                <i class="fas fa-bell"></i> Need attention
                            </small>
                        </div>
                        <div class="p-3 rounded-circle bg-light">
                            <i class="fas fa-exclamation-triangle text-danger" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================================ -->
    <!-- QUICK ACTIONS ROW -->
    <!-- ================================ -->
    <div class="row mb-5">
        <div class="col-md-12">
            <h5 class="fw-bold mb-4">Quick Actions</h5>
        </div>

        <div class="col-12 col-sm-6 col-lg-4 mb-4">
            <a href="hostels.php" class="text-decoration-none">
                <div class="card border-0 shadow-sm text-center py-4 cursor-pointer hover-lift">
                    <div class="card-body">
                        <i class="fas fa-home text-success mb-3" style="font-size: 32px;"></i>
                        <h6 class="fw-bold">Manage Hostels</h6>
                        <small class="text-muted">View all hostels</small>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-lg-4 mb-4">
            <a href="students.php" class="text-decoration-none">
                <div class="card border-0 shadow-sm text-center py-4 cursor-pointer hover-lift">
                    <div class="card-body">
                        <i class="fas fa-graduation-cap text-info mb-3" style="font-size: 32px;"></i>
                        <h6 class="fw-bold">Manage Students</h6>
                        <small class="text-muted">View all users</small>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-lg-4 mb-4">
            <a href="complaints.php" class="text-decoration-none">
                <div class="card border-0 shadow-sm text-center py-4 cursor-pointer hover-lift">
                    <div class="card-body">
                        <i class="fas fa-comments text-danger mb-3" style="font-size: 32px;"></i>
                        <h6 class="fw-bold">Complaints</h6>
                        <small class="text-muted">View all complaints</small>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- ================================ -->
    <!-- RECENT ACTIVITY SECTION (OPTIONAL) -->
    <!-- ================================ -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-bottom bg-light">
                    <h6 class="fw-bold mb-0">Recent Activity</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 fw-bold">New user registered</p>
                                <small class="text-muted">Ali Ahmed from Karachi</small>
                            </div>
                            <small class="text-muted">6 hours ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Custom Styles -->
<style>
    .cursor-pointer {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .cursor-pointer:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
    }
</style>

<?php
include("footer.php");
?>