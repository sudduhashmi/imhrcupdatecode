<?php
// ================= ERROR REPORTING =================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ================= INCLUDES =================
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

requireLogin();
$pageTitle = 'Manage Research & Publications - ' . APP_NAME;

// ================= AUTO-FIX DATABASE SCHEMA =================
try {
    // 1. Create Tables if missing
    $conn->query("CREATE TABLE IF NOT EXISTS publications (
        PubId INT AUTO_INCREMENT PRIMARY KEY,
        Title VARCHAR(255) NOT NULL,
        Category ENUM('Journal', 'Tool', 'Book') NOT NULL,
        Author VARCHAR(255),
        Description TEXT,
        CoverImage VARCHAR(255),
        Link VARCHAR(255),
        Price DECIMAL(10,2) DEFAULT 0,
        Details JSON,
        IsFeatured TINYINT(1) DEFAULT 0,
        Status ENUM('published', 'draft') DEFAULT 'published',
        CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $conn->query("CREATE TABLE IF NOT EXISTS book_proposals (
        ProposalId INT AUTO_INCREMENT PRIMARY KEY,
        FullName VARCHAR(100) NOT NULL,
        Email VARCHAR(100) NOT NULL,
        Phone VARCHAR(20) NOT NULL,
        BookTitle VARCHAR(255) NOT NULL,
        Genre VARCHAR(100),
        Description TEXT,
        ManuscriptFile VARCHAR(255),
        SubmittedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 2. Fix Column Name Mismatch (id vs PubId)
    $checkPubId = $conn->query("SHOW COLUMNS FROM publications LIKE 'PubId'");
    if ($checkPubId && $checkPubId->num_rows == 0) {
        $checkId = $conn->query("SHOW COLUMNS FROM publications LIKE 'id'");
        if ($checkId && $checkId->num_rows > 0) {
            $conn->query("ALTER TABLE publications CHANGE id PubId INT AUTO_INCREMENT");
        }
    }

    // 3. Ensure Missing Columns Exist
    $checkDetails = $conn->query("SHOW COLUMNS FROM publications LIKE 'Details'");
    if ($checkDetails && $checkDetails->num_rows == 0) {
        $conn->query("ALTER TABLE publications ADD COLUMN Details JSON AFTER Price");
    }
    
    $checkFeatured = $conn->query("SHOW COLUMNS FROM publications LIKE 'IsFeatured'");
    if ($checkFeatured && $checkFeatured->num_rows == 0) {
        $conn->query("ALTER TABLE publications ADD COLUMN IsFeatured TINYINT(1) DEFAULT 0 AFTER Details");
    }
    
    // 4. Auto-fix Incorrect Status (0 -> published) for existing records
    $conn->query("UPDATE publications SET Status = 'published' WHERE Status = '0' OR Status = ''");

} catch (Exception $e) {
    // Continue execution even if fix fails
}

// ================= DELETE HANDLERS =================
if (isset($_GET['del_pub'])) {
    $id = (int)$_GET['del_pub'];
    $conn->query("DELETE FROM publications WHERE PubId = $id");
    header("Location: research-publications.php?msg=pub_deleted");
    exit;
}
if (isset($_GET['del_prop'])) {
    $id = (int)$_GET['del_prop'];
    $conn->query("DELETE FROM book_proposals WHERE ProposalId = $id");
    header("Location: research-publications.php?tab=proposals&msg=prop_deleted");
    exit;
}

// ================= SAVE / UPDATE PUBLICATION =================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_publication'])) {

    $id         = $_POST['pub_id'] ?? '';
    $title      = trim($_POST['title']);
    $cat        = $_POST['category'];
    $author     = trim($_POST['author']);
    $desc       = trim($_POST['description']);
    $price      = (float)($_POST['price'] ?? 0);
    $status     = $_POST['status'];
    $feat       = isset($_POST['is_featured']) ? 1 : 0;

    // JSON Details
    $details = json_encode([
        'pages'  => $_POST['pages'] ?? '',
        'type'   => $_POST['type'] ?? '',
        'rating' => $_POST['rating'] ?? '',
        'link'   => $_POST['link'] ?? ''
    ]);

    // Image Upload
    $cover = $_POST['existing_cover'] ?? '';
    if (!empty($_FILES['cover_image']['name'])) {
        $dir = '../assets/img/publications/';
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        
        $name = time() . '_' . basename($_FILES['cover_image']['name']);
        if(move_uploaded_file($_FILES['cover_image']['tmp_name'], $dir . $name)){
             $cover = 'assets/img/publications/' . $name;
        }
    }

    try {
        if ($id) {
            // Update
            $stmt = $conn->prepare("UPDATE publications SET Title=?, Category=?, Author=?, Description=?, CoverImage=?, Price=?, Details=?, IsFeatured=?, Status=? WHERE PubId=?");
            // FIXED: Changed 9th char from 'i' to 's' for Status
            // sssssdsssi -> Title(s), Cat(s), Author(s), Desc(s), Cover(s), Price(d), Details(s), Feat(s), Status(s), Id(i)
            $stmt->bind_param("sssssdsssi", $title, $cat, $author, $desc, $cover, $price, $details, $feat, $status, $id);
        } else {
            // Insert
            $stmt = $conn->prepare("INSERT INTO publications (Title, Category, Author, Description, CoverImage, Price, Details, IsFeatured, Status) VALUES (?,?,?,?,?,?,?,?,?)");
            // FIXED: Changed 9th char from 'i' to 's' for Status
            // sssssdsss -> Title(s), Cat(s), Author(s), Desc(s), Cover(s), Price(d), Details(s), Feat(s), Status(s)
            $stmt->bind_param("sssssdsss", $title, $cat, $author, $desc, $cover, $price, $details, $feat, $status);
        }

        if($stmt->execute()) {
            header("Location: research-publications.php?msg=saved");
            exit;
        } else {
            $error = "Database Error: " . $conn->error;
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// ================= FETCH DATA =================
$publications = [];
$proposals = [];

try {
    $res = $conn->query("SELECT * FROM publications ORDER BY CreatedAt DESC");
    if ($res) {
        $publications = $res->fetch_all(MYSQLI_ASSOC);
    }

    $resP = $conn->query("SELECT * FROM book_proposals ORDER BY SubmittedAt DESC");
    if ($resP) {
        $proposals = $resP->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    $error = "Error fetching data: " . $e->getMessage();
}

$activeTab = isset($_GET['tab']) && $_GET['tab'] === 'proposals' ? 'proposals' : 'publications';
?>

<?php require_once 'includes/head.php'; ?>
<body>

<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>

<div class="main-container">
    <div class="pd-ltr-20">

        <!-- Header -->
        <div class="page-header mb-4 bg-white p-4 rounded shadow-sm d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0 text-primary">Research & Publications</h4>
                <p class="text-muted small mb-0">Manage books, journals, tools and user proposals.</p>
            </div>
            <button class="btn btn-primary" onclick="openModal()">
                <i class="bi bi-plus-lg"></i> Add Publication
            </button>
        </div>

        <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Action completed successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link <?php echo $activeTab == 'publications' ? 'active' : ''; ?>" id="pub-tab" data-bs-toggle="tab" data-bs-target="#pub-tab-pane" type="button">Publications</button>
            </li>
            <li class="nav-item">
                <button class="nav-link <?php echo $activeTab == 'proposals' ? 'active' : ''; ?>" id="prop-tab" data-bs-toggle="tab" data-bs-target="#prop-tab-pane" type="button">
                    Book Proposals <span class="badge bg-danger rounded-pill ms-1"><?php echo count($proposals); ?></span>
                </button>
            </li>
        </ul>

        <div class="tab-content">
            
            <!-- PUBLICATIONS LIST -->
            <div class="tab-pane fade <?php echo $activeTab == 'publications' ? 'show active' : ''; ?>" id="pub-tab-pane">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Cover</th>
                                        <th>Title & Category</th>
                                        <th>Author</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($publications): foreach ($publications as $row): ?>
                                    <tr>
                                        <td>
                                            <img src="../<?php echo !empty($row['CoverImage']) ? $row['CoverImage'] : 'assets/img/default-book.png'; ?>" 
                                                 width="40" height="55" style="object-fit:cover" class="rounded border">
                                        </td>
                                        <td>
                                            <strong class="text-dark d-block text-truncate" style="max-width:300px;"><?php echo htmlspecialchars($row['Title']); ?></strong>
                                            <span class="badge bg-light text-dark border"><?php echo $row['Category']; ?></span>
                                            <?php if(!empty($row['IsFeatured'])): ?>
                                                <i class="bi bi-star-fill text-warning ms-1" title="Featured"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['Author']); ?></td>
                                        <td><?php echo $row['Price'] > 0 ? '₹'.$row['Price'] : 'Free'; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $row['Status']=='published'?'success':'secondary'; ?>-subtle text-<?php echo $row['Status']=='published'?'success':'secondary'; ?>">
                                                <?php echo ucfirst($row['Status']); ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php $pubId = $row['PubId'] ?? $row['id'] ?? 0; ?>
                                            <div class="btn-group shadow-sm">
                                                <!-- EDIT BUTTON -->
                                                <button type="button" class="btn btn-sm btn-light border text-primary"
                                                    onclick='editPublication(<?php echo htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>)'
                                                    title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <!-- DELETE BUTTON -->
                                                <a href="research-publications.php?del_pub=<?php echo $pubId; ?>"
                                                   class="btn btn-sm btn-light border text-danger"
                                                   onclick="return confirm('Delete this publication?')"
                                                   title="Delete">
                                                   <i class="bi bi-trash3"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; else: ?>
                                    <tr><td colspan="6" class="text-center py-5 text-muted">No publications found</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PROPOSALS LIST -->
            <div class="tab-pane fade <?php echo $activeTab == 'proposals' ? 'show active' : ''; ?>" id="prop-tab-pane">
                 <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Applicant</th>
                                        <th>Book Details</th>
                                        <th>Contact</th>
                                        <th>Submitted</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($proposals): foreach ($proposals as $prop): ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo htmlspecialchars($prop['FullName']); ?></td>
                                        <td>
                                            <span class="d-block fw-bold"><?php echo htmlspecialchars($prop['BookTitle']); ?></span>
                                            <small class="text-muted"><?php echo htmlspecialchars($prop['Genre']); ?></small>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <i class="bi bi-envelope"></i> <?php echo htmlspecialchars($prop['Email']); ?><br>
                                                <i class="bi bi-phone"></i> <?php echo htmlspecialchars($prop['Phone']); ?>
                                            </div>
                                        </td>
                                        <td><small class="text-muted"><?php echo date('d M Y', strtotime($prop['SubmittedAt'])); ?></small></td>
                                        <td class="text-center">
                                            <?php $propId = $prop['ProposalId'] ?? $prop['id'] ?? 0; ?>
                                            <div class="btn-group shadow-sm">
                                                <?php if(!empty($prop['ManuscriptFile'])): ?>
                                                    <a href="../<?php echo $prop['ManuscriptFile']; ?>" target="_blank" class="btn btn-sm btn-light border text-primary" title="View Manuscript"><i class="bi bi-file-earmark-text"></i></a>
                                                <?php endif; ?>
                                                <a href="research-publications.php?del_prop=<?php echo $propId; ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Delete proposal?')" title="Delete"><i class="bi bi-trash3"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; else: ?>
                                    <tr><td colspan="5" class="text-center py-5 text-muted">No proposals received yet.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                 </div>
            </div>

        </div>

    </div>
</div>

<!-- ================= MODAL ================= -->
<div class="modal fade" id="pubModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Add Publication</h5>
                <button type="button" class="btn-close" onclick="pubModal.hide()"></button>
            </div>
            
            <div class="modal-body p-4 pt-3">
                <form method="post" enctype="multipart/form-data" id="pubForm">
                    <input type="hidden" name="pub_id" id="pub_id">
                    <input type="hidden" name="existing_cover" id="existing_cover">

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-muted">Title</label>
                            <input class="form-control" name="title" id="title" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Category</label>
                            <select class="form-select" name="category" id="category" onchange="toggleFields()">
                                <option value="Journal">Journal</option>
                                <option value="Book">Book</option>
                                <option value="Tool">Tool</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Author</label>
                            <input class="form-control" name="author" id="author" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Price (0 for Free)</label>
                            <input type="number" class="form-control" name="price" id="price" value="0">
                        </div>

                        <!-- Book Specific Fields -->
                        <div class="col-md-4 book-field" style="display:none;">
                            <label class="form-label small fw-bold text-muted">Pages</label>
                            <input type="text" name="pages" id="pages" class="form-control" placeholder="e.g. 250">
                        </div>
                        <div class="col-md-4 book-field" style="display:none;">
                            <label class="form-label small fw-bold text-muted">Type</label>
                            <input type="text" name="type" id="type" class="form-control" placeholder="e.g. Paperback">
                        </div>
                        <div class="col-md-4 book-field" style="display:none;">
                            <label class="form-label small fw-bold text-muted">Rating</label>
                            <input type="text" name="rating" id="rating" class="form-control" placeholder="0-5">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">External Link / URL</label>
                            <input type="text" name="link" id="link" class="form-control" placeholder="https://...">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Description</label>
                            <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-muted">Cover Image</label>
                            <input type="file" class="form-control" name="cover_image">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Status</label>
                            <select class="form-select" name="status" id="status">
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_featured" id="is_featured" value="1">
                                <label class="form-check-label fw-bold text-warning" for="is_featured">Mark as Featured</label>
                            </div>
                        </div>

                        <div class="col-12 mt-4 d-grid">
                            <button class="btn btn-primary rounded-pill py-2 fw-bold" name="save_publication">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
let pubModal;
document.addEventListener("DOMContentLoaded", ()=>{
    pubModal = new bootstrap.Modal(document.getElementById('pubModal'));
});

function openModal(){
    document.getElementById('pubForm').reset();
    document.getElementById('pub_id').value='';
    document.getElementById('existing_cover').value='';
    document.getElementById('modalTitle').innerText='Add Publication';
    toggleFields();
    pubModal.show();
}

function editPublication(d){
    document.getElementById('modalTitle').innerText='Edit Publication';
    
    // Basic Fields
    document.getElementById('pub_id').value = d.PubId;
    document.getElementById('title').value = d.Title;
    document.getElementById('author').value = d.Author;
    document.getElementById('category').value = d.Category;
    document.getElementById('description').value = d.Description;
    document.getElementById('price').value = d.Price;
    document.getElementById('status').value = d.Status;
    document.getElementById('existing_cover').value = d.CoverImage;
    // Check if IsFeatured exists before accessing
    if(d.IsFeatured) {
        document.getElementById('is_featured').checked = (d.IsFeatured == 1);
    } else {
        document.getElementById('is_featured').checked = false;
    }

    // JSON Details Parsing
    let details = {};
    try { details = JSON.parse(d.Details); } catch(e){}
    
    document.getElementById('link').value = details.link || '';
    document.getElementById('pages').value = details.pages || '';
    document.getElementById('type').value = details.type || '';
    document.getElementById('rating').value = details.rating || '';

    toggleFields();
    pubModal.show();
}

function toggleFields() {
    const cat = document.getElementById('category').value;
    const fields = document.querySelectorAll('.book-field');
    fields.forEach(el => el.style.display = (cat === 'Book') ? 'block' : 'none');
}
</script>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>