<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();

$id = trim($_GET['id'] ?? '');
if ($id === '') { header('Location: users-list.php'); exit; }

$stmt = db()->prepare("SELECT AdminId, Name, Email, Role, IsActive, IsDeleted FROM admins WHERE AdminId = ?");
$stmt->bind_param('s', $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (!$user) { header('Location: users-list.php'); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? 'staff');
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    if ($name==='') $errors[]='Name is required';
    if ($email==='') $errors[]='Email is required';

    if (!$errors) {
        $stmt2 = db()->prepare("UPDATE admins SET Name=?, Email=?, Role=?, IsActive=? WHERE AdminId = ?");
        $stmt2->bind_param('sssis', $name, $email, $role, $isActive, $id);
        if ($stmt2->execute()) { header('Location: users-list.php?msg=updated'); exit; }
        else { $errors[] = 'Failed to update user'; }
    }
}
?>
<?php require_once 'includes/head.php'; ?>
<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
<div class="xs-pd-20-10 pd-ltr-20">
        <div class="page-title d-flex align-items-center justify-content-between">
            <h4><i class="ti-user me-2"></i> Edit User</h4>
        </div>
        <div class="card"><div class="card-body">
            <?php if ($errors): ?><div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div><?php endif; ?>
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['Name']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['Email']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <?php $roles=['admin','manager','staff']; foreach ($roles as $r): ?>
                                <option value="<?= $r ?>" <?= $user['Role']===$r?'selected':''; ?>><?= ucfirst($r) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" <?= $user['IsActive'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary"><i class="ti-check me-1"></i> Update</button>
                    <a href="users-list.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
            <hr>
            <div class="d-flex gap-2">
                <?php if ((int)$user['IsDeleted'] === 0): ?>
                    <a class="btn btn-outline-danger" href="user-delete.php?id=<?= urlencode($user['AdminId']) ?>"><i class="ti-trash me-1"></i> Soft Delete</a>
                <?php else: ?>
                    <a class="btn btn-outline-success" href="user-delete.php?action=restore&id=<?= urlencode($user['AdminId']) ?>"><i class="ti-reload me-1"></i> Restore</a>
                <?php endif; ?>
            </div>
        </div></div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
