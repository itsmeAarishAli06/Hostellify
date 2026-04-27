<?php
/**
 * ============================================
 * HOSTELS MANAGEMENT
 * ============================================
 * Admin can view and manage all hostels
 */

include("header.php");

$conn = new mysqli("localhost","root","","hostel");
$result = mysqli_query($conn, 
    "SELECT 
        h.id AS hostel_id,
        h.hostel_name,
        h.address,
        o.name AS owner_name,
        COUNT(r.id) AS total_rooms,
        SUM(r.capacity) AS total_capacity,
        MIN(r.price_monthly) AS min_price_monthly,
        MAX(r.price_monthly) AS max_price_monthly
    FROM hostel h
    JOIN owner o ON o.id = h.owner_id
    LEFT JOIN room r ON r.hostel_id = h.id
    GROUP BY h.id, h.hostel_name, h.address, o.name
    ORDER BY h.id"
);

$hostels = [];
while($row = mysqli_fetch_assoc($result)) {
    $hostels[] = $row;
}
?>

<style>
/* Professional Table Styling */
.tbl-card { 
    border-radius: 14px; 
    border: 1px solid #e9ecef; 
    box-shadow: 0 4px 18px rgba(0,0,0,0.06); 
    overflow: hidden; 
    background: #fff;
}
.tbl-card-head { 
    padding: 18px 24px; 
    border-bottom: 1px solid #f0f0f0; 
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
    flex-wrap: wrap; 
    gap: 12px; 
    background: #fff;
}
.tbl-card-head h6 { 
    margin: 0; 
    font-weight: 700; 
    font-size: 1rem; 
    color: #1a202c;
}
.professional-table { 
    margin: 0; 
    width: 100%;
}
.professional-table thead th { 
    font-size: .75rem; 
    font-weight: 700; 
    text-transform: uppercase; 
    letter-spacing: .6px; 
    color: #6c757d; 
    background: #f8f9fa; 
    padding: 14px 16px; 
    white-space: nowrap; 
    border-bottom: 2px solid #e9ecef;
}
.professional-table tbody td { 
    padding: 16px; 
    font-size: .88rem; 
    vertical-align: middle; 
    border-bottom: 1px solid #f0f0f0; 
    color: #1a202c;
}
.professional-table tbody tr:last-child td {
    border-bottom: none;
}
.professional-table tbody tr:hover { 
    background: #f8f9fa; 
    transition: background 0.15s ease;
}

/* Badge Styling */
.badge-custom { 
    padding: 6px 14px; 
    border-radius: 50px; 
    font-size: .75rem; 
    font-weight: 600; 
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.badge-rooms { 
    background: rgba(0,188,212,.12); 
    color: #007b8a; 
}
.badge-beds { 
    background: rgba(111,66,193,.12); 
    color: #6f42c1; 
}
.badge-count {
    background: rgba(0,188,212,.15);
    color: #00bcd4;
    padding: 8px 18px;
    border-radius: 50px;
    font-size: .85rem;
    font-weight: 700;
}

/* Button Styling */
.btn-action { 
    padding: 7px 16px; 
    border-radius: 8px; 
    font-size: .8rem; 
    font-weight: 600; 
    border: 1.5px solid; 
    cursor: pointer; 
    transition: all .2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-view { 
    background: rgba(0,188,212,.1); 
    color: #00bcd4; 
    border-color: rgba(0,188,212,.2); 
}
.btn-view:hover { 
    background: #00bcd4; 
    color: #fff; 
    border-color: #00bcd4;
}
.btn-delete { 
    background: rgba(220,53,69,.1); 
    color: #dc3545; 
    border-color: rgba(220,53,69,.2); 
}
.btn-delete:hover { 
    background: #dc3545; 
    color: #fff; 
    border-color: #dc3545;
}

/* Search Input */
.search-input { 
    border: 1.5px solid #dee2e6; 
    border-radius: 10px; 
    padding: 10px 16px; 
    font-size: .88rem; 
    outline: none; 
    transition: border .2s; 
    font-family: inherit;
}
.search-input:focus { 
    border-color: #00bcd4; 
    box-shadow: 0 0 0 3px rgba(0,188,212,.1);
}

/* View Modal Styling */
.detail-section {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 20px;
}
.detail-row { 
    display: flex; 
    padding: 14px 0; 
    border-bottom: 1px solid #e9ecef; 
}
.detail-row:last-child { 
    border-bottom: none; 
}
.detail-label { 
    font-size: .78rem; 
    font-weight: 700; 
    color: #6c757d; 
    text-transform: uppercase; 
    letter-spacing: .6px; 
    width: 140px; 
    flex-shrink: 0; 
}
.detail-value { 
    font-size: .92rem; 
    color: #1a202c; 
    font-weight: 500; 
}
.hostel-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 2rem;
    margin: 0 auto 20px;
    box-shadow: 0 6px 20px rgba(0,188,212,0.3);
}
.stat-box {
    text-align: center;
    padding: 16px;
    background: #fff;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}
.stat-box .value {
    font-size: 1.4rem;
    font-weight: 700;
    color: #00bcd4;
}
.stat-box .label {
    font-size: .75rem;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-top: 4px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}
.empty-state i {
    font-size: 3.5rem;
    color: #dee2e6;
    margin-bottom: 20px;
}
.empty-state p {
    color: #6c757d;
    font-size: .95rem;
}
</style>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1a202c;">Hostels Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size:.8rem;">
                    <li class="breadcrumb-item"><a href="index.php" style="color:#00bcd4;">Dashboard</a></li>
                    <li class="breadcrumb-item active">Hostels</li>
                </ol>
            </nav>
        </div>
        <span class="badge-count">
            <i class="fas fa-building me-1"></i><?php echo count($hostels); ?> Total
        </span>
    </div>

    <!-- Search Section -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <input type="text" id="searchInput" class="search-input w-100" placeholder="🔍 Search by hostel name...">
        </div>
        <div class="col-md-6">
            <select id="statusFilter" class="search-input w-100">
                <option value="">All Status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card tbl-card">
        <div class="tbl-card-head">
            <h6><i class="fas fa-building me-2" style="color:#00bcd4;"></i>All Hostels</h6>
        </div>
        <div class="table-responsive">
            <table class="professional-table">
                <thead>
                    <tr>
                        <th>Hostel Name</th>
                        <th>Location</th>
                        <th>Owner</th>
                        <th>Rooms/Beds</th>
                        <th>Price Range</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($hostels) > 0): ?>
                        <?php foreach($hostels as $hostel): ?>
                        <tr class="hostel-row" data-name="<?php echo strtolower($hostel['hostel_name']); ?>">
                            <td>
                                <div class="fw-bold" style="color:#1a202c;">
                                    <?php echo htmlspecialchars($hostel['hostel_name']); ?>
                                </div>
                            </td>
                            <td>
                                <span style="color:#6c757d;">
                                    <i class="fas fa-map-marker-alt me-1" style="color:#00bcd4;"></i>
                                    <?php echo htmlspecialchars($hostel['address']); ?>
                                </span>
                            </td>
                            <td>
                                <span style="color:#6c757d;">
                                    <i class="fas fa-user me-1" style="color:#6f42c1;"></i>
                                    <?php echo htmlspecialchars($hostel['owner_name']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge-custom badge-rooms">
                                    <i class="fas fa-door-open"></i> <?php echo $hostel['total_rooms']; ?> Rooms
                                </span>
                                <span class="badge-custom badge-beds">
                                    <i class="fas fa-bed"></i> <?php echo $hostel['total_capacity']; ?> Beds
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold" style="color:#00bcd4;">
                                    ₨<?php echo number_format($hostel['min_price_monthly']); ?> - 
                                    ₨<?php echo number_format($hostel['max_price_monthly']); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn-action btn-view" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#viewModal_<?php echo $hostel['hostel_id']; ?>">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn-action btn-delete" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal_<?php echo $hostel['hostel_id']; ?>">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>

                        <!-- VIEW MODAL -->
                        <div class="modal fade" id="viewModal_<?php echo $hostel['hostel_id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content" style="border-radius:14px; border:none;">
                                    <div class="modal-header" style="background:#1a1f2e; color:#fff; border-radius:14px 14px 0 0;">
                                        <h5 class="modal-title fw-bold">
                                            <i class="fas fa-building me-2" style="color:#00bcd4;"></i>Hostel Details
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <!-- Hostel Icon -->
                                        <div class="text-center mb-4">
                                            <div class="hostel-icon">
                                                <i class="fas fa-building"></i>
                                            </div>
                                            <h4 class="fw-bold mb-0" style="color:#1a202c;">
                                                <?php echo htmlspecialchars($hostel['hostel_name']); ?>
                                            </h4>
                                        </div>

                                        <!-- Basic Information -->
                                        <div class="detail-section">
                                            <div class="detail-row">
                                                <div class="detail-label">Owner Name</div>
                                                <div class="detail-value"><?php echo htmlspecialchars($hostel['owner_name']); ?></div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label">Address</div>
                                                <div class="detail-value"><?php echo htmlspecialchars($hostel['address']); ?></div>
                                            </div>
                                        </div>

                                        <!-- Statistics -->
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="stat-box">
                                                    <i class="fas fa-door-open" style="color:#00bcd4; font-size:1.5rem;"></i>
                                                    <div class="value"><?php echo $hostel['total_rooms']; ?></div>
                                                    <div class="label">Total Rooms</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="stat-box">
                                                    <i class="fas fa-bed" style="color:#6f42c1; font-size:1.5rem;"></i>
                                                    <div class="value"><?php echo $hostel['total_capacity']; ?></div>
                                                    <div class="label">Total Beds</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="stat-box">
                                                    <i class="fas fa-rupee-sign" style="color:#28a745; font-size:1.5rem;"></i>
                                                    <div class="value" style="font-size:1rem;">
                                                        ₨<?php echo number_format($hostel['min_price_monthly']); ?> - 
                                                        ₨<?php echo number_format($hostel['max_price_monthly']); ?>
                                                    </div>
                                                    <div class="label">Price Range/Month</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer" style="border-top:1px solid #f0f0f0;">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- DELETE MODAL -->
                        <div class="modal fade" id="deleteModal_<?php echo $hostel['hostel_id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="border-radius:14px; border:none;">
                                    <form action="../../Backend/backend.php" method="post">
                                        <div class="modal-header" style="background:#dc3545; color:#fff; border-radius:14px 14px 0 0;">
                                            <h5 class="modal-title fw-bold">
                                                <i class="fas fa-exclamation-triangle me-2"></i>Delete Hostel
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="alert alert-danger" style="border-radius:10px;">
                                                <i class="fas fa-exclamation-circle me-2"></i>
                                                <strong>Warning:</strong> This action cannot be undone!
                                            </div>
                                            <p style="font-size:.95rem; color:#6c757d;">
                                                Are you sure you want to delete 
                                                <strong style="color:#1a202c;"><?php echo htmlspecialchars($hostel['hostel_name']); ?></strong>?
                                            </p>
                                            <input type="hidden" name="hostel_id" value="<?php echo $hostel['hostel_id']; ?>">
                                        </div>
                                        <div class="modal-footer" style="border-top:1px solid #f0f0f0;">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" name="del_hostel" class="btn btn-danger">
                                                <i class="fas fa-trash me-1"></i> Delete Hostel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-building"></i>
                                    <p class="mb-0">No hostels found</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- JavaScript -->
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function(e) {
    const searchValue = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.hostel-row');
    
    rows.forEach(row => {
        const name = row.dataset.name;
        if(name.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Status filter functionality
document.getElementById('statusFilter').addEventListener('change', function(e) {
    const statusValue = e.target.value;
    const rows = document.querySelectorAll('.hostel-row');
    
    rows.forEach(row => {
        const status = row.dataset.status;
        if(statusValue === '' || status === statusValue) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

<?php
include("footer.php");
?>