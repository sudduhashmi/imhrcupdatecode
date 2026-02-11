<?php
// Left navigation menu for the authenticated layout.
// This file should be included within the left-side-bar div

// Helper functions for active states
if (!function_exists('is_parent_active')) {
    function is_parent_active($pages) {
        $current_page = basename($_SERVER['PHP_SELF']);
        return in_array($current_page, $pages) ? 'show' : '';
    }
}
if (!function_exists('is_child_active')) {
    function is_child_active($page) {
        $current_page = basename($_SERVER['PHP_SELF']);
        return $current_page == $page ? 'active' : '';
    }
}
?>
            <ul id="accordion-menu">
                <!-- Dashboard -->
                <li>
                    <a href="<?php echo BASE_URL; ?>index.php" class="dropdown-toggle no-arrow <?php echo is_child_active('index.php'); ?>">
                        <span class="micon bi bi-speedometer2"></span>
                        <span class="mtext">Dashboard</span>
                    </a>
                </li>
                
                <!-- Pages -->
                
                <!-- Services Management -->
                <?php if (function_exists('canPerform') ? canPerform('manage_services') : true) { 
                    $service_pages = ['services-list.php', 'service-add.php'];
                ?>
                <li class="dropdown <?php echo is_parent_active($service_pages); ?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-heart-pulse"></span>
                        <span class="mtext">Services</span>
                    </a>
                    <ul class="submenu" style="display: <?php echo is_parent_active($service_pages) ? 'block' : 'none'; ?>;">
                        <li><a href="<?php echo BASE_URL; ?>services-list.php" class="<?php echo is_child_active('services-list.php'); ?>"><i class="bi bi-list-ul"></i> All Services</a></li>
                        <li><a href="<?php echo BASE_URL; ?>service-add.php" class="<?php echo is_child_active('service-add.php'); ?>"><i class="bi bi-plus-circle"></i> Add Service</a></li>
                    </ul>
                </li>
                <?php } ?>
                
                <!-- Clinical Services -->
                <?php 
                    $clinical_pages = [
                        'clinical-services.php', 'clinical-services-manage.php', 'clinical-experts.php', 
                        'experts-list.php', 'adult-ctvs.php', 'anaesthesia.php', 'art-therapy.php', 
                        'breast-endocrine.php', 'cardiac-sciences.php', 'counselling-psychology.php', 'critical-care.php'
                    ];
                ?>
                <li class="dropdown <?php echo is_parent_active($clinical_pages); ?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-hospital"></span>
                        <span class="mtext">Clinical Services</span>
                    </a>
                    <ul class="submenu" style="display: <?php echo is_parent_active($clinical_pages) ? 'block' : 'none'; ?>;">
                        <li><a href="<?php echo BASE_URL; ?>clinical-services.php" class="<?php echo is_child_active('clinical-services.php'); ?>"><i class="bi bi-collection"></i> Main Services</a></li>
                        
                        <!-- Manage Services Link -->
                        <li><a href="<?php echo BASE_URL; ?>clinical-services-manage.php" class="<?php echo is_child_active('clinical-services-manage.php'); ?>"><i class="bi bi-pencil-square"></i> Manage Services</a></li>
                        
                        <li><a href="<?php echo BASE_URL; ?>clinical-experts.php" class="<?php echo is_child_active('clinical-experts.php'); ?>"><i class="bi bi-people-fill"></i> Clinical Experts</a></li>
                        <?php if (function_exists('canPerform') ? canPerform('manage_experts') : true) { ?>
                        <li><a href="<?php echo BASE_URL; ?>experts-list.php" class="<?php echo is_child_active('experts-list.php'); ?>"><i class="bi bi-person-vcard"></i> Manage Experts</a></li>
                        <?php } ?>
                        <li><a href="<?php echo BASE_URL; ?>adult-ctvs.php" class="<?php echo is_child_active('adult-ctvs.php'); ?>"><i class="bi bi-heart"></i> Adult CTVS</a></li>
                        <li><a href="<?php echo BASE_URL; ?>anaesthesia.php" class="<?php echo is_child_active('anaesthesia.php'); ?>"><i class="bi bi-droplet"></i> Anaesthesia</a></li>
                        <li><a href="<?php echo BASE_URL; ?>art-therapy.php" class="<?php echo is_child_active('art-therapy.php'); ?>"><i class="bi bi-palette"></i> Art Therapy</a></li>
                        <li><a href="<?php echo BASE_URL; ?>breast-endocrine.php" class="<?php echo is_child_active('breast-endocrine.php'); ?>"><i class="bi bi-activity"></i> Breast & Endocrine</a></li>
                        <li><a href="<?php echo BASE_URL; ?>cardiac-sciences.php" class="<?php echo is_child_active('cardiac-sciences.php'); ?>"><i class="bi bi-heart-pulse"></i> Cardiac Sciences</a></li>
                        <li><a href="<?php echo BASE_URL; ?>counselling-psychology.php" class="<?php echo is_child_active('counselling-psychology.php'); ?>"><i class="bi bi-chat-heart"></i> Counselling Psychology</a></li>
                        <li><a href="<?php echo BASE_URL; ?>critical-care.php" class="<?php echo is_child_active('critical-care.php'); ?>"><i class="bi bi-shield-plus"></i> Critical Care</a></li>
                    </ul>
                </li>
                
                <!-- Academic Programs -->
                <?php 
                    $academic_pages = [
                        'academic-programs.php', 'academic-programs-manage.php', 'manageAdmissionForm.php', 'courses-list.php', 
                        'certificate-online.php', 'diploma-programs.php', 'undergraduate.php', 
                        'postgraduate-diploma.php', 'other-academics.php'
                    ];
                ?>
                <li class="dropdown <?php echo is_parent_active($academic_pages); ?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-mortarboard"></span>
                        <span class="mtext">Academic Programs</span>
                    </a>
                    <ul class="submenu" style="display: <?php echo is_parent_active($academic_pages) ? 'block' : 'none'; ?>;">
                        <li><a href="<?php echo BASE_URL; ?>academic-programs.php" class="<?php echo is_child_active('academic-programs.php'); ?>"><i class="bi bi-collection"></i> Main Programs</a></li>
                        <!-- Example Link Placement in Sidebar -->
                       <li><a href="<?php echo BASE_URL; ?>internships-manage.php"><i class="bi bi-briefcase"></i> Manage Internships</a></li>
                        <!-- Manage Academic Programs Link -->
                        <li><a href="<?php echo BASE_URL; ?>academic-programs-manage.php" class="<?php echo is_child_active('academic-programs-manage.php'); ?>"><i class="bi bi-pencil-square"></i> Manage Programs</a></li>

                        <!-- Manage Admissions (Fixed: Permission check removed for visibility) -->
                        <li><a href="<?php echo BASE_URL; ?>manageAdmissionForm.php" class="<?php echo is_child_active('manageAdmissionForm.php'); ?>"><i class="bi bi-ui-checks"></i> Manage Admissions</a></li>

                        <?php if (function_exists('canPerform') ? canPerform('view_courses') : true) { ?>
                        <li><a href="<?php echo BASE_URL; ?>courses-list.php" class="<?php echo is_child_active('courses-list.php'); ?>"><i class="bi bi-journal"></i> Courses List</a></li>
                        <?php } ?>
                        <li><a href="<?php echo BASE_URL; ?>certificate-online.php" class="<?php echo is_child_active('certificate-online.php'); ?>"><i class="bi bi-award"></i> Certificate & Online</a></li>
                        <li><a href="<?php echo BASE_URL; ?>diploma-programs.php" class="<?php echo is_child_active('diploma-programs.php'); ?>"><i class="bi bi-patch-check"></i> Diploma Programs</a></li>
                        <li><a href="<?php echo BASE_URL; ?>undergraduate.php" class="<?php echo is_child_active('undergraduate.php'); ?>"><i class="bi bi-journal-bookmark"></i> Undergraduate</a></li>
                        <li><a href="<?php echo BASE_URL; ?>postgraduate-diploma.php" class="<?php echo is_child_active('postgraduate-diploma.php'); ?>"><i class="bi bi-bookmark-star"></i> Postgraduate Diploma</a></li>
                        <li><a href="<?php echo BASE_URL; ?>other-academics.php" class="<?php echo is_child_active('other-academics.php'); ?>"><i class="bi bi-box"></i> Other Offerings</a></li>
                    </ul>
                </li>
                
                <!-- Research & Publications -->
                <?php 
                    $research_pages = ['research-publications.php', 'ongoing-projects.php', 'research-ethics.php'];
                ?>
                <li class="dropdown <?php echo is_parent_active($research_pages); ?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-journal-text"></span>
                        <span class="mtext">Research & Publications</span>
                    </a>
                    <ul class="submenu" style="display: <?php echo is_parent_active($research_pages) ? 'block' : 'none'; ?>;">
                        <li><a href="<?php echo BASE_URL; ?>research-publications.php" class="<?php echo is_child_active('research-publications.php'); ?>"><i class="bi bi-pencil-square"></i> Manage Publications</a></li>
                        <li><a href="<?php echo BASE_URL; ?>ongoing-projects.php" class="<?php echo is_child_active('ongoing-projects.php'); ?>"><i class="bi bi-diagram-3"></i> Ongoing Projects</a></li>
                        <li><a href="<?php echo BASE_URL; ?>research-ethics.php" class="<?php echo is_child_active('research-ethics.php'); ?>"><i class="bi bi-shield-check"></i> Ethics & Guidelines</a></li>
                    </ul>
                </li>
                
                <!-- Conferences & Events -->
                <?php 
                    $event_pages = ['international-conference.php', 'conference-archive.php', 'mental-health-forums.php', 'past-events.php'];
                ?>
                <li class="dropdown <?php echo is_parent_active($event_pages); ?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-calendar-event"></span>
                        <span class="mtext">Conferences & Events</span>
                    </a>
                    <ul class="submenu" style="display: <?php echo is_parent_active($event_pages) ? 'block' : 'none'; ?>;">
                        <li><a href="<?php echo BASE_URL; ?>international-conference.php" class="<?php echo is_child_active('international-conference.php'); ?>"><i class="bi bi-globe2"></i> International Conference</a></li>
                        <li><a href="<?php echo BASE_URL; ?>conference-archive.php" class="<?php echo is_child_active('conference-archive.php'); ?>"><i class="bi bi-archive"></i> Conference Archive</a></li>
                        <li><a href="<?php echo BASE_URL; ?>mental-health-forums.php" class="<?php echo is_child_active('mental-health-forums.php'); ?>"><i class="bi bi-chat-dots"></i> Mental Health Forums</a></li>
                        <li><a href="<?php echo BASE_URL; ?>past-events.php" class="<?php echo is_child_active('past-events.php'); ?>"><i class="bi bi-clock-history"></i> Past Event Reports</a></li>
                    </ul>
                </li>
                
                <!-- Resources -->
                <?php 
                    $resource_pages = ['articles-blogs.php', 'video-podcasts.php', 'self-help-resources.php', 'results-list.php'];
                ?>
                <li class="dropdown <?php echo is_parent_active($resource_pages); ?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-folder"></span>
                        <span class="mtext">Resources</span>
                    </a>
                    <ul class="submenu" style="display: <?php echo is_parent_active($resource_pages) ? 'block' : 'none'; ?>;">
                        <li><a href="<?php echo BASE_URL; ?>articles-blogs.php" class="<?php echo is_child_active('articles-blogs.php'); ?>"><i class="bi bi-newspaper"></i> Articles & Blogs (View)</a></li>
                        <li><a href="<?php echo BASE_URL; ?>video-podcasts.php" class="<?php echo is_child_active('video-podcasts.php'); ?>"><i class="bi bi-play-circle"></i> Videos & Podcasts</a></li>
                        <li><a href="<?php echo BASE_URL; ?>self-help-resources.php" class="<?php echo is_child_active('self-help-resources.php'); ?>"><i class="bi bi-lightbulb"></i> Self-Help Resources</a></li>
                        <?php if (function_exists('canPerform') ? canPerform('view_reports') : true) { ?>
                        <li><a href="<?php echo BASE_URL; ?>results-list.php" class="<?php echo is_child_active('results-list.php'); ?>"><i class="bi bi-card-checklist"></i> Results</a></li>
                        <?php } ?>
                    </ul>
                </li>
                
                <!-- Content Management -->
                <?php 
                    $cms_pages = ['articles-manage.php', 'events-manage.php', 'media-gallery.php', 'appointments-manage.php', 'manageAdmissionForm.php'];
                ?>
                <li class="dropdown <?php echo is_parent_active($cms_pages); ?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-journal-text"></span>
                        <span class="mtext">Content Management</span>
                    </a>
                    <ul class="submenu" style="display: <?php echo is_parent_active($cms_pages) ? 'block' : 'none'; ?>;">
                        <li><a href="<?php echo BASE_URL; ?>articles-manage.php" class="<?php echo is_child_active('articles-manage.php'); ?>"><i class="bi bi-pencil-square"></i> Manage Articles</a></li>
                        <li><a href="<?php echo BASE_URL; ?>events-manage.php" class="<?php echo is_child_active('events-manage.php'); ?>"><i class="bi bi-calendar-event"></i> Manage Events</a></li>
                        <li><a href="<?php echo BASE_URL; ?>media-gallery.php" class="<?php echo is_child_active('media-gallery.php'); ?>"><i class="bi bi-images"></i> Manage Gallery</a></li>
                        <li><a href="<?php echo BASE_URL; ?>appointments-manage.php" class="<?php echo is_child_active('appointments-manage.php'); ?>"><i class="bi bi-calendar-check"></i> Manage Appointments</a></li>
                        
                        <!-- Updated Admission Link (Fixed: Permission check removed for visibility) -->
                        <li><a href="<?php echo BASE_URL; ?>manageAdmissionForm.php" class="<?php echo is_child_active('manageAdmissionForm.php'); ?>"><i class="bi bi-ui-checks"></i> Manage Admissions</a></li>
                    </ul>
                </li>
                
                <!-- Media Library -->
                <?php 
                    $media_pages = ['media-upload.php', 'media-library.php'];
                ?>
                <li class="dropdown <?php echo is_parent_active($media_pages); ?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-cloud-upload"></span>
                        <span class="mtext">Media Library</span>
                    </a>
                    <ul class="submenu" style="display: <?php echo is_parent_active($media_pages) ? 'block' : 'none'; ?>;">
                        <li><a href="<?php echo BASE_URL; ?>media-upload.php" class="<?php echo is_child_active('media-upload.php'); ?>"><i class="bi bi-cloud-arrow-up"></i> Upload Media</a></li>
                        <li><a href="<?php echo BASE_URL; ?>media-library.php" class="<?php echo is_child_active('media-library.php'); ?>"><i class="bi bi-collection"></i> All Media</a></li>
                    </ul>
                </li>
                
                <!-- Team Management -->
                <?php 
                    $team_pages = ['team-list.php', 'team-add.php', 'leadership-manage.php'];
                ?>
                <li class="dropdown <?php echo is_parent_active($team_pages); ?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-people-fill"></span>
                        <span class="mtext">Team Management</span>
                    </a>
                    <ul class="submenu" style="display: <?php echo is_parent_active($team_pages) ? 'block' : 'none'; ?>;">
                        <li><a href="<?php echo BASE_URL; ?>team-list.php" class="<?php echo is_child_active('team-list.php'); ?>"><i class="bi bi-people"></i> All Team Members</a></li>
                        <li><a href="<?php echo BASE_URL; ?>team-add.php" class="<?php echo is_child_active('team-add.php'); ?>"><i class="bi bi-person-plus"></i> Add Team Member</a></li>
                        <li><a href="<?php echo BASE_URL; ?>leadership-manage.php" class="<?php echo is_child_active('leadership-manage.php'); ?>"><i class="bi bi-person-badge"></i> Leadership Team</a></li>
                    </ul>
                </li>
                
                <!-- Settings -->
                <?php 
                    $setting_pages = ['site-settings.php', 'menu-management.php', 'seo-settings.php'];
                ?>
                <li class="dropdown <?php echo is_parent_active($setting_pages); ?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-gear-fill"></span>
                        <span class="mtext">Settings</span>
                    </a>
                    <ul class="submenu" style="display: <?php echo is_parent_active($setting_pages) ? 'block' : 'none'; ?>;">
                        <li><a href="<?php echo BASE_URL; ?>site-settings.php" class="<?php echo is_child_active('site-settings.php'); ?>"><i class="bi bi-sliders"></i> Site Settings</a></li>
                        <li><a href="<?php echo BASE_URL; ?>menu-management.php" class="<?php echo is_child_active('menu-management.php'); ?>"><i class="bi bi-menu-button-wide"></i> Menu Management</a></li>
                        <li><a href="<?php echo BASE_URL; ?>seo-settings.php" class="<?php echo is_child_active('seo-settings.php'); ?>"><i class="bi bi-search"></i> SEO Settings</a></li>
                    </ul>
                </li>
                
                <!-- Users & Roles -->
                <?php 
                    $user_pages = ['users-list.php', 'user-add.php', 'roles-manage.php'];
                ?>
                <li class="dropdown <?php echo is_parent_active($user_pages); ?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-shield-lock-fill"></span>
                        <span class="mtext">Users & Roles</span>
                    </a>
                    <ul class="submenu" style="display: <?php echo is_parent_active($user_pages) ? 'block' : 'none'; ?>;">
                        <li><a href="<?php echo BASE_URL; ?>users-list.php" class="<?php echo is_child_active('users-list.php'); ?>"><i class="bi bi-person-lines-fill"></i> All Users</a></li>
                        <li><a href="<?php echo BASE_URL; ?>user-add.php" class="<?php echo is_child_active('user-add.php'); ?>"><i class="bi bi-person-plus-fill"></i> Add User</a></li>
                        <li><a href="<?php echo BASE_URL; ?>roles-manage.php" class="<?php echo is_child_active('roles-manage.php'); ?>"><i class="bi bi-key"></i> Manage Roles</a></li>
                    </ul>
                </li>
                
                <li class="menu-divider"></li>
                
                <!-- Profile -->
                <li>
                    <a href="<?php echo BASE_URL; ?>profile.php" class="dropdown-toggle no-arrow <?php echo is_child_active('profile.php'); ?>">
                        <span class="micon bi bi-person-circle"></span>
                        <span class="mtext">My Profile</span>
                    </a>
                </li>
                
                <!-- Clear Cookies -->
                <li>
                    <a href="<?php echo BASE_URL; ?>academic-programs.php?action=clear_cookies" class="dropdown-toggle no-arrow" onclick="return confirm('Are you sure you want to clear cookies?')">
                        <span class="micon bi bi-eraser"></span>
                        <span class="mtext">Clear Cookies</span>
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