<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: events-manage.php'); exit; }

$stmt = db()->prepare("SELECT * FROM events WHERE EventId = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
if (!$event) { header('Location: events-manage.php'); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $eventType = trim($_POST['event_type'] ?? 'conference');
    $startDate = trim($_POST['start_date'] ?? '');
    $endDate = trim($_POST['end_date'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $status = trim($_POST['status'] ?? 'upcoming');

    if ($title==='') $errors[]='Title is required';
    if ($startDate==='') $errors[]='Start date is required';

    if (!$errors) {
        $updatedBy = currentUserId();
        $stmt2 = db()->prepare("UPDATE events SET Title=?, Description=?, EventType=?, StartDate=?, EndDate=?, Location=?, Status=?, UpdatedBy=? WHERE EventId = ?");
        $stmt2->bind_param('ssssssssi', $title, $description, $eventType, $startDate, $endDate, $location, $status, $updatedBy, $id);
        if ($stmt2->execute()) { header('Location: events-manage.php?msg=updated'); exit; }
        else { $errors[] = 'Failed to update event'; }
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
            <h4><i class="ti-calendar me-2"></i> Edit Event</h4>
        </div>
        <div class="card"><div class="card-body">
            <?php if ($errors): ?><div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div><?php endif; ?>
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($event['Title']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Type</label>
                        <select name="event_type" class="form-select">
                            <?php $types = ['conference','workshop','seminar']; foreach ($types as $t): ?>
                                <option value="<?= $t ?>" <?= $event['EventType']===$t?'selected':''; ?>><?= ucfirst($t) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="datetime-local" name="start_date" class="form-control" value="<?= str_replace(' ', 'T', htmlspecialchars($event['StartDate'])) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Date</label>
                        <input type="datetime-local" name="end_date" class="form-control" value="<?= str_replace(' ', 'T', htmlspecialchars($event['EndDate'])) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($event['Location']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <?php $statuses = ['upcoming','ongoing','completed','cancelled']; foreach ($statuses as $s): ?>
                                <option value="<?= $s ?>" <?= $event['Status']===$s?'selected':''; ?>><?= ucfirst($s) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="6"><?= htmlspecialchars($event['Description']) ?></textarea>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary"><i class="ti-check me-1"></i> Update Event</button>
                    <a href="events-manage.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div></div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
