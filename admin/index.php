<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/cms.php';

// Redirect to dashboard if already logged in, otherwise show login
requireLogin();
// Ensure CMS pages table exists
cmsEnsurePagesTable();

// If user is logged in, show dashboard
$admin = getCurrentAdmin();
$adminRole = $_SESSION['admin_role'] ?? '';

// Get comprehensive dashboard statistics
$stats = [];
$internshipStatusMessage = '';
$internshipStatusType = 'info';

$internshipPerPage = 10;
$currentInternshipPage = isset($_GET['internship_page']) ? max(1, (int)$_GET['internship_page']) : 1;
$internshipOffset = ($currentInternshipPage - 1) * $internshipPerPage;
$internshipTotalCount = 0;
$internshipTotalPages = 1;

// Handle internship status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_internship_status'])) {
	if ($adminRole === 'admin' || $adminRole === 'manager') {
		$applicationId = (int)($_POST['application_id'] ?? 0);
		$newStatus = $_POST['new_status'] ?? '';
		$allowedStatuses = ['pending', 'approved', 'rejected'];

		if ($applicationId > 0 && in_array($newStatus, $allowedStatuses, true)) {
			try {
				$stmt = db()->prepare("UPDATE internship_applications SET status = ? WHERE id = ?");
				if ($stmt) {
					$stmt->bind_param('si', $newStatus, $applicationId);
					$stmt->execute();
					$stmt->close();
					$internshipStatusMessage = 'Status updated successfully.';
					$internshipStatusType = 'success';
				} else {
					$internshipStatusMessage = 'Unable to prepare update statement.';
					$internshipStatusType = 'danger';
				}
			} catch (Exception $e) {
				$internshipStatusMessage = 'Error updating status: ' . htmlspecialchars($e->getMessage());
				$internshipStatusType = 'danger';
			}
		} else {
			$internshipStatusMessage = 'Invalid request.';
			$internshipStatusType = 'warning';
		}
	} else {
		$internshipStatusMessage = 'You do not have permission to update statuses.';
		$internshipStatusType = 'warning';
	}
}

if ($adminRole === 'admin' || $adminRole === 'manager' || $adminRole === 'staff') {
    // Students count
    $studentCount = getRow("SELECT COUNT(*) as count FROM studentlogin WHERE IsActive = 1");
    $stats['students'] = $studentCount['count'] ?? 0;
    $stats['total_students'] = getRow("SELECT COUNT(*) as count FROM studentlogin")['count'] ?? 0;
    
    // Articles
    try {
        $articlesCount = getRow("SELECT COUNT(*) as count FROM articles WHERE Status = 'published'");
        $stats['articles'] = $articlesCount['count'] ?? 0;
        $stats['draft_articles'] = getRow("SELECT COUNT(*) as count FROM articles WHERE Status = 'draft'")['count'] ?? 0;
    } catch (Exception $e) {
        $stats['articles'] = 0;
        $stats['draft_articles'] = 0;
    }
    
    // Team members
    try {
        $teamCount = getRow("SELECT COUNT(*) as count FROM team_members WHERE Status = 'active'");
        $stats['team'] = $teamCount['count'] ?? 0;
    } catch (Exception $e) {
        $stats['team'] = 0;
    }
    
    // Appointments
    try {
        $stats['appointments'] = getRow("SELECT COUNT(*) as count FROM appointments")['count'] ?? 0;
        $stats['pending_appointments'] = getRow("SELECT COUNT(*) as count FROM appointments WHERE Status = 'pending'")['count'] ?? 0;
        $stats['confirmed_appointments'] = getRow("SELECT COUNT(*) as count FROM appointments WHERE Status = 'confirmed'")['count'] ?? 0;
    } catch (Exception $e) {
        $stats['appointments'] = 0;
        $stats['pending_appointments'] = 0;
        $stats['confirmed_appointments'] = 0;
    }
    
    // Events
    try {
        $stats['events'] = getRow("SELECT COUNT(*) as count FROM events")['count'] ?? 0;
        $stats['upcoming_events'] = getRow("SELECT COUNT(*) as count FROM events WHERE Status = 'upcoming'")['count'] ?? 0;
    } catch (Exception $e) {
        $stats['events'] = 0;
        $stats['upcoming_events'] = 0;
    }
    
    // Media
    try {
        $stats['media'] = getRow("SELECT COUNT(*) as count FROM media WHERE Status = 'active'")['count'] ?? 0;
    } catch (Exception $e) {
        $stats['media'] = 0;
    }
    
    // Pages
    try {
        $stats['pages'] = getRow("SELECT COUNT(*) as count FROM pages WHERE Status = 'published'")['count'] ?? 0;
    } catch (Exception $e) {
        $stats['pages'] = 0;
    }
    
    // Courses
    try {
        $stats['courses'] = getRow("SELECT COUNT(*) as count FROM courses")['count'] ?? 0;
    } catch (Exception $e) {
        $stats['courses'] = 0;
    }
    
    // Academic Programs
    try {
        $stats['programs'] = getRow("SELECT COUNT(*) as count FROM academic_programs WHERE Status = 'active'")['count'] ?? 0;
    } catch (Exception $e) {
        $stats['programs'] = 0;
    }
    
    // Clinical Services
    try {
        $stats['services'] = getRow("SELECT COUNT(*) as count FROM clinical_services WHERE Status = 'active'")['count'] ?? 0;
    } catch (Exception $e) {
        $stats['services'] = 0;
    }
    
    // Experts
    try {
        $stats['experts'] = getRow("SELECT COUNT(*) as count FROM experts WHERE IsActive = 1")['count'] ?? 0;
    } catch (Exception $e) {
        $stats['experts'] = 0;
    }
    
    // Internship Applications
	try {
		$stats['internships'] = getRow("SELECT COUNT(*) as count FROM internship_applications")['count'] ?? 0;
		$stats['pending_internships'] = getRow("SELECT COUNT(*) as count FROM internship_applications WHERE status = 'pending'")['count'] ?? 0;
		$internshipTotalCount = $stats['internships'];
		$internshipTotalPages = $internshipTotalCount > 0 ? (int)ceil($internshipTotalCount / $internshipPerPage) : 1;
	} catch (Exception $e) {
		$stats['internships'] = 0;
		$stats['pending_internships'] = 0;
		$internshipTotalCount = 0;
		$internshipTotalPages = 1;
	}
    
    // Inquiries
    try {
        $stats['inquiries'] = getRow("SELECT COUNT(*) as count FROM inquiries")['count'] ?? 0;
        $stats['new_inquiries'] = getRow("SELECT COUNT(*) as count FROM inquiries WHERE Status = 'new'")['count'] ?? 0;
    } catch (Exception $e) {
        $stats['inquiries'] = 0;
        $stats['new_inquiries'] = 0;
    }
    
    // Research Projects
    try {
        $stats['research_projects'] = getRow("SELECT COUNT(*) as count FROM research_projects WHERE Status = 'ongoing'")['count'] ?? 0;
    } catch (Exception $e) {
        $stats['research_projects'] = 0;
    }
    
    // Publications
    try {
        $stats['publications'] = getRow("SELECT COUNT(*) as count FROM publications WHERE Status = 'published'")['count'] ?? 0;
    } catch (Exception $e) {
        $stats['publications'] = 0;
    }
}

// Admin-only statistics
if ($adminRole === 'admin') {
    $adminsCount = getRow("SELECT COUNT(*) as count FROM admins WHERE IsActive = 1");
    $stats['admins'] = $adminsCount['count'] ?? 0;
}

// Get recent activities
$recentArticles = [];
$recentEvents = [];
$recentInternships = [];
$recentAppointments = [];
$internshipTableData = [];

try {
	$recentArticles = getRows("SELECT id, Title, CreatedAt FROM articles ORDER BY CreatedAt DESC LIMIT 5");
} catch (Exception $e) {
	$recentArticles = [];
}

try {
	$recentEvents = getRows("SELECT id, Title, StartDate FROM events ORDER BY CreatedAt DESC LIMIT 5");
} catch (Exception $e) {
	$recentEvents = [];
}

try {
	$recentInternships = getRows("SELECT id, fullname, email, created_at FROM internship_applications ORDER BY created_at DESC LIMIT 5");
} catch (Exception $e) {
	$recentInternships = [];
}

try {
	$recentAppointments = getRows("SELECT id, PatientName, AppointmentDate, Status FROM appointments ORDER BY CreatedAt DESC LIMIT 5");
} catch (Exception $e) {
	$recentAppointments = [];
}

try {
	$internshipTableData = getRows("SELECT id, fullname, email, phone, program, start_date, status, created_at FROM internship_applications ORDER BY created_at DESC LIMIT {$internshipPerPage} OFFSET {$internshipOffset}");
} catch (Exception $e) {
	$internshipTableData = [];
}

$pageTitle = 'Dashboard - ' . APP_NAME;
?>
<?php require_once 'includes/head.php'; ?>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>

		<!-- <div class="main-container">
							<a href="sitemap.html" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-diagram-3"></span
								><span class="mtext">Sitemap</span>
							</a>
						</li>
						<li>
							<a href="chat.html" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-chat-right-dots"></span
								><span class="mtext">Chat</span>
							</a>
						</li>
						<li>
							<a href="invoice.html" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-receipt-cutoff"></span
								><span class="mtext">Invoice</span>
							</a>
						</li>
						<li>
							<div class="dropdown-divider"></div>
						</li>
						<li>
							<div class="sidebar-small-cap">Extra</div>
						</li>
						<li>
							<a href="javascript:;" class="dropdown-toggle">
								<span class="micon bi bi-file-pdf"></span
								><span class="mtext">Documentation</span>
							</a>
							<ul class="submenu">
								<li><a href="introduction.html">Introduction</a></li>
								<li><a href="getting-started.html">Getting Started</a></li>
								<li><a href="color-settings.html">Color Settings</a></li>
								<li>
									<a href="third-party-plugins.html">Third Party Plugins</a>
								</li>
							</ul>
						</li>
						<li>
							<a
								href="https://dropways.github.io/deskapp-free-single-page-website-template/"
								target="_blank"
								class="dropdown-toggle no-arrow"
							>
								<span class="micon bi bi-layout-text-window-reverse"></span>
								<span class="mtext"
									>Landing Page
									<img src="vendors/images/coming-soon.png" alt="" width="25"
								/></span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div> -->
		<div class="mobile-menu-overlay"></div>

		<div class="main-container">
			<div class="xs-pd-20-10 pd-ltr-20">
				<?php if (!empty($internshipStatusMessage)): ?>
				<div class="alert alert-<?php echo $internshipStatusType; ?> alert-dismissible fade show" role="alert">
					<?php echo $internshipStatusMessage; ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<?php endif; ?>
				<!-- Welcome Section -->
				<div class="page-header mb-30">
					<div class="row align-items-center">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Welcome, <?php echo htmlspecialchars($admin['Name']); ?>!</h4>
								<p class="text-muted">Here's what's happening with your mental health center today.</p>
							</div>
						</div>
						<div class="col-md-6 col-sm-12 text-right">
							<div class="dropdown">
								<a class="btn btn-primary" href="#" role="button">
									<i class="icon-copy bi bi-calendar-event"></i> <?php echo date('F d, Y'); ?>
								</a>
							</div>
						</div>
					</div>
				</div>

				<!-- Main Statistics Row -->
				<div class="row pb-10">
					<!-- Students Card -->
					<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
						<a href="users-list.php" class="card-box height-100-p widget-style3 d-block text-reset">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark"><?php echo number_format($stats['students']); ?></div>
									<div class="font-14 text-secondary weight-500">Active Students</div>
									<div class="font-12 text-muted mt-1">
										<span class="text-success">+<?php echo $stats['total_students'] - $stats['students']; ?></span> Inactive
									</div>
								</div>
								<div class="widget-icon">
									<div class="icon" data-color="#00eccf">
										<i class="icon-copy bi bi-people-fill"></i>
									</div>
								</div>
							</div>
						</a>
					</div>

					<!-- Articles Card -->
					<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
						<a href="articles-manage.php" class="card-box height-100-p widget-style3 d-block text-reset">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark"><?php echo number_format($stats['articles']); ?></div>
									<div class="font-14 text-secondary weight-500">Published Articles</div>
									<div class="font-12 text-muted mt-1">
										<span class="text-warning"><?php echo $stats['draft_articles']; ?></span> Drafts
									</div>
								</div>
								<div class="widget-icon">
									<div class="icon" data-color="#ff5b5b">
										<i class="icon-copy bi bi-file-earmark-text"></i>
									</div>
								</div>
							</div>
						</a>
					</div>

					<!-- Appointments Card -->
					<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
						<a href="appointments-manage.php" class="card-box height-100-p widget-style3 d-block text-reset">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark"><?php echo number_format($stats['appointments']); ?></div>
									<div class="font-14 text-secondary weight-500">Total Appointments</div>
									<div class="font-12 text-muted mt-1">
										<span class="text-danger"><?php echo $stats['pending_appointments']; ?></span> Pending
									</div>
								</div>
								<div class="widget-icon">
									<div class="icon" data-color="#09cc06">
										<i class="icon-copy bi bi-calendar-check"></i>
									</div>
								</div>
							</div>
						</a>
					</div>

					<!-- Experts/Team Card -->
					<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
						<a href="team-list.php" class="card-box height-100-p widget-style3 d-block text-reset">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark"><?php echo number_format($stats['experts']); ?></div>
									<div class="font-14 text-secondary weight-500">Clinical Experts</div>
									<div class="font-12 text-muted mt-1">
										<span class="text-info"><?php echo $stats['team']; ?></span> Team Members
									</div>
								</div>
								<div class="widget-icon">
									<div class="icon" data-color="#265ed7">
										<i class="icon-copy bi bi-person-badge"></i>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>

				<!-- Secondary Statistics Row -->
				<div class="row pb-10">
					<!-- Events Card -->
					<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-20">
						<a href="events-manage.php" class="card-box pd-20 text-center d-block text-reset">
							<div class="h1 text-blue mb-2"><i class="icon-copy bi bi-calendar-event"></i></div>
							<div class="weight-600 font-18"><?php echo number_format($stats['events']); ?></div>
							<div class="font-14 text-secondary">Events</div>
							<div class="font-12 text-muted mt-1"><?php echo $stats['upcoming_events']; ?> Upcoming</div>
						</a>
					</div>

					<!-- Programs Card -->
					<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-20">
						<a href="academic-programs.php" class="card-box pd-20 text-center d-block text-reset">
							<div class="h1 text-orange mb-2"><i class="icon-copy bi bi-journal-bookmark"></i></div>
							<div class="weight-600 font-18"><?php echo number_format($stats['programs']); ?></div>
							<div class="font-14 text-secondary">Programs</div>
							<div class="font-12 text-muted mt-1">Active</div>
						</a>
					</div>

					<!-- Services Card -->
					<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-20">
						<a href="clinical-services.php" class="card-box pd-20 text-center d-block text-reset">
							<div class="h1 text-success mb-2"><i class="icon-copy bi bi-heart-pulse"></i></div>
							<div class="weight-600 font-18"><?php echo number_format($stats['services']); ?></div>
							<div class="font-14 text-secondary">Services</div>
							<div class="font-12 text-muted mt-1">Clinical</div>
						</a>
					</div>

					<!-- Internships Card -->
					<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-20">
						<a href="#internship-table" class="card-box pd-20 text-center d-block text-reset">
							<div class="h1 text-purple mb-2"><i class="icon-copy bi bi-briefcase"></i></div>
							<div class="weight-600 font-18"><?php echo number_format($stats['internships']); ?></div>
							<div class="font-14 text-secondary">Internships</div>
							<div class="font-12 text-muted mt-1"><?php echo $stats['pending_internships']; ?> Pending</div>
						</a>
					</div>

					<!-- Media Card -->
					<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-20">
						<a href="media-gallery.php" class="card-box pd-20 text-center d-block text-reset">
							<div class="h1 text-pink mb-2"><i class="icon-copy bi bi-image"></i></div>
							<div class="weight-600 font-18"><?php echo number_format($stats['media']); ?></div>
							<div class="font-14 text-secondary">Media Files</div>
							<div class="font-12 text-muted mt-1">Active</div>
						</a>
					</div>

					<!-- Research Card -->
					<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-20">
						<a href="research-publications.php" class="card-box pd-20 text-center d-block text-reset">
							<div class="h1 text-cyan mb-2"><i class="icon-copy bi bi-search"></i></div>
							<div class="weight-600 font-18"><?php echo number_format($stats['research_projects']); ?></div>
							<div class="font-14 text-secondary">Research</div>
							<div class="font-12 text-muted mt-1">Ongoing</div>
						</a>
					</div>
				</div>

				<!-- Activity Dashboard -->
				<div class="row pb-10">
					<!-- Recent Articles -->
					<div class="col-lg-6 col-md-12 mb-20">
						<div class="card-box height-100-p pd-20">
							<div class="d-flex justify-content-between align-items-center pb-20">
								<h5 class="mb-0">Recent Articles</h5>
								<a href="articles-manage.php" class="text-primary font-14">View All <i class="icon-copy bi bi-arrow-right"></i></a>
							</div>
							
							<?php if (!empty($recentArticles)): ?>
							<div class="list-group">
								<?php foreach ($recentArticles as $article): ?>
								<div class="list-group-item d-flex justify-content-between align-items-center">
									<div>
										<i class="icon-copy bi bi-file-text text-primary mr-2"></i>
										<a href="article-edit.php?id=<?php echo (int)$article['id']; ?>" class="weight-500 text-reset"><?php echo htmlspecialchars($article['Title']); ?></a>
									</div>
									<small class="text-muted"><?php echo date('M d, Y', strtotime($article['CreatedAt'])); ?></small>
								</div>
								<?php endforeach; ?>
							</div>
							<?php else: ?>
							<div class="text-center text-muted py-4">
								<i class="icon-copy bi bi-inbox" style="font-size: 48px;"></i>
								<p class="mt-2">No articles yet. <a href="article-add.php">Create one</a></p>
							</div>
							<?php endif; ?>
						</div>
					</div>

					<!-- Recent Events -->
					<div class="col-lg-6 col-md-12 mb-20">
						<div class="card-box height-100-p pd-20">
							<div class="d-flex justify-content-between align-items-center pb-20">
								<h5 class="mb-0">Recent Events</h5>
								<a href="events-manage.php" class="text-primary font-14">View All <i class="icon-copy bi bi-arrow-right"></i></a>
							</div>
							
							<?php if (!empty($recentEvents)): ?>
							<div class="list-group">
								<?php foreach ($recentEvents as $event): ?>
								<div class="list-group-item d-flex justify-content-between align-items-center">
									<div>
										<i class="icon-copy bi bi-calendar-event text-success mr-2"></i>
										<a href="event-edit.php?id=<?php echo (int)$event['id']; ?>" class="weight-500 text-reset"><?php echo htmlspecialchars($event['Title']); ?></a>
									</div>
									<small class="text-muted"><?php echo date('M d, Y', strtotime($event['StartDate'])); ?></small>
								</div>
								<?php endforeach; ?>
							</div>
							<?php else: ?>
							<div class="text-center text-muted py-4">
								<i class="icon-copy bi bi-calendar-x" style="font-size: 48px;"></i>
								<p class="mt-2">No events yet. <a href="event-add.php">Schedule one</a></p>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<!-- More Activity -->
				<div class="row pb-10">
					<!-- Recent Appointments -->
					<div class="col-lg-6 col-md-12 mb-20">
						<div class="card-box height-100-p pd-20">
							<div class="d-flex justify-content-between align-items-center pb-20">
								<h5 class="mb-0">Recent Appointments</h5>
								<a href="appointments-manage.php" class="text-primary font-14">View All <i class="icon-copy bi bi-arrow-right"></i></a>
							</div>
							
							<?php if (!empty($recentAppointments)): ?>
							<div class="list-group">
								<?php foreach ($recentAppointments as $appointment): ?>
								<div class="list-group-item d-flex justify-content-between align-items-center">
									<div>
										<i class="icon-copy bi bi-person-circle text-info mr-2"></i>
										<a href="appointments-manage.php#appt-<?php echo (int)$appointment['id']; ?>" class="weight-500 text-reset"><?php echo htmlspecialchars($appointment['PatientName']); ?></a>
										<span class="badge badge-<?php echo $appointment['Status'] === 'confirmed' ? 'success' : 'warning'; ?> ml-2">
											<?php echo ucfirst($appointment['Status']); ?>
										</span>
									</div>
									<small class="text-muted"><?php echo date('M d, Y', strtotime($appointment['AppointmentDate'])); ?></small>
								</div>
								<?php endforeach; ?>
							</div>
							<?php else: ?>
							<div class="text-center text-muted py-4">
								<i class="icon-copy bi bi-calendar-x" style="font-size: 48px;"></i>
								<p class="mt-2">No appointments yet.</p>
							</div>
							<?php endif; ?>
						</div>
					</div>

					<!-- Recent Internship Applications -->
					<div class="col-lg-6 col-md-12 mb-20">
						<div class="card-box height-100-p pd-20">
							<div class="d-flex justify-content-between align-items-center pb-20">
								<h5 class="mb-0">Recent Internship Applications</h5>
								<span class="badge badge-primary"><?php echo $stats['pending_internships']; ?> Pending</span>
							</div>
							
							<?php if (!empty($recentInternships)): ?>
							<div class="list-group">
								<?php foreach ($recentInternships as $internship): ?>
								<div class="list-group-item d-flex justify-content-between align-items-center">
									<div>
										<i class="icon-copy bi bi-person-plus text-purple mr-2"></i>
										<a href="#internship-table" class="weight-500 text-reset"><?php echo htmlspecialchars($internship['fullname']); ?></a>
										<br>
										<small class="text-muted"><?php echo htmlspecialchars($internship['email']); ?></small>
									</div>
									<small class="text-muted"><?php echo date('M d, Y', strtotime($internship['created_at'])); ?></small>
								</div>
								<?php endforeach; ?>
							</div>
							<?php else: ?>
							<div class="text-center text-muted py-4">
								<i class="icon-copy bi bi-inbox" style="font-size: 48px;"></i>
								<p class="mt-2">No internship applications yet.</p>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<!-- Internship Applications Table -->
				<div class="row pb-10" id="internship-table">
					<div class="col-12">
						<div class="card-box pd-20">
							<div class="d-flex justify-content-between align-items-center pb-10">
								<h5 class="mb-0">Internship Applications</h5>
								<span class="badge badge-primary"><?php echo number_format($stats['internships']); ?> Total</span>
							</div>

							<?php if (!empty($internshipTableData)): ?>
							<div class="table-responsive">
								<table class="table table-striped table-hover">
									<thead>
										<tr>
											<th>Applicant</th>
											<th>Email</th>
											<th>Phone</th>
											<th>Program</th>
											<th>Start Date</th>
											<th>Status</th>
											<?php if ($adminRole === 'admin' || $adminRole === 'manager'): ?>
											<th class="text-right">Actions</th>
											<?php endif; ?>
											<th>Applied On</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($internshipTableData as $row): ?>
										<tr id="intern-<?php echo (int)$row['id']; ?>">
											<td class="weight-500 text-dark"><?php echo htmlspecialchars($row['fullname']); ?></td>
											<td><a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="text-primary"><?php echo htmlspecialchars($row['email']); ?></a></td>
											<td><?php echo htmlspecialchars($row['phone']); ?></td>
											<td><?php echo htmlspecialchars($row['program']); ?></td>
											<td><?php echo date('M d, Y', strtotime($row['start_date'])); ?></td>
											<td>
												<?php
												$status = $row['status'] ?? 'pending';
												$badgeClass = 'warning';
												if ($status === 'approved') {
												    $badgeClass = 'success';
												} elseif ($status === 'rejected') {
												    $badgeClass = 'danger';
												}
												?>
												<span class="badge badge-<?php echo $badgeClass; ?> text-capitalize"><?php echo ucfirst($status); ?></span>
											</td>
											<?php if ($adminRole === 'admin' || $adminRole === 'manager'): ?>
											<td class="text-right">
												<form method="post" class="form-inline justify-content-end">
													<input type="hidden" name="update_internship_status" value="1" />
													<input type="hidden" name="application_id" value="<?php echo (int)$row['id']; ?>" />
													<select name="new_status" class="form-control form-control-sm" aria-label="Select status" onchange="this.form.submit()">
														<option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending / Process</option>
														<option value="approved" <?php echo $status === 'approved' ? 'selected' : ''; ?>>Approved</option>
														<option value="rejected" <?php echo $status === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
													</select>
												</form>
											</td>
											<?php endif; ?>
											<td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
							<?php if ($internshipTotalPages > 1): ?>
							<nav aria-label="Internship pagination">
								<ul class="pagination justify-content-end mt-3 mb-0">
									<li class="page-item <?php echo $currentInternshipPage <= 1 ? 'disabled' : ''; ?>">
										<a class="page-link" href="?internship_page=<?php echo max(1, $currentInternshipPage - 1); ?>#internship-table">Previous</a>
									</li>
									<?php for ($p = 1; $p <= $internshipTotalPages; $p++): ?>
									<li class="page-item <?php echo $p === $currentInternshipPage ? 'active' : ''; ?>">
										<a class="page-link" href="?internship_page=<?php echo $p; ?>#internship-table"><?php echo $p; ?></a>
									</li>
									<?php endfor; ?>
									<li class="page-item <?php echo $currentInternshipPage >= $internshipTotalPages ? 'disabled' : ''; ?>">
										<a class="page-link" href="?internship_page=<?php echo min($internshipTotalPages, $currentInternshipPage + 1); ?>#internship-table">Next</a>
									</li>
								</ul>
							</nav>
							<?php endif; ?>
							<?php else: ?>
							<div class="text-center text-muted py-4">
								<i class="icon-copy bi bi-briefcase" style="font-size: 48px;"></i>
								<p class="mt-2 mb-0">No internship applications yet. Share your internship form to start receiving applications.</p>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<!-- Quick Actions -->
				<div class="row pb-10">
					<div class="col-12">
						<div class="card-box pd-20">
							<h5 class="mb-20">Quick Actions</h5>
							<div class="row">
								<div class="col-lg-2 col-md-3 col-sm-6 mb-10">
									<a href="article-add.php" class="btn btn-block btn-outline-primary">
										<i class="icon-copy bi bi-plus-circle"></i> New Article
									</a>
								</div>
								<div class="col-lg-2 col-md-3 col-sm-6 mb-10">
									<a href="event-add.php" class="btn btn-block btn-outline-success">
										<i class="icon-copy bi bi-calendar-plus"></i> New Event
									</a>
								</div>
								<div class="col-lg-2 col-md-3 col-sm-6 mb-10">
									<a href="team-add.php" class="btn btn-block btn-outline-info">
										<i class="icon-copy bi bi-person-plus"></i> Add Team Member
									</a>
								</div>
								<div class="col-lg-2 col-md-3 col-sm-6 mb-10">
									<a href="media-upload.php" class="btn btn-block btn-outline-warning">
										<i class="icon-copy bi bi-cloud-upload"></i> Upload Media
									</a>
								</div>
								<div class="col-lg-2 col-md-3 col-sm-6 mb-10">
									<a href="page-add.php" class="btn btn-block btn-outline-secondary">
										<i class="icon-copy bi bi-file-earmark-plus"></i> New Page
									</a>
								</div>
								<?php if ($adminRole === 'admin'): ?>
								<div class="col-lg-2 col-md-3 col-sm-6 mb-10">
									<a href="user-add.php" class="btn btn-block btn-outline-dark">
										<i class="icon-copy bi bi-person-badge"></i> Add User
									</a>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>

				<!-- System Information -->
				<div class="row pb-20">
					<div class="col-lg-4 col-md-6 mb-20">
						<div class="card-box pd-20">
							<h5 class="mb-20"><i class="icon-copy bi bi-database"></i> Database Overview</h5>
							<ul class="list-group">
								<li class="list-group-item d-flex justify-content-between">
									<span>Courses</span>
									<span class="badge badge-primary"><?php echo number_format($stats['courses']); ?></span>
								</li>
								<li class="list-group-item d-flex justify-content-between">
									<span>Publications</span>
									<span class="badge badge-info"><?php echo number_format($stats['publications']); ?></span>
								</li>
								<li class="list-group-item d-flex justify-content-between">
									<span>Pages</span>
									<span class="badge badge-secondary"><?php echo number_format($stats['pages']); ?></span>
								</li>
								<li class="list-group-item d-flex justify-content-between">
									<span>Inquiries</span>
									<span class="badge badge-warning"><?php echo number_format($stats['inquiries']); ?></span>
								</li>
							</ul>
						</div>
					</div>

					<div class="col-lg-4 col-md-6 mb-20">
						<div class="card-box pd-20">
							<h5 class="mb-20"><i class="icon-copy bi bi-info-circle"></i> System Status</h5>
							<ul class="list-group">
								<li class="list-group-item d-flex justify-content-between align-items-center">
									<span>Database</span>
									<span class="badge badge-success">Connected</span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center">
									<span>PHP Version</span>
									<span class="badge badge-info"><?php echo phpversion(); ?></span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center">
									<span>Your Role</span>
									<span class="badge badge-primary"><?php echo ucfirst($adminRole); ?></span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center">
									<span>Last Login</span>
									<span class="text-muted font-12">Today</span>
								</li>
							</ul>
						</div>
					</div>

					<div class="col-lg-4 col-md-12 mb-20">
						<div class="card-box pd-20 text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
							<div class="text-white">
								<i class="icon-copy bi bi-heart-pulse" style="font-size: 64px; opacity: 0.8;"></i>
								<h4 class="text-white mt-3 mb-2">Mental Health Research Center</h4>
								<p style="opacity: 0.9;">Empowering minds, transforming lives</p>
								<a href="site-settings.php" class="btn btn-light mt-3">
									<i class="icon-copy bi bi-gear"></i> Settings
								</a>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
		<?php include __DIR__ . '/includes/footer.php'; ?>

