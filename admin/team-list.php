<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

requireLogin();

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$dept = isset($_GET['dept']) ? sanitize($_GET['dept']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

$where = "Status = 'active'";
if (!empty($search)) $where .= " AND (Name LIKE '%$search%' OR Department LIKE '%$search%')";
if (!empty($dept)) $where .= " AND Department = '$dept'";

$countResult = getRow("SELECT COUNT(*) as total FROM team_members WHERE $where");
$totalRecords = $countResult['total'] ?? 0;
$totalPages = ceil($totalRecords / $perPage);

$team = getRows("SELECT * FROM team_members WHERE $where ORDER BY Name ASC LIMIT $offset, $perPage");

$pageTitle = 'Team Management - ' . APP_NAME;
?>
<?php require_once 'includes/head.php'; ?>

<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
                <div class="page-header mb-4 mt-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="page-title mb-0"><i class="fas fa-users"></i> Team Members</h1>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="<?php echo BASE_URL; ?>team-add.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-user-plus"></i> Add Member
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="GET" class="form-inline mb-3">
                            <input type="text" name="search" class="form-control mr-2" placeholder="Search team members..." value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="btn btn-primary btn-sm">Search</button>
                        </form>

                        <?php if (!empty($team)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Department</th>
                                        <th>Email</th>
                                        <th>Specialization</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sn = $offset + 1; foreach ($team as $t): ?>
                                    <tr>
                                        <td><?php echo $sn++; ?></td>
                                        <td><strong><?php echo htmlspecialchars($t['Name']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($t['Position'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($t['Department'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($t['Email'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($t['Specialization'] ?? '-'); ?></td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>team-edit.php?id=<?php echo $t['TeamMemberId']; ?>" class="btn btn-outline-primary btn-sm">Edit</a>
                                            <a href="<?php echo BASE_URL; ?>team-delete.php?id=<?php echo $t['TeamMemberId']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this member?');">Delete</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-info">No team members found</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>
