<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();
$pageTitle = 'Roles Management - ' . APP_NAME;

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_id'], $_POST['role'])) {
	$adminId = trim($_POST['admin_id']);
	$role = trim($_POST['role']);
	if (!in_array($role, ['admin','manager','staff'])) {
		$errors[] = 'Invalid role selected';
	} else {
		$stmt = db()->prepare("UPDATE admins SET Role = ?, UpdatedAt = NOW() WHERE AdminId = ?");
		$stmt->bind_param('ss', $role, $adminId);
		$success = $stmt->execute();
		if (!$success) { $errors[] = 'Failed to update role'; }
	}
}

$rows = [];
$res = db()->query("SELECT AdminId, Name, Email, Role, IsActive, IsDeleted FROM admins ORDER BY Name ASC");
if ($res) { $rows = $res->fetch_all(MYSQLI_ASSOC); }
?>
<?php require_once 'includes/head.php'; ?>
<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
<div class="xs-pd-20-10 pd-ltr-20">
		<div class="page-header mb-4 mt-3 d-flex justify-content-between align-items-center">
			<h1 class="page-title mb-0"><i class="ti-lock"></i> Roles Management</h1>
		</div>
		<div class="card"><div class="card-body">
			<?php if ($success): ?>
				<div class="alert alert-success">Role updated successfully.</div>
			<?php endif; ?>
			<?php if ($errors): ?>
				<div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
			<?php endif; ?>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Current Role</th>
							<th>Status</th>
							<th>Change Role</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($rows as $r): ?>
							<tr>
								<td><?= htmlspecialchars($r['Name']) ?></td>
								<td><?= htmlspecialchars($r['Email']) ?></td>
								<td><span class="badge bg-info text-dark"><?= htmlspecialchars(ucfirst($r['Role'])) ?></span></td>
								<td>
									<?php if ((int)$r['IsDeleted'] === 1): ?>
										<span class="badge bg-danger">Deleted</span>
									<?php else: ?>
										<span class="badge bg-<?= (int)$r['IsActive'] ? 'success' : 'secondary' ?>"><?= (int)$r['IsActive'] ? 'Active' : 'Inactive' ?></span>
									<?php endif; ?>
								</td>
								<td>
									<?php if ((int)$r['IsDeleted'] === 0): ?>
									<form method="post" class="d-flex gap-2 align-items-center">
										<input type="hidden" name="admin_id" value="<?= htmlspecialchars($r['AdminId']) ?>">
										<select name="role" class="form-select form-select-sm" style="max-width: 160px;">
											<?php foreach (['admin','manager','staff'] as $role): ?>
												<option value="<?= $role ?>" <?= $r['Role'] === $role ? 'selected' : '' ?>><?= ucfirst($role) ?></option>
											<?php endforeach; ?>
										</select>
										<button class="btn btn-sm btn-primary"><i class="ti-check"></i> Update</button>
									</form>
									<?php else: ?>
										<span class="text-muted">Restore user to change role</span>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
						<?php if (!$rows): ?>
							<tr><td colspan="5" class="text-center text-muted">No users found.</td></tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div></div>
	</div>
</div>
</div>
<?php require_once 'includes/footer.php'; ?>
</body>
</html>
