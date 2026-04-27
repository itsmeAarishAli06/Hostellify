<?php 
$conn = new mysqli("localhost","root","","hostel");



// ── Handle actions FIRST before any HTML ──
if (isset($_GET['mark_resolved'])) {
    $id = (int)$_GET['mark_resolved'];
    $conn->query("UPDATE contact_messages SET is_read=1 WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM contact_messages WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
include("header.php");
// ── Fetch stats ──
$stats = $conn->query("
    SELECT 
        COUNT(*) as total,
        SUM(is_read = 0) as pending,
        SUM(is_read = 1) as resolved,
        SUM(DATE(submitted_at) = CURDATE()) as today
    FROM contact_messages
")->fetch_assoc();

// ── Fetch all messages ──

$result = $conn->query("SELECT * FROM contact_messages ORDER BY submitted_at DESC");
?>

<div class="container-fluid px-4 py-4">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-0">Contact Queries</h4>
            <p class="text-muted small mb-0">All messages submitted via the contact form</p>
        </div>
        <span class="badge bg-primary fs-6 px-3 py-2"><?= $stats['total'] ?> Total</span>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-3 fw-bold text-primary"><?= $stats['total'] ?></div>
                <div class="text-muted small">Total</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-3 fw-bold text-warning"><?= $stats['pending'] ?></div>
                <div class="text-muted small">Pending</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-3 fw-bold text-success"><?= $stats['resolved'] ?></div>
                <div class="text-muted small">Resolved</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-3 fw-bold text-danger"><?= $stats['today'] ?></div>
                <div class="text-muted small">Today</div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()):
                            $date        = date("d M Y", strtotime($row['submitted_at']));
                            $is_resolved = $row['is_read'] == 1;
                            $status_text  = $is_resolved ? "Resolved" : "Pending";
                            $status_class = $is_resolved ? "bg-success-subtle text-success" : "bg-warning-subtle text-warning";
                        ?>
                        <tr>
                            <td class="ps-4 text-muted"><?= $row['id'] ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($row['full_name']) ?></td>
                            <td class="text-muted small"><?= htmlspecialchars($row['email']) ?></td>
                            <td class="text-muted small"><?= htmlspecialchars($row['phone']) ?></td>
                            <td><span class="badge bg-primary-subtle text-primary"><?= htmlspecialchars($row['subject']) ?></span></td>
                            <td class="text-muted small" style="max-width:200px;">
                                <span class="d-inline-block text-truncate" style="max-width:180px;">
                                    <?= htmlspecialchars($row['message']) ?>
                                </span>
                            </td>
                            <td class="text-muted small"><?= $date ?></td>
                            <td><span class="badge <?= $status_class ?>"><?= $status_text ?></span></td>
                            <td class="text-center">
                            <!-- 1. Eye button: ADD type="button" -->
                            <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                data-bs-toggle="modal" data-bs-target="#viewModal"
                                data-name="<?= htmlspecialchars($row['full_name'], ENT_QUOTES) ?>"
                                data-email="<?= htmlspecialchars($row['email'], ENT_QUOTES) ?>"
                                data-phone="<?= htmlspecialchars($row['phone'], ENT_QUOTES) ?>"
                                data-subject="<?= htmlspecialchars($row['subject'], ENT_QUOTES) ?>"
                                data-message="<?= htmlspecialchars($row['message'], ENT_QUOTES) ?>"
                                data-date="<?= $date ?>"
                                data-status="<?= $status_text ?>">
                                <i class="fas fa-eye"></i>
                            </button>

                            <a href="?mark_resolved=<?= $row['id'] ?>" class="btn btn-sm btn-outline-success me-1" 
                            onclick="return confirm('Mark as resolved?')">
                                <i class="fas fa-check"></i>
                            </a>
                    
                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Delete this message?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Query Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless table-sm">
                    <tr><th width="100" class="text-muted">Name</th>    <td id="m_name"></td></tr>
                    <tr><th class="text-muted">Email</th>   <td id="m_email"></td></tr>
                    <tr><th class="text-muted">Phone</th>   <td id="m_phone"></td></tr>
                    <tr><th class="text-muted">Subject</th> <td id="m_subject"></td></tr>
                    <tr><th class="text-muted">Date</th>    <td id="m_date"></td></tr>
                    <tr><th class="text-muted">Status</th>  <td id="m_status"></td></tr>
                    <tr><th class="text-muted align-top">Message</th><td id="m_message" class="text-muted small"></td></tr>
                </table>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// When modal opens, read data-* from the clicked button
document.getElementById('viewModal').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    document.getElementById('m_name').textContent    = btn.dataset.name;
    document.getElementById('m_email').textContent   = btn.dataset.email;
    document.getElementById('m_phone').textContent   = btn.dataset.phone || 'N/A';
    document.getElementById('m_subject').textContent = btn.dataset.subject;
    document.getElementById('m_message').textContent = btn.dataset.message;
    document.getElementById('m_date').textContent    = btn.dataset.date;
    document.getElementById('m_status').innerHTML    = btn.dataset.status === 'Resolved'
        ? '<span class="badge bg-success-subtle text-success">Resolved</span>'
        : '<span class="badge bg-warning-subtle text-warning">Pending</span>';
});
</script>

<?php include("footer.php"); ?>