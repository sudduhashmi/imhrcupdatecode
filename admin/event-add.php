<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();

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
        $createdBy = currentUserId();
        $stmt = db()->prepare("INSERT INTO events (Title, Description, EventType, StartDate, EndDate, Location, Status, CreatedBy) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssss', $title, $description, $eventType, $startDate, $endDate, $location, $status, $createdBy);
        if ($stmt->execute()) { header('Location: events-manage.php?msg=created'); exit; }
        else { $errors[] = 'Failed to create event'; }
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
            <h4><i class="ti-calendar me-2"></i> Add Event</h4>
        </div>
        <div class="card"><div class="card-body">
            <?php if ($errors): ?><div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div><?php endif; ?>
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Type</label>
                        <select name="event_type" class="form-select">
                            <option value="conference">Conference</option>
                            <option value="workshop">Workshop</option>
                            <option value="seminar">Seminar</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="datetime-local" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Date</label>
                        <input type="datetime-local" name="end_date" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="upcoming">Upcoming</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="6"></textarea>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary"><i class="ti-check me-1"></i> Save Event</button>
                    <a href="events-manage.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div></div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
