<?php
require_once __DIR__ . '/config.php';

$pageTitle = $pageTitle ?? APP_NAME;
$pageStyles = $pageStyles ?? [];
$pageScripts = $pageScripts ?? [];
$bodyClass = trim((string)($bodyClass ?? ''));
$useLayout = $useLayout ?? true;
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title><?php echo htmlspecialchars($pageTitle); ?></title>

		<!-- Site favicon -->
		<link
			rel="apple-touch-icon"
			sizes="180x180"
			href="<?php echo asset('vendors/images/apple-touch-icon.png'); ?>"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="<?php echo asset('vendors/images/favicon-32x32.png'); ?>"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="<?php echo asset('vendors/images/favicon-16x16.png'); ?>"
		/>

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="<?php echo asset('vendors/styles/core.css'); ?>" />
		<link
			rel="stylesheet"
			type="text/css"
			href="<?php echo asset('vendors/styles/icon-font.min.css'); ?>"
		/>
		<link rel="stylesheet" type="text/css" href="<?php echo asset('vendors/styles/style.css'); ?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo asset('src/styles/responsive-fixes.css'); ?>" />

		<?php foreach ($pageStyles as $stylePath): ?>
		<link rel="stylesheet" type="text/css" href="<?php echo asset($stylePath); ?>" />
		<?php endforeach; ?>

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script
			async
			src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"
		></script>
		<script
			async
			src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
			crossorigin="anonymous"
		></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag() {
				dataLayer.push(arguments);
			}
			gtag("js", new Date());

			gtag("config", "G-GBZ3SGGX85");
		</script>
		<!-- Google Tag Manager -->
		<script>
			(function (w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({ "gtm.start": new Date().getTime(), event: "gtm.js" });
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s),
					dl = l != "dataLayer" ? "&l=" + l : "";
				j.async = true;
				j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, "script", "dataLayer", "GTM-NXZMQSS");
		</script>
		<!-- End Google Tag Manager -->
		
		<!-- Global Menu & Navigation Styling -->
		<style>
			/* Sidebar Menu Styling */
			.sidebar-menu ul li a .mtext {
				font-size: 13px !important;
				font-weight: 500;
			}
			
			.sidebar-menu ul li .submenu li a {
				font-size: 12px !important;
			}
			
			.sidebar-menu ul li .submenu li a i {
				margin-right: 6px;
				font-size: 11px;
			}
			
			/* Dropdown Menu Styling */
			.dropdown-menu {
				font-size: 13px !important;
			}
			
			.dropdown-menu .dropdown-item {
				font-size: 12px !important;
				padding: 8px 16px !important;
			}
			
			/* Header Menu Styling */
			.navbar-menu,
			.navbar .nav-link,
			.navbar .dropdown-menu a {
				font-size: 13px !important;
			}
			
			/* General Menu Items */
			.menu-item,
			.menu-link {
				font-size: 13px !important;
			}
		</style>
	</head>
	<body<?php echo $bodyClass ? ' class="' . htmlspecialchars($bodyClass) . '"' : ''; ?>>