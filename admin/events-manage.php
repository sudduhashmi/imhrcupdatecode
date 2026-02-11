<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

requireLogin();

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

$where = "1=1";
if (!empty($search)) $where .= " AND Title LIKE '%$search%'";

$countResult = getRow("SELECT COUNT(*) as total FROM events WHERE $where");
$totalRecords = $countResult['total'] ?? 0;
$totalPages = ceil($totalRecords / $perPage);

$events = getRows("SELECT * FROM events WHERE $where ORDER BY StartDate DESC LIMIT $offset, $perPage");

$pageTitle = 'Events Management - ' . APP_NAME;
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
                            <h1 class="page-title mb-0"><i class="fas fa-calendar-alt"></i> Events & Conferences</h1>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="<?php echo BASE_URL; ?>event-add.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add Event
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="GET" class="form-inline mb-3">
                            <input type="text" name="search" class="form-control mr-2" placeholder="Search events..." value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="btn btn-primary btn-sm">Search</button>
                        </form>

                        <?php if (!empty($events)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sn = $offset + 1; foreach ($events as $e): ?>
                                    <tr>
                                        <td><?php echo $sn++; ?></td>
                                        <td><strong><?php echo htmlspecialchars($e['Title']); ?></strong></td>
                                        <td><span class="badge badge-info"><?php echo ucfirst($e['EventType']); ?></span></td>
                                        <td><?php echo date('M d, Y', strtotime($e['StartDate'])); ?></td>
                                        <td><span class="badge badge-<?php echo $e['Status'] === 'upcoming' ? 'warning' : ($e['Status'] === 'completed' ? 'success' : 'secondary'); ?>"><?php echo ucfirst($e['Status']); ?></span></td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>event-edit.php?id=<?php echo $e['EventId']; ?>" class="btn btn-outline-primary btn-sm">Edit</a>
                                            <a href="<?php echo BASE_URL; ?>event-delete.php?id=<?php echo $e['EventId']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this event?');">Delete</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-info">No events found</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>
