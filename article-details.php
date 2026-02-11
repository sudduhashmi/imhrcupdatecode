<?php
// Database connection
// Assuming this file is in root and db.php is in admin/includes/
require_once 'admin/includes/db.php'; 

// 1. Get Article ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: articles-blogs.php");
    exit;
}
$id = (int)$_GET['id'];

// 2. Fetch Article
$stmt = $conn->prepare("SELECT * FROM articles WHERE ArticleId = ? AND Status = 'active'");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();

// If not found, redirect back
if (!$article) {
    header("Location: articles-blogs.php");
    exit;
}

// 3. Increment View Count
$conn->query("UPDATE articles SET ViewCount = ViewCount + 1 WHERE ArticleId = $id");

// 4. Fetch Related Articles (Same Category, excluding current)
$cat = $article['Category'];
$relStmt = $conn->prepare("SELECT * FROM articles WHERE Category = ? AND ArticleId != ? AND Status = 'active' ORDER BY CreatedAt DESC LIMIT 3");
$relStmt->bind_param("si", $cat, $id);
$relStmt->execute();
$related = $relStmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSS Links -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/boxicons.min.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.min.css">
    <link rel="stylesheet" href="assets/css/flaticon.css">
    <link rel="stylesheet" href="assets/css/meanmenu.min.css">
    <link rel="stylesheet" href="assets/css/odometer.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dark.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts for better typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">

    <title><?php echo htmlspecialchars($article['Title']); ?> - IMHRC</title>
    
    <style>
        :root {
            /* Luxury Color Palette */
            --primary-color: #bfa15f;       /* Elegant Gold */
            --dark-blue: #0a1f33;          /* Deep Royal Navy */
            --accent-color: #d4af37;       /* Metallic Gold */
            --bg-light: #fafafa;           /* Crisp Off-White */
            --text-main: #374151;          /* Dark Gray */
            --text-heading: #111827;       /* Nearly Black */
            --link-hover: #9f8240;         /* Darker Gold */
        }

        body { font-family: 'Inter', sans-serif; color: var(--text-main); background-color: var(--bg-light); }
        
        /* Modern Hero Section */
        .article-hero {
            /* Luxury Gradient */
            background: linear-gradient(135deg, #0f1c2e 0%, #1c3d5a 100%);
            padding: 180px 0 120px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .article-hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('assets/img/pattern.png'); /* If you have a pattern */
            opacity: 0.05;
            mix-blend-mode: overlay;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 900px;
            margin: 0 auto;
        }

        .category-badge {
            display: inline-block;
            background: rgba(191, 161, 95, 0.2); /* Gold tint */
            backdrop-filter: blur(10px);
            color: #fceec7; /* Light cream text */
            padding: 8px 24px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 25px;
            border: 1px solid rgba(191, 161, 95, 0.4);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .article-title {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.25;
            margin-bottom: 35px;
            text-shadow: 0 4px 10px rgba(0,0,0,0.3);
            font-family: 'Merriweather', serif; /* Serif for title feels more luxury */
            color: #ffffff; /* Explicitly white */
        }

        .hero-meta {
            display: flex;
            justify-content: center;
            gap: 25px;
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.85);
            flex-wrap: wrap;
            font-weight: 400;
            letter-spacing: 0.5px;
        }

        .hero-meta div {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .hero-meta i {
            color: var(--primary-color); /* Gold icons */
        }

        /* Content Container Overlap */
        .main-content-wrapper {
            margin-top: -80px;
            position: relative;
            z-index: 10;
            padding-bottom: 80px;
        }

        .article-card {
            background: #fff;
            border-radius: 4px; /* Sharper corners for luxury feel */
            box-shadow: 0 20px 60px -10px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.02);
        }

        .featured-img-container {
            width: 100%;
            height: 550px;
            overflow: hidden;
            position: relative;
        }
        
        .featured-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .article-body {
            padding: 80px 15% 60px;
        }

        .content-text {
            font-family: 'Merriweather', serif;
            font-size: 1.25rem;
            line-height: 2;
            color: #2d3748; /* Deep Gray */
        }
        
        .content-text h2, .content-text h3, .content-text h4 {
            font-family: 'Inter', sans-serif;
            color: var(--dark-blue);
            font-weight: 800;
            margin-top: 3.5rem;
            margin-bottom: 1.5rem;
            letter-spacing: -0.5px;
        }
        
        .content-text h2 { 
            font-size: 2.2rem; 
            border-bottom: 2px solid var(--primary-color); 
            padding-bottom: 15px; 
            display: inline-block;
        }

        .content-text p { margin-bottom: 1.8rem; }

        .content-text img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin: 40px 0;
            box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.15);
        }
        
        .content-text blockquote {
            border-left: 4px solid var(--primary-color);
            padding: 30px 40px;
            margin: 50px 0;
            background: #fdfcf8; /* Warm tint */
            font-style: italic;
            color: var(--dark-blue);
            font-size: 1.5rem;
            font-weight: 400;
            font-family: 'Merriweather', serif;
        }

        /* Footer: Tags & Share */
        .article-footer {
            margin-top: 80px;
            padding-top: 40px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .tags-container a {
            display: inline-block;
            background: #fff;
            color: var(--text-main);
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 0.85rem;
            margin-right: 10px;
            margin-bottom: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 600;
            border: 1px solid #e5e7eb;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .tags-container a:hover { 
            background: var(--dark-blue); 
            color: #fff; 
            border-color: var(--dark-blue);
        }

        .share-container span { font-weight: 700; margin-right: 15px; color: var(--text-heading); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; }
        .social-share-btn {
            width: 48px; height: 48px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid #e5e7eb;
            color: var(--text-main);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-left: 10px;
            text-decoration: none;
        }
        .social-share-btn:hover {
            background: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
            transform: translateY(-5px);
            box-shadow: 0 10px 20px -5px rgba(191, 161, 95, 0.4);
        }

        /* Related Section */
        .related-section {
            background: #f8f9fa;
            padding: 100px 0;
            border-top: 1px solid #eaeaea;
        }
        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }
        .section-title h3 {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--dark-blue);
            position: relative;
            display: inline-block;
            padding-bottom: 20px;
            font-family: 'Merriweather', serif;
        }
        .section-title h3::after {
            content: '';
            position: absolute;
            bottom: 0; left: 50%; transform: translateX(-50%);
            width: 60px; height: 3px;
            background: var(--primary-color);
        }

        .related-card {
            display: block;
            text-decoration: none;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.4s ease;
            height: 100%;
        }
        .related-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); }
        
        .related-thumb { height: 250px; overflow: hidden; position: relative; }
        .related-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s cubic-bezier(0.2, 1, 0.2, 1); }
        .related-card:hover .related-thumb img { transform: scale(1.05); }
        
        .related-content { padding: 30px; }
        .related-date { 
            font-size: 0.75rem; 
            color: var(--primary-color); 
            font-weight: 700; 
            text-transform: uppercase; 
            letter-spacing: 1.5px; 
            margin-bottom: 12px; 
            display: block; 
        }
        .related-title { 
            font-size: 1.4rem; 
            font-weight: 700; 
            color: var(--text-heading); 
            margin-bottom: 0; 
            line-height: 1.4; 
            transition: color 0.2s;
            font-family: 'Merriweather', serif;
        }
        .related-card:hover .related-title { color: var(--primary-color); }

        @media(max-width: 991px) {
            .article-title { font-size: 2.5rem; }
            .article-body { padding: 40px 30px; }
            .featured-img-container { height: 400px; }
        }
        @media(max-width: 768px) {
            .article-title { font-size: 2rem; }
            .hero-meta { flex-direction: column; gap: 10px; font-size: 0.95rem; }
            .article-body { padding: 30px 20px; }
            .featured-img-container { height: 250px; }
            .main-content-wrapper { margin-top: 0; }
            .article-hero { padding: 130px 0 60px; }
            .article-card { border-radius: 0; box-shadow: none; background: transparent; border: none; }
            .article-body { background: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); margin-top: -30px; z-index: 20; position: relative; }
        }
    </style>
</head>

<body>

    <?php include 'includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="article-hero">
        <div class="container hero-content">
            <span class="category-badge"><?php echo htmlspecialchars($article['Category']); ?></span>
            <h1 class="article-title"><?php echo htmlspecialchars($article['Title']); ?></h1>
            
            <div class="hero-meta">
                <div><i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($article['Author']); ?></div>
                <div class="d-none d-md-block">•</div>
                <div><i class="bi bi-calendar3"></i> <?php echo date('F d, Y', strtotime($article['CreatedAt'])); ?></div>
                <div class="d-none d-md-block">•</div>
                <div><i class="bi bi-clock"></i> <?php echo htmlspecialchars($article['ReadTime']); ?></div>
                <div class="d-none d-md-block">•</div>
                <div><i class="bi bi-eye"></i> <?php echo $article['ViewCount']; ?> Views</div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container main-content-wrapper">
        <div class="article-card">
            <!-- Featured Image -->
            <?php if(!empty($article['CoverImage'])): ?>
            <div class="featured-img-container">
                <img src="<?php echo htmlspecialchars($article['CoverImage']); ?>" alt="Article Image" onerror="this.src='assets/img/default-blog.jpg'">
            </div>
            <?php endif; ?>

            <!-- Article Body -->
            <div class="article-body">
                <div class="content-text">
                    <!-- HTML Content from Editor -->
                    <?php echo $article['Content']; ?> 
                </div>

                <!-- Footer: Tags & Share -->
                <div class="article-footer">
                    <div class="tags-container">
                        <?php 
                        if (!empty($article['Tags'])) {
                            $tags = explode(',', $article['Tags']);
                            foreach($tags as $tag) {
                                $tag = trim($tag);
                                if($tag) echo "<a href='#'>#$tag</a>";
                            }
                        }
                        ?>
                    </div>
                    <div class="share-container">
                        <span>Share:</span>
                        <a href="#" class="social-share-btn"><i class="bx bxl-facebook"></i></a>
                        <a href="#" class="social-share-btn"><i class="bx bxl-twitter"></i></a>
                        <a href="#" class="social-share-btn"><i class="bx bxl-linkedin"></i></a>
                        <a href="#" class="social-share-btn"><i class="bx bxl-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Articles -->
    <?php if(count($related) > 0): ?>
    <div class="related-section">
        <div class="container">
            <div class="section-title">
                <h3>You Might Also Like</h3>
            </div>
            <div class="row g-4">
                <?php foreach($related as $rel): ?>
                <div class="col-md-4">
                    <a href="article-details.php?id=<?php echo $rel['ArticleId']; ?>" class="related-card">
                        <div class="related-thumb">
                            <img src="<?php echo !empty($rel['CoverImage']) ? $rel['CoverImage'] : 'assets/img/default-blog.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($rel['Title']); ?>"
                                 onerror="this.src='assets/img/default-blog.jpg'">
                        </div>
                        <div class="related-content">
                            <span class="related-date"><?php echo date('M d, Y', strtotime($rel['CreatedAt'])); ?></span>
                            <h4 class="related-title"><?php echo htmlspecialchars($rel['Title']); ?></h4>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php include 'includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/meanmenu.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/custom.js"></script>
  
</body>
</html>