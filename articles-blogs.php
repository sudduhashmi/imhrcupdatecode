<?php
// Include database connection
// Adjust path if needed. If this is in root: 'admin/includes/db.php'
require_once 'admin/includes/db.php'; 

// --- FILTER LOGIC ---
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$whereClause = "WHERE Status = 'active'";

if (!empty($categoryFilter)) {
    $safeCat = $conn->real_escape_string($categoryFilter);
    $whereClause .= " AND (Category = '$safeCat' OR Tags LIKE '%$safeCat%')";
}

// --- FETCH CATEGORIES (For Filter Bar) ---
$categories = [];
$catSql = "SELECT DISTINCT Category FROM articles WHERE Status = 'active' ORDER BY Category ASC";
$catResult = $conn->query($catSql);
if ($catResult) {
    while($row = $catResult->fetch_assoc()) {
        if(!empty($row['Category'])) {
            $categories[] = $row['Category'];
        }
    }
}

// --- FETCH FEATURED ARTICLES ---
// Only show featured section if no specific filter is active (Home view of blog)
$featuredPosts = [];
if (empty($categoryFilter)) {
    $featSql = "SELECT * FROM articles WHERE Status = 'active' AND IsFeatured = 1 ORDER BY CreatedAt DESC LIMIT 3";
    $featResult = $conn->query($featSql);
    if ($featResult) {
        $featuredPosts = $featResult->fetch_all(MYSQLI_ASSOC);
    }
}

// --- FETCH BLOG GRID ARTICLES ---
// If filter is active, show matching. If not, show non-featured or all remaining to avoid duplicates.
$gridSql = "SELECT * FROM articles $whereClause";
if (empty($categoryFilter)) {
    // If viewing all, exclude featured ones from the main grid to keep it clean
    $gridSql .= " AND IsFeatured = 0";
}
$gridSql .= " ORDER BY CreatedAt DESC";

$gridResult = $conn->query($gridSql);
$gridPosts = ($gridResult) ? $gridResult->fetch_all(MYSQLI_ASSOC) : [];
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
    
    <title>Articles & Blogs - IMHRC</title>
   
<style>
  /* Page Specific Styles */
  .blog-header h1 { font-size: 2.5rem; margin-bottom: 10px; text-align: center; }
  .blog-header p { text-align: center; color: #555; margin-bottom: 40px; }

  /* Trending topics / Filters */
  .trending-topics { display: flex; flex-wrap: wrap; justify-content: center; gap: 15px; margin-bottom: 50px; }
  .trending-topics a { 
    background: #e0e0e0; padding: 10px 20px; border-radius: 20px; 
    cursor: pointer; transition: 0.3s; text-decoration: none; color: #333; font-size: 0.9rem;
    border: 1px solid transparent;
  }
  .trending-topics a:hover, .trending-topics a.active { 
    background: #4e5bff; color: #fff; border-color: #4e5bff; 
  }

  /* Featured section */
  .featured { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 50px; }
  .feature-card {
    background: #fff; border-radius: 10px; overflow: hidden; width: 100%; max-width: 350px;
    cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s; display: flex; flex-direction: column;
    margin: 0 auto;
  }
  .feature-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
  .feature-card img { width: 100%; height: 200px; object-fit: cover; }
  .feature-card .content { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; }
  .feature-card .badge { 
    display: inline-block; background: #4e5bff; color: #fff; 
    padding: 5px 10px; border-radius: 5px; font-size: 0.8rem; margin-bottom: 10px; align-self: flex-start; 
  }
  .feature-card h3 { font-size: 1.2rem; margin: 10px 0; color: #0b2c57; font-weight: 700; line-height: 1.4; }
  .feature-card .meta { font-size: 0.85rem; color: #777; margin-bottom: 10px; }
  .feature-card a.read-link { text-decoration: none; color: #4e5bff; font-weight: bold; margin-top: auto; }

  /* Blog grid */
  .blog-grid { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 50px; }
  .blog-card {
    background: #fff; border-radius: 10px; overflow: hidden; width: 100%; max-width: 350px;
    cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s; display: flex; flex-direction: column;
    margin: 0 auto;
  }
  .blog-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
  .blog-card img { width: 100%; height: 180px; object-fit: cover; }
  .blog-card .content { padding: 20px; flex-grow: 1; }
  .blog-card h4 { font-size: 1.1rem; margin-bottom: 10px; color: #0b2c57; font-weight: 700; }
  .blog-card p { font-size: 0.9rem; color: #555; margin-bottom: 10px; line-height: 1.5; }
  .blog-card .tags { font-size: 0.8rem; color: #4e5bff; font-weight: 600; text-transform: uppercase; }

  @media(max-width: 768px){
    .featured, .blog-grid { justify-content: center; }
  }
</style>
</head>

<body>

    <?php include 'includes/header.php'; ?>
    
    <!-- Start Page Title Area -->
    <div class="page-title-wave">
        <div class="container">
            <h2>Articles & Blogs</h2>
            <p>Trending insights on mental health, psychology research, student well-being, <br> clinical practice, and community mental health awareness.</p>
        </div>

        <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill="#ffffff" fill-opacity="1" 
              d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,
              1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
            </path>
        </svg>
    </div>

    <section class="container">

        <!-- TRENDING TOPICS (FILTERS) -->
        <div class="trending-topics">
            <a href="articles-blogs.php" class="<?php echo ($categoryFilter == '') ? 'active' : ''; ?>">All Topics</a>
            <?php foreach($categories as $cat): ?>
                <a href="articles-blogs.php?category=<?php echo urlencode($cat); ?>" 
                   class="<?php echo ($categoryFilter == $cat) ? 'active' : ''; ?>">
                   <?php echo htmlspecialchars($cat); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- FEATURED SECTION (Only show if no filter or on home view) -->
        <?php if(!empty($featuredPosts)): ?>
        <h3 class="mb-4" style="color: #0b2c57; font-weight: 700; border-left: 5px solid #4e5bff; padding-left: 15px;">Featured Insights</h3>
        <div class="featured">
            <?php foreach($featuredPosts as $post): ?>
            <div class="feature-card" onclick="openArticle('<?php echo $post['ArticleId']; ?>')">
                <?php 
                    // Handle image path
                    $imgSrc = !empty($post['CoverImage']) ? htmlspecialchars($post['CoverImage']) : 'assets/img/default-blog.jpg';
                    // Strip ../ if coming from admin path
                    $imgSrc = str_replace('../', '', $imgSrc);
                ?>
                <img src="<?php echo $imgSrc; ?>" onerror="this.src='assets/img/default-blog.jpg'" alt="<?php echo htmlspecialchars($post['Title']); ?>">
                <div class="content">
                    <span class="badge">Featured</span>
                    <h3><?php echo htmlspecialchars($post['Title']); ?></h3>
                    <div class="meta">By <?php echo htmlspecialchars($post['Author']); ?> · <?php echo htmlspecialchars($post['ReadTime']); ?></div>
                    <a href="article-details.php?id=<?php echo $post['ArticleId']; ?>" class="read-link">Read Article →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- BLOG GRID -->
        <h3 class="mb-4" style="color: #0b2c57; font-weight: 700; border-left: 5px solid #4e5bff; padding-left: 15px;">
            <?php echo !empty($categoryFilter) ? htmlspecialchars($categoryFilter) . ' Articles' : 'Latest Articles'; ?>
        </h3>
        
        <div class="blog-grid">
            <?php if(count($gridPosts) > 0): ?>
                <?php foreach($gridPosts as $post): ?>
                <div class="blog-card" onclick="openArticle('<?php echo $post['ArticleId']; ?>')">
                    <?php 
                        $imgSrc = !empty($post['CoverImage']) ? htmlspecialchars($post['CoverImage']) : 'assets/img/default-blog.jpg';
                        $imgSrc = str_replace('../', '', $imgSrc);
                    ?>
                    <img src="<?php echo $imgSrc; ?>" onerror="this.src='assets/img/default-blog.jpg'" alt="<?php echo htmlspecialchars($post['Title']); ?>">
                    <div class="content">
                        <h4><?php echo htmlspecialchars($post['Title']); ?></h4>
                        <p><?php echo htmlspecialchars(substr($post['Excerpt'], 0, 100)) . '...'; ?></p>
                        <div class="tags"><?php echo htmlspecialchars($post['Category']); ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="alert alert-light border shadow-sm">No articles found in this category.</div>
                </div>
            <?php endif; ?>
        </div>

    </section>

    <script>
        function openArticle(id){
            window.location.href = 'article-details.php?id=' + id;
        }
    </script>

    <?php include 'includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/meanmenu.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/magnific-popup.min.js"></script>
    <script src="assets/js/custom.js"></script>
  
</body>
</html>