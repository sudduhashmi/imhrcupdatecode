<?php
// Lightweight CMS utilities for dynamic pages
require_once __DIR__ . '/db.php';

function cmsEnsurePagesTable() {
    $exists = tableExists('pages');
    if ($exists) return;
    $sql = "CREATE TABLE IF NOT EXISTS pages (
        PageId INT AUTO_INCREMENT PRIMARY KEY,
        Title VARCHAR(255) NOT NULL,
        Slug VARCHAR(255) NOT NULL UNIQUE,
        Content LONGTEXT NULL,
        Status ENUM('draft','published') DEFAULT 'draft',
        CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
        UpdatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    execute($sql);
}

function getPageBySlug($slug) {
    $slug = sanitize($slug);
    return getRow("SELECT * FROM pages WHERE Slug = '$slug' AND Status = 'published'");
}

function getAllPages($status = null) {
    $where = $status ? "WHERE Status='" . sanitize($status) . "'" : '';
    return getRows("SELECT * FROM pages $where ORDER BY CreatedAt DESC");
}

function renderShortcodes($content) {
    if (!$content) return '';
    // [gallery] -> render media images grid
    $content = preg_replace_callback('/\[gallery\]/i', function() {
        $items = getRows("SELECT * FROM media WHERE Type='image' ORDER BY Created DESC LIMIT 12");
        $html = '<div class="row">';
        foreach ($items as $m) {
            $src = asset($m['Path']);
            $html .= '<div class="col-md-3 mb-3"><img src="'.htmlspecialchars($src).'" class="img-fluid rounded" alt=""></div>';
        }
        return $html . '</div>';
    }, $content);

    // [recent_posts] -> render recent articles list
    $content = preg_replace_callback('/\[recent_posts\]/i', function() {
        $posts = getRows("SELECT * FROM articles ORDER BY CreatedAt DESC LIMIT 5");
        $html = '<ul class="list-group">';
        foreach ($posts as $p) {
            $html .= '<li class="list-group-item"><strong>'.htmlspecialchars($p['Title']).'</strong> <span class="text-muted">'.htmlspecialchars($p['CreatedAt']).'</span></li>';
        }
        return $html . '</ul>';
    }, $content);

    return $content;
}

function renderPageContent($page) {
    if (!$page) return '';
    $content = $page['Content'] ?? '';
    return renderShortcodes($content);
}

?>
