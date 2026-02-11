<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();
$pageTitle = 'Clinical Experts - ' . APP_NAME;
$experts = [];
try {
    $experts = getRows("SELECT * FROM experts ORDER BY CreatedAt DESC");
} catch (Exception $e) { $experts = []; }
?>
<?php require_once 'includes/head.php'; ?>
<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="page-header mb-4 mt-3">
            <h1 class="page-title mb-0">Clinical Experts</h1>
        </div>
        <div class="card-box pd-20">
            <?php if (!empty($experts)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Specialization</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($experts as $ex): ?>
                        <tr>
                            <td class="weight-500"><?php echo htmlspecialchars($ex['Name'] ?? ($ex['FullName'] ?? '')); ?></td>
                            <td><?php echo htmlspecialchars($ex['Specialization'] ?? ($ex['Department'] ?? '')); ?></td>
                            <td><span class="badge badge-<?php echo (($ex['Status'] ?? ($ex['IsActive'] ?? 0)) === 'active' || ($ex['IsActive'] ?? 0) == 1) ? 'success':'secondary'; ?>"><?php echo (($ex['Status'] ?? ($ex['IsActive'] ?? 0)) === 'active' || ($ex['IsActive'] ?? 0) == 1) ? 'Active':'Inactive'; ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-muted mb-0">No experts found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
</body>
</html>
