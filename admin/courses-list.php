<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();
$pageTitle = 'Courses - ' . APP_NAME;
$courses = [];
try {
    $courses = getRows("SELECT * FROM courses ORDER BY CreatedAt DESC");
} catch (Exception $e) { $courses = []; }
?>
<?php require_once 'includes/head.php'; ?>
<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="page-header mb-4 mt-3">
            <h1 class="page-title mb-0">Courses</h1>
        </div>
        <div class="card-box pd-20">
            <?php if (!empty($courses)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>Full Name</th>
                            <th>Category</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $c): ?>
                        <tr>
                            <td class="weight-500"><?php echo htmlspecialchars($c['CourseName'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($c['CourseFullName'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($c['Category'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($c['Duration'] ?? ''); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-muted mb-0">No courses found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
</body>
</html>
