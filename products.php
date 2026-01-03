<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/helpers.php';

$title = 'Produk';
include __DIR__ . '/includes/header.php';

// Filter
$category = isset($_GET['category']) ? clean($_GET['category']) : '';
$search = isset($_GET['search']) ? clean($_GET['search']) : '';

$conn = getConnection();
$query = "SELECT * FROM products WHERE 1=1";
$params = [];
$types = '';

if (!empty($category)) {
    $query .= " AND category = ?";
    $params[] = $category;
    $types .= 's';
}

if (!empty($search)) {
    $query .= " AND (name LIKE ? OR description LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'ss';
}

$query .= " ORDER BY created_at DESC";

if (!empty($params)) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($query);
}

$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Ambil kategori untuk filter
$categories = $conn->query("SELECT DISTINCT category FROM products ORDER BY category")->fetch_all(MYSQLI_ASSOC);

closeConnection($conn);
?>

<section class="products-page">
    <div class="container">
        <h1>Semua Produk</h1>
        
        <div class="filters">
            <form method="GET" action="" class="filter-form">
                <div class="filter-group">
                    <input type="text" name="search" placeholder="Cari produk..." value="<?php echo htmlspecialchars($search); ?>" class="form-control">
                    
                    <select name="category" class="form-control">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat['category']); ?>" <?php echo $category === $cat['category'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['category']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <input type="submit" value="🔍 Filter" class="btn btn-primary">
                    
                    <?php if (!empty($category) || !empty($search)): ?>
                    <a href="<?php echo baseUrl('products.php'); ?>" class="btn btn-secondary">Reset</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <?php if (!empty($product['image'])): ?>
                        <img src="<?php echo baseUrl('assets/images/products/' . $product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php else: ?>
                        <img src="<?php echo baseUrl('assets/images/placeholder.svg'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php endif; ?>
                    <?php if ($product['stock'] < 10 && $product['stock'] > 0): ?>
                        <span class="badge badge-warning">Stok Terbatas</span>
                    <?php elseif ($product['stock'] === 0): ?>
                        <span class="badge badge-danger">Stok Habis</span>
                    <?php endif; ?>
                </div>
                <div class="product-info">
                    <span class="product-category"><?php echo htmlspecialchars($product['category']); ?></span>
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="product-price"><?php echo formatRupiah($product['price']); ?></p>
                    <div class="product-actions">
                        <a href="<?php echo baseUrl('product-detail.php?id=' . $product['id']); ?>" class="btn btn-secondary">Lihat Detail</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (empty($products)): ?>
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <p>Tidak ada produk ditemukan</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
