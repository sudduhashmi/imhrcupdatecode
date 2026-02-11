<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

// Get service ID from URL
$serviceId = intval($_GET['id'] ?? 0);

if ($serviceId <= 0) {
    header("Location: our-services.php");
    exit;
}

// Fetch service details
$service = getRow("
    SELECT s.*, sc.CategoryName, sc.CategoryDescription
    FROM services s
    LEFT JOIN service_categories sc ON s.CategoryId = sc.CategoryId
    WHERE s.ServiceId = ? AND s.Status = 'active'
", [$serviceId]);

if (!$service) {
    header("Location: our-services.php");
    exit;
}

// Get related services in the same category
$relatedServices = getRows("
    SELECT * FROM services 
    WHERE CategoryId = ? AND ServiceId != ? AND Status = 'active'
    ORDER BY DisplayOrder ASC
    LIMIT 3
", [$service['CategoryId'], $serviceId]);

$pageTitle = htmlspecialchars($service['ServiceName']) . ' - ' . APP_NAME;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(substr(strip_tags($service['ServiceDescription']), 0, 160)); ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #48bb78;
            --accent-color: #38b6ff;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f8f9fa;
            color: #333;
        }

        /* Breadcrumb & Header */
        .header-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 50px 0;
            margin-bottom: 50px;
        }

        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 20px;
        }

        .breadcrumb-item {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
        }

        .breadcrumb-item a {
            color: rgba(255, 255, 255, 1);
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            text-decoration: underline;
        }

        .service-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-top: 20px;
            margin-bottom: 15px;
        }

        .service-meta {
            font-size: 1.05rem;
            opacity: 0.95;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .category-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Main Content */
        .content-section {
            background: white;
            padding: 50px 0;
            margin-bottom: 50px;
            border-radius: 12px;
        }

        .service-icon-large {
            font-size: 100px;
            color: var(--primary-color);
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .description-content {
            font-size: 1.05rem;
            line-height: 1.8;
            color: #555;
        }

        .description-content h3 {
            color: var(--primary-color);
            font-weight: 600;
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .description-content p {
            margin-bottom: 15px;
        }

        .description-content ul {
            margin-bottom: 20px;
            padding-left: 20px;
        }

        .description-content li {
            margin-bottom: 10px;
            line-height: 1.6;
        }

        /* Details Grid */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .detail-card {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
        }

        .detail-card i {
            font-size: 40px;
            margin-bottom: 15px;
            display: block;
            opacity: 0.9;
        }

        .detail-card h4 {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .detail-card p {
            font-size: 0.95rem;
            opacity: 0.9;
            margin: 0;
        }

        /* Related Services */
        .related-section {
            background: white;
            padding: 50px 0;
            margin-bottom: 50px;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 40px;
            text-align: center;
        }

        .service-card-small {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 5px solid var(--primary-color);
            height: 100%;
        }

        .service-card-small:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .service-card-small-content {
            padding: 25px;
        }

        .service-card-small-icon {
            font-size: 40px;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .service-card-small-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .service-card-small-desc {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* CTA Buttons */
        .cta-buttons {
            display: flex;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn-custom {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: none;
            cursor: pointer;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-outline-custom {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline-custom:hover {
            background: var(--primary-color);
            color: white;
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .service-title {
                font-size: 1.8rem;
            }

            .header-section {
                padding: 30px 0;
                margin-bottom: 30px;
            }

            .content-section {
                padding: 30px 0;
                margin-bottom: 30px;
            }

            .service-icon-large {
                font-size: 60px;
                margin-bottom: 20px;
            }

            .description-content {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .service-meta {
                font-size: 0.95rem;
            }

            .cta-buttons {
                flex-direction: column;
            }

            .btn-custom {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
   <?php include 'includes/header.php'; ?>

    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="our-services.php">Services</a></li>
                    <li class="breadcrumb-item active"><?php echo htmlspecialchars($service['ServiceName']); ?></li>
                </ol>
            </nav>
            <h1 class="service-title">
                <i class="<?php echo htmlspecialchars($service['ServiceIcon']); ?>"></i>
                <?php echo htmlspecialchars($service['ServiceName']); ?>
            </h1>
            <div class="service-meta">
                <div class="category-badge">
                    <i class="fas fa-tag"></i> <?php echo htmlspecialchars($service['CategoryName']); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="content-section">
                    <div class="description-content">
                        <?php echo $service['ServiceDescription']; ?>
                    </div>

                    <div class="cta-buttons">
                        <a href="book-appointment.php" class="btn-custom btn-primary-custom">
                            <i class="fas fa-calendar-check"></i> Book Appointment
                        </a>
                        <a href="contact-us.php" class="btn-custom btn-outline-custom">
                            <i class="fas fa-envelope"></i> Get More Info
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; padding: 30px; border-radius: 12px; margin-bottom: 30px;">
                    <h4 style="margin-bottom: 20px; font-weight: 600;">
                        <i class="fas fa-info-circle"></i> Service Information
                    </h4>
                    <div style="font-size: 0.95rem; line-height: 1.8;">
                        <p>
                            <strong>Category:</strong> <?php echo htmlspecialchars($service['CategoryName']); ?>
                        </p>
                        <p>
                            <strong>Status:</strong> <span style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px;"><?php echo htmlspecialchars($service['Status']); ?></span>
                        </p>
                        <hr style="border-color: rgba(255,255,255,0.2);">
                        <p style="margin-bottom: 0;">
                            <strong>Need assistance?</strong><br>
                            <small>Our team is ready to help you.</small>
                        </p>
                    </div>
                </div>

                <!-- Quick Contact -->
                <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <h4 style="color: var(--primary-color); font-weight: 600; margin-bottom: 20px;">
                        <i class="fas fa-phone"></i> Quick Contact
                    </h4>
                    <p style="color: #666; margin-bottom: 20px;">Have questions? Reach out to us anytime.</p>
                    <a href="contact-us.php" class="btn-custom btn-primary-custom" style="width: 100%; justify-content: center;">
                        <i class="fas fa-comments"></i> Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Services -->
    <?php if ($relatedServices && count($relatedServices) > 0): ?>
    <div class="related-section">
        <div class="container">
            <h2 class="section-title">Related Services</h2>
            <div class="row g-4">
                <?php foreach ($relatedServices as $related): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="service-card-small">
                        <div class="service-card-small-content">
                            <div class="service-card-small-icon">
                                <i class="<?php echo htmlspecialchars($related['ServiceIcon']); ?>"></i>
                            </div>
                            <h3 class="service-card-small-title"><?php echo htmlspecialchars($related['ServiceName']); ?></h3>
                            <p class="service-card-small-desc">
                                <?php 
                                    $desc = strip_tags($related['ServiceDescription']);
                                    echo htmlspecialchars(substr($desc, 0, 100));
                                    if (strlen($desc) > 100) echo '...';
                                ?>
                            </p>
                            <a href="service-details.php?id=<?php echo $related['ServiceId']; ?>" style="color: var(--primary-color); text-decoration: none; font-weight: 600; margin-top: 15px; display: inline-block;">
                                Learn More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- CTA Section -->
    <div style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; padding: 60px 0; text-align: center; margin-top: 50px;">
        <div class="container">
            <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 15px;">Ready to Get Started?</h2>
            <p style="font-size: 1.1rem; margin-bottom: 30px; opacity: 0.95;">Schedule a consultation with our experts today.</p>
            <a href="book-appointment.php" class="btn-custom btn-primary-custom" style="background: white; color: var(--primary-color);">
                <i class="fas fa-calendar"></i> Book Now
            </a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
