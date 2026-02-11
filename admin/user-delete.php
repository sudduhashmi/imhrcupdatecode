<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();

$id = trim($_GET['id'] ?? '');
$action = trim($_GET['action'] ?? 'delete');
if ($id === '') { header('Location: users-list.php'); exit; }

$errors = [];
$done = false;

if ($action === 'restore') {
    $stmt = db()->prepare("UPDATE admins SET IsDeleted = 0, DeletedAt = NULL WHERE AdminId = ?");
    $stmt->bind_param('s', $id);
    $done = $stmt->execute();
    $msg = $done ? 'restored' : 'failed';
    header('Location: users-list.php?msg=' . $msg);
    exit;
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = db()->prepare("UPDATE admins SET IsDeleted = 1, DeletedAt = NOW() WHERE AdminId = ?");
        $stmt->bind_param('s', $id);
        $done = $stmt->execute();
        $msg = $done ? 'deleted' : 'failed';
        header('Location: users-list.php?msg=' . $msg);
        exit;
    }
}

$stmt = db()->prepare("SELECT AdminId, Name, Email, Role, IsDeleted FROM admins WHERE AdminId = ?");
$stmt->bind_param('s', $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (!$user) { header('Location: users-list.php'); exit; }
?>
<?php require_once 'includes/head.php'; ?>
<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
<div class="xs-pd-20-10 pd-ltr-20">
        <div class="page-title d-flex align-items-center justify-content-between">
            <h4><i class="ti-trash me-2"></i> Soft Delete User</h4>
        </div>
        <div class="card"><div class="card-body">
            <p>Are you sure you want to soft delete this user?</p>
            <ul>
                <li><strong>ID:</strong> <?= htmlspecialchars($user['AdminId']) ?></li>
                <li><strong>Name:</strong> <?= htmlspecialchars($user['Name']) ?></li>
                <li><strong>Email:</strong> <?= htmlspecialchars($user['Email']) ?></li>
                <li><strong>Role:</strong> <?= htmlspecialchars($user['Role']) ?></li>
            </ul>
            <form method="post">
                <button class="btn btn-danger"><i class="ti-trash me-1"></i> Confirm Soft Delete</button>
                <a href="users-list.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div></div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
