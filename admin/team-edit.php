<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: team-list.php'); exit; }

$stmt = db()->prepare("SELECT * FROM team_members WHERE TeamMemberId = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();
if (!$member) { header('Location: team-list.php'); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $experience = intval($_POST['experience'] ?? 0);
    $status = trim($_POST['status'] ?? 'active');
    $isLeadership = isset($_POST['is_leadership']) ? 1 : 0;

    if ($name==='') $errors[]='Name is required';

    if (!$errors) {
        $updatedBy = currentUserId();
        $stmt2 = db()->prepare("UPDATE team_members SET Name=?, Position=?, Department=?, Email=?, Phone=?, Bio=?, Specialization=?, Experience=?, Status=?, IsLeadership=?, UpdatedBy=? WHERE TeamMemberId = ?");
        $stmt2->bind_param('sssssssssssi', $name, $position, $department, $email, $phone, $bio, $specialization, $experience, $status, $isLeadership, $updatedBy, $id);
        if ($stmt2->execute()) { header('Location: team-list.php?msg=updated'); exit; }
        else { $errors[] = 'Failed to update team member'; }
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
            <h4><i class="ti-user me-2"></i> Edit Team Member</h4>
        </div>
        <div class="card"><div class="card-body">
            <?php if ($errors): ?><div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div><?php endif; ?>
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($member['Name']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Position</label>
                        <input type="text" name="position" class="form-control" value="<?= htmlspecialchars($member['Position']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" value="<?= htmlspecialchars($member['Department']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($member['Email']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($member['Phone']) ?>">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="5"><?= htmlspecialchars($member['Bio']) ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Specialization</label>
                        <input type="text" name="specialization" class="form-control" value="<?= htmlspecialchars($member['Specialization']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Experience (years)</label>
                        <input type="number" name="experience" class="form-control" value="<?= htmlspecialchars($member['Experience']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" <?= $member['Status']==='active'?'selected':''; ?>>Active</option>
                            <option value="inactive" <?= $member['Status']==='inactive'?'selected':''; ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_leadership" id="isLeadership" <?= $member['IsLeadership'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="isLeadership">Leadership Team</label>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary"><i class="ti-check me-1"></i> Update</button>
                    <a href="team-list.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div></div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
