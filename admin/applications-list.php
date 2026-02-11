<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();
$pageTitle = 'Applications - ' . APP_NAME;
$applications = [];
try {
    $applications = getRows("SELECT * FROM applications ORDER BY CreatedAt DESC");
} catch (Exception $e) { $applications = []; }
?>
<?php require_once 'includes/head.php'; ?>
<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="page-header mb-4 mt-3">
            <h1 class="page-title mb-0">Applications</h1>
        </div>
        <div class="card-box pd-20">
            <?php if (!empty($applications)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Application ID</th>
                            <th>Candidate Name</th>
                            <th>Course</th>
                            <th>Faculty</th>
                            <th>Email</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $a): ?>
                        <tr>
                            <td class="weight-500"><?php echo htmlspecialchars($a['ApplicationId'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($a['CandidateName'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($a['Course'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($a['Faculty'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($a['EmailAddress'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($a['Created'] ?? ''); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-muted mb-0">No applications found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
</body>
</html>
