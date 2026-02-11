<?php
// Left navigation bar for the authenticated layout.
?>
<div class="left-side-bar">
	<div class="brand-logo">
		<a href="<?php echo BASE_URL; ?>index.php">
			<img src="<?php echo APP_LOGO_URL; ?>" alt="Imhrc" class="dark-logo" width="60" height="60" />
			<img src="<?php echo APP_LOGO_URL; ?>" alt="Imhrc" class="light-logo" width="60" height="60" />
		</a>
		<div class="close-sidebar" data-toggle="left-sidebar-close">
			<i class="ion-close-round"></i>
		</div>
	</div>
	<div class="menu-block customscroll">
		<div class="sidebar-menu">
			<ul id="accordion-menu">
				<!-- Dashboard -->
				<li>
					<a href="<?php echo BASE_URL; ?>index.php" class="dropdown-toggle no-arrow">
						<span class="micon bi bi-speedometer2"></span>
						<span class="mtext">Dashboard</span>
					</a>
				</li>
				
				<!-- Pages -->
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon bi bi-file-earmark-richtext"></span>
						<span class="mtext">Pages</span>
					</a>
					<ul class="submenu">
						<li><a href="<?php echo BASE_URL; ?>pages-list.php"><i class="bi bi-list-ul"></i> List All Pages</a></li>
						<li><a href="<?php echo BASE_URL; ?>page-add.php"><i class="bi bi-plus-circle"></i> Add New Page</a></li>
						<li><a href="<?php echo BASE_URL; ?>page-home.php"><i class="bi bi-house-fill"></i> Home Page</a></li>
						<li><a href="<?php echo BASE_URL; ?>about-overview.php"><i class="bi bi-eye"></i> About Overview</a></li>
						<li><a href="<?php echo BASE_URL; ?>global-collaborations.php"><i class="bi bi-globe"></i> Global Collaborations</a></li>
						<li><a href="<?php echo BASE_URL; ?>social-impact-sdg.php"><i class="bi bi-heart-pulse"></i> Social Impact & SDG</a></li>
						<li><a href="<?php echo BASE_URL; ?>annual-report.php"><i class="bi bi-file-earmark-pdf"></i> Annual Report</a></li>
						<li><a href="<?php echo BASE_URL; ?>hausla-overview.php"><i class="bi bi-emoji-smile"></i> Hausla Overview</a></li>
						<li><a href="<?php echo BASE_URL; ?>our-services.php"><i class="bi bi-gear"></i> Our Services</a></li>
						<li><a href="<?php echo BASE_URL; ?>our-team.php"><i class="bi bi-people"></i> Our Team</a></li>
						<li><a href="<?php echo BASE_URL; ?>book-appointment.php"><i class="bi bi-calendar-check"></i> Book Appointment</a></li>
						<li><a href="<?php echo BASE_URL; ?>event-reports-gallery.php"><i class="bi bi-images"></i> Event Reports & Gallery</a></li>
						<li><a href="<?php echo BASE_URL; ?>leadership-team.php"><i class="bi bi-person-badge"></i> Leadership Team</a></li>
					</ul>
				</li>
				
				<!-- Clinical Services -->
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon bi bi-hospital"></span>
						<span class="mtext">Clinical Services</span>
					</a>
					<ul class="submenu">
						<li><a href="<?php echo BASE_URL; ?>clinical-services.php"><i class="bi bi-collection"></i> Main Services</a></li>
						<li><a href="<?php echo BASE_URL; ?>adult-ctvs.php"><i class="bi bi-heart"></i> Adult CTVS</a></li>
						<li><a href="<?php echo BASE_URL; ?>anaesthesia.php"><i class="bi bi-droplet"></i> Anaesthesia</a></li>
						<li><a href="<?php echo BASE_URL; ?>art-therapy.php"><i class="bi bi-palette"></i> Art Therapy</a></li>
						<li><a href="<?php echo BASE_URL; ?>breast-endocrine.php"><i class="bi bi-activity"></i> Breast & Endocrine</a></li>
						<li><a href="<?php echo BASE_URL; ?>cardiac-sciences.php"><i class="bi bi-heart-pulse"></i> Cardiac Sciences</a></li>
						<li><a href="<?php echo BASE_URL; ?>counselling-psychology.php"><i class="bi bi-chat-heart"></i> Counselling Psychology</a></li>
						<li><a href="<?php echo BASE_URL; ?>critical-care.php"><i class="bi bi-shield-plus"></i> Critical Care</a></li>
					</ul>
				</li>
				
				<!-- Academic Programs -->
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon bi bi-mortarboard"></span>
						<span class="mtext">Academic Programs</span>
					</a>
					<ul class="submenu">
						<li><a href="<?php echo BASE_URL; ?>academic-programs.php"><i class="bi bi-collection"></i> Main Programs</a></li>
						<li><a href="<?php echo BASE_URL; ?>certificate-online.php"><i class="bi bi-award"></i> Certificate & Online</a></li>
						<li><a href="<?php echo BASE_URL; ?>diploma-programs.php"><i class="bi bi-patch-check"></i> Diploma Programs</a></li>
						<li><a href="<?php echo BASE_URL; ?>undergraduate.php"><i class="bi bi-journal-bookmark"></i> Undergraduate</a></li>
						<li><a href="<?php echo BASE_URL; ?>postgraduate-diploma.php"><i class="bi bi-bookmark-star"></i> Postgraduate Diploma</a></li>
						<li><a href="<?php echo BASE_URL; ?>other-academics.php"><i class="bi bi-box"></i> Other Offerings</a></li>
					</ul>
				</li>
				
				<!-- Research & Publications -->
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon bi bi-journal-text"></span>
						<span class="mtext">Research & Publications</span>
					</a>
					<ul class="submenu">
						<li><a href="<?php echo BASE_URL; ?>ongoing-projects.php"><i class="bi bi-diagram-3"></i> Ongoing Projects</a></li>
						<li><a href="<?php echo BASE_URL; ?>research-publications.php"><i class="bi bi-book"></i> Publications</a></li>
						<li><a href="<?php echo BASE_URL; ?>research-ethics.php"><i class="bi bi-shield-check"></i> Ethics & Guidelines</a></li>
					</ul>
				</li>
				
				<!-- Conferences & Events -->
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon bi bi-calendar-event"></span>
						<span class="mtext">Conferences & Events</span>
					</a>
					<ul class="submenu">
						<li><a href="<?php echo BASE_URL; ?>international-conference.php"><i class="bi bi-globe2"></i> International Conference</a></li>
						<li><a href="<?php echo BASE_URL; ?>conference-archive.php"><i class="bi bi-archive"></i> Conference Archive</a></li>
						<li><a href="<?php echo BASE_URL; ?>mental-health-forums.php"><i class="bi bi-chat-dots"></i> Mental Health Forums</a></li>
						<li><a href="<?php echo BASE_URL; ?>past-events.php"><i class="bi bi-clock-history"></i> Past Event Reports</a></li>
					</ul>
				</li>
				
				<!-- Resources -->
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon bi bi-folder"></span>
						<span class="mtext">Resources</span>
					</a>
					<ul class="submenu">
						<li><a href="<?php echo BASE_URL; ?>articles-blogs.php"><i class="bi bi-newspaper"></i> Articles & Blogs</a></li>
						<li><a href="<?php echo BASE_URL; ?>video-podcasts.php"><i class="bi bi-play-circle"></i> Videos & Podcasts</a></li>
						<li><a href="<?php echo BASE_URL; ?>self-help-resources.php"><i class="bi bi-lightbulb"></i> Self-Help Resources</a></li>
					</ul>
				</li>
				
				<!-- Content Management -->
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon bi bi-journal-text"></span>
						<span class="mtext">Content Management</span>
					</a>
					<ul class="submenu">
						<li><a href="<?php echo BASE_URL; ?>articles-manage.php"><i class="bi bi-newspaper"></i> Articles/Blogs</a></li>
						<li><a href="<?php echo BASE_URL; ?>events-manage.php"><i class="bi bi-calendar-event"></i> Events/Conferences</a></li>
						<li><a href="<?php echo BASE_URL; ?>media-gallery.php"><i class="bi bi-images"></i> Media Gallery</a></li>
						<li><a href="<?php echo BASE_URL; ?>appointments-manage.php"><i class="bi bi-calendar-check"></i> Appointments</a></li>
					</ul>
				</li>
				
				<!-- Media Library -->
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon bi bi-cloud-upload"></span>
						<span class="mtext">Media Library</span>
					</a>
					<ul class="submenu">
						<li><a href="<?php echo BASE_URL; ?>media-upload.php"><i class="bi bi-cloud-arrow-up"></i> Upload Media</a></li>
						<li><a href="<?php echo BASE_URL; ?>media-library.php"><i class="bi bi-collection"></i> All Media</a></li>
						<li><a href="<?php echo BASE_URL; ?>media-images.php"><i class="bi bi-image"></i> Images</a></li>
						<li><a href="<?php echo BASE_URL; ?>media-videos.php"><i class="bi bi-play-btn"></i> Videos</a></li>
						<li><a href="<?php echo BASE_URL; ?>media-documents.php"><i class="bi bi-file-earmark-pdf"></i> Documents/PDFs</a></li>
					</ul>
				</li>
				
				<!-- Team Management -->
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon bi bi-people-fill"></span>
						<span class="mtext">Team Management</span>
					</a>
					<ul class="submenu">
						<li><a href="<?php echo BASE_URL; ?>team-list.php"><i class="bi bi-people"></i> All Team Members</a></li>
						<li><a href="<?php echo BASE_URL; ?>team-add.php"><i class="bi bi-person-plus"></i> Add Team Member</a></li>
						<li><a href="<?php echo BASE_URL; ?>leadership-manage.php"><i class="bi bi-person-badge"></i> Leadership Team</a></li>
					</ul>
				</li>
				
				<!-- Settings -->
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon bi bi-gear-fill"></span>
						<span class="mtext">Settings</span>
					</a>
					<ul class="submenu">
						<li><a href="<?php echo BASE_URL; ?>site-settings.php"><i class="bi bi-sliders"></i> Site Settings</a></li>
						<li><a href="<?php echo BASE_URL; ?>menu-management.php"><i class="bi bi-menu-button-wide"></i> Menu Management</a></li>
						<li><a href="<?php echo BASE_URL; ?>seo-settings.php"><i class="bi bi-search"></i> SEO Settings</a></li>
					</ul>
				</li>
				
				<!-- Users & Roles -->
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<span class="micon bi bi-shield-lock-fill"></span>
						<span class="mtext">Users & Roles</span>
					</a>
					<ul class="submenu">
						<li><a href="<?php echo BASE_URL; ?>users-list.php"><i class="bi bi-person-lines-fill"></i> All Users</a></li>
						<li><a href="<?php echo BASE_URL; ?>user-add.php"><i class="bi bi-person-plus-fill"></i> Add User</a></li>
						<li><a href="<?php echo BASE_URL; ?>roles-manage.php"><i class="bi bi-key"></i> Manage Roles</a></li>
					</ul>
				</li>
				
				<li class="menu-divider"></li>
				
				<!-- Profile -->
				<li>
					<a href="<?php echo BASE_URL; ?>profile.php" class="dropdown-toggle no-arrow">
						<span class="micon bi bi-person-circle"></span>
						<span class="mtext">My Profile</span>
					</a>
				</li>
				
				<!-- Logout -->
				<li>
					<a href="<?php echo BASE_URL; ?>logout.php" class="dropdown-toggle no-arrow">
						<span class="micon bi bi-box-arrow-right"></span>
						<span class="mtext">Logout</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="mobile-menu-overlay"></div>