<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();
$pageTitle = 'Users - ' . APP_NAME;
?>
<?php require_once 'includes/head.php'; ?>
<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
<div class="xs-pd-20-10 pd-ltr-20">
	<div class="page-header mb-4 mt-3 d-flex justify-content-between align-items-center">
		<h1 class="page-title mb-0"><i class="ti-user"></i> Users</h1>
		<div>
			<a href="user-add.php" class="btn btn-primary"><i class="ti-plus me-1"></i> Add User</a>
			<?php $showDeleted = isset($_GET['show_deleted']) ? 1 : 0; ?>
			<a href="users-list.php?show_deleted=<?= $showDeleted?0:1 ?>" class="btn btn-outline-secondary ms-2">
				<?= $showDeleted? 'Hide Deleted' : 'Show Deleted' ?>
			</a>
		</div>
	</div>

	<?php
	$showDeleted = isset($_GET['show_deleted']) ? 1 : 0;
	$sql = $showDeleted
		? "SELECT AdminId, Name, Email, Role, IsActive, IsDeleted FROM admins ORDER BY Name ASC"
		: "SELECT AdminId, Name, Email, Role, IsActive, IsDeleted FROM admins WHERE IsDeleted = 0 ORDER BY Name ASC";
	$res = db()->query($sql);
	$rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
	?>

	<div class="card">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Role</th>
							<th>Status</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($rows as $r): ?>
							<tr>
								<td><?= htmlspecialchars($r['Name']) ?></td>
								<td><?= htmlspecialchars($r['Email']) ?></td>
								<td><?= htmlspecialchars($r['Role']) ?></td>
								<td>
									<?php $active = isset($r['IsActive']) ? (int)$r['IsActive'] : 1; ?>
									<span class="badge bg-<?= $active? 'success':'secondary' ?>"><?= $active? 'Active':'Inactive' ?></span>
									<?php if ($showDeleted && isset($r['IsDeleted']) && (int)$r['IsDeleted']===1): ?>
										<span class="badge bg-danger ms-1">Deleted</span>
									<?php endif; ?>
								</td>
								<td>
									<a class="btn btn-sm btn-outline-secondary" href="user-edit.php?id=<?= urlencode($r['AdminId']) ?>">Edit</a>
									<?php if (!$showDeleted): ?>
										<a class="btn btn-sm btn-outline-danger" href="user-delete.php?id=<?= urlencode($r['AdminId']) ?>">Delete</a>
									<?php else: ?>
										<a class="btn btn-sm btn-outline-success" href="user-delete.php?action=restore&id=<?= urlencode($r['AdminId']) ?>">Restore</a>
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
		</div>
	</div>
</div>
</div>

<?php require_once 'includes/footer.php'; ?>
