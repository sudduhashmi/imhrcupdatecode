<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();
$pageTitle = 'Results - ' . APP_NAME;
$results = [];
try {
    $results = getRows("SELECT * FROM results ORDER BY Created DESC");
} catch (Exception $e) { $results = []; }
?>
<?php require_once 'includes/head.php'; ?>
<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="page-header mb-4 mt-3">
            <h1 class="page-title mb-0">Results</h1>
        </div>
        <div class="card-box pd-20">
            <?php if (!empty($results)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Result ID</th>
                            <th>Email</th>
                            <th>File</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $r): ?>
                        <tr>
                            <td class="weight-500"><?php echo htmlspecialchars($r['ResultId'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($r['Email'] ?? ''); ?></td>
                            <td>
                                <?php if (!empty($r['Result'])): ?>
                                    <a href="<?php echo htmlspecialchars($r['Result']); ?>" target="_blank">View</a>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($r['Created'] ?? ''); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-muted mb-0">No results found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
</body>
</html>
