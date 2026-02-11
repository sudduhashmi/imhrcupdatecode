<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

$pageTitle = 'Contact Messages - ' . APP_NAME;
requireLogin();

// --- HANDLE DELETE ---
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    $conn->query("DELETE FROM contact_messages WHERE id = $id");
    header("Location: contact-messages.php?msg=deleted");
    exit;
}

// Fetch Messages
$messages = getRows("SELECT * FROM contact_messages ORDER BY created_at DESC");
?>
<?php require_once 'includes/head.php'; ?>

<style>
    /* Blue Theme Enhancements */
    .bg-blue-gradient {
        background: linear-gradient(135deg, #0f1c2e 0%, #1c3d5a 100%);
        color: #ffffff;
    }
    
    .text-white-dim {
        color: rgba(255, 255, 255, 0.85) !important;
    }

    .badge-glass {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    .table-header-blue {
        background-color: #1c3d5a !important;
        color: #ffffff !important;
    }
    
    .table-header-blue th {
        color: #ffffff !important;
        font-weight: 600;
        border-bottom: none;
    }
</style>

<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
        
        <!-- Messages -->
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Message deleted successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Blue Header Section -->
        <div class="page-header mb-4 mt-3 bg-blue-gradient p-4 rounded-3 shadow-sm d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title mb-1 text-white fw-bold">
                    <i class="bi bi-envelope me-2"></i> Contact Inquiries
                </h4>
                <p class="text-white-dim small mb-0">View and manage messages from the website contact form.</p>
            </div>
            <div>
                <span class="badge badge-glass rounded-pill px-3 py-2">Total Messages: <?php echo count($messages); ?></span>
            </div>
        </div>

        <!-- Messages Table -->
        <div class="card shadow border-0 rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-header-blue">
                            <tr>
                                <th class="ps-4" width="5%">#</th>
                                <th width="15%">Date</th>
                                <th width="20%">Sender Info</th>
                                <th width="20%">Subject</th>
                                <th width="30%">Message Preview</th>
                                <th class="text-center pe-4" width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($messages) > 0): ?>
                            <?php $i=1; foreach($messages as $row): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-muted"><?php echo $i++; ?></td>
                                <td>
                                    <span class="d-block fw-bold text-dark" style="font-size: 0.9rem;"><?php echo date('d M, Y', strtotime($row['created_at'])); ?></span>
                                    <small class="text-muted"><?php echo date('h:i A', strtotime($row['created_at'])); ?></small>
                                </td>
                                <td>
                                    <strong class="text-primary d-block"><?php echo htmlspecialchars($row['name']); ?></strong>
                                    <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="text-muted small text-decoration-none"><i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($row['email']); ?></a>
                                    <?php if(!empty($row['phone'])): ?>
                                    <br><a href="tel:<?php echo htmlspecialchars($row['phone']); ?>" class="text-muted small text-decoration-none"><i class="bi bi-phone me-1"></i><?php echo htmlspecialchars($row['phone']); ?></a>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['subject']); ?></span></td>
                                <td>
                                    <span class="text-muted small d-block text-truncate" style="max-width: 250px;">
                                        <?php echo htmlspecialchars(substr($row['message'], 0, 80)) . '...'; ?>
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group shadow-sm">
                                        <button class="btn btn-sm btn-light border text-primary" 
                                                onclick='viewMessage(<?php echo json_encode($row); ?>)' title="View Full Message">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                        <a href="contact-messages.php?del=<?php echo $row['id']; ?>" 
                                           class="btn btn-sm btn-light border text-danger"
                                           title="Delete"
                                           onclick="return confirm('Are you sure you want to delete this message?')">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary-subtle"></i>
                                    No new messages found.
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

<!-- VIEW MESSAGE MODAL -->
<div class="modal fade" id="msgModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-blue-gradient">
                <h5 class="modal-title text-white"><i class="bi bi-envelope-open me-2"></i>Message Details</h5>
                <button type="button" class="btn-close btn-close-white" onclick="msgModal.hide()"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="mb-1 text-dark" id="modalName"></h5>
                        <p class="text-muted mb-0 small"><i class="bi bi-envelope me-1"></i> <span id="modalEmail"></span></p>
                        <p class="text-muted mb-0 small"><i class="bi bi-phone me-1"></i> <span id="modalPhone"></span></p>
                    </div>
                    <span class="badge bg-light text-dark border" id="modalDate"></span>
                </div>
                
                <hr class="text-muted opacity-25">
                
                <h6 class="fw-bold text-primary mb-2">Subject: <span id="modalSubject" class="text-dark fw-normal"></span></h6>
                <div class="p-3 bg-light rounded border">
                    <p class="mb-0 text-secondary" id="modalMessage" style="white-space: pre-wrap; font-family: sans-serif; line-height: 1.6;"></p>
                </div>
            </div>
            <div class="modal-footer bg-light border-top-0">
                <a href="#" id="replyBtn" class="btn btn-primary"><i class="bi bi-reply-fill me-1"></i> Reply via Email</a>
                <button type="button" class="btn btn-secondary" onclick="msgModal.hide()">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var msgModal;
    document.addEventListener("DOMContentLoaded", function() {
        msgModal = new bootstrap.Modal(document.getElementById('msgModal'));
    });

    function viewMessage(data) {
        document.getElementById('modalName').innerText = data.name;
        document.getElementById('modalEmail').innerText = data.email;
        document.getElementById('modalPhone').innerText = data.phone || 'N/A';
        document.getElementById('modalDate').innerText = new Date(data.created_at).toLocaleDateString();
        document.getElementById('modalSubject').innerText = data.subject;
        document.getElementById('modalMessage').innerText = data.message;
        
        // Setup Reply Button
        document.getElementById('replyBtn').href = "mailto:" + data.email + "?subject=Re: " + encodeURIComponent(data.subject);
        
        msgModal.show();
    }
</script>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>