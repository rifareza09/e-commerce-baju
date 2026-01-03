<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/helpers.php';

$title = 'Home';
include __DIR__ . '/includes/header.php';

// Ambil produk terbaru
$conn = getConnection();
$query = "SELECT * FROM products ORDER BY created_at DESC LIMIT 8";
$result = $conn->query($query);
$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
closeConnection($conn);
?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Koleksi Kaos Terbaru</h1>
            <p>Temukan kaos distro, streetwear, dan casual terbaik dengan desain eksklusif</p>
            <a href="<?php echo baseUrl('products.php'); ?>" class="btn btn-primary">Lihat Produk</a>
        </div>
    </div>
</section>

<section class="features">
    <div class="container">
        <div class="features-grid">
            <div class="feature-item">
                <i class="fas fa-truck"></i>
                <h3>Gratis Ongkir</h3>
                <p>Untuk pembelian minimal Rp 200.000</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-shield-alt"></i>
                <h3>Pembayaran Aman</h3>
                <p>Transaksi dijamin aman</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-exchange-alt"></i>
                <h3>Bisa Retur</h3>
                <p>Garansi retur 7 hari</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-headset"></i>
                <h3>Customer Service</h3>
                <p>Siap membantu 24/7</p>
            </div>
        </div>
    </div>
</section>

<section class="products-section">
    <div class="container">
        <h2 class="section-title">Produk Terbaru</h2>
        
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <?php if (!empty($product['image'])): ?>
                        <img src="<?php echo baseUrl('assets/images/products/' . $product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php else: ?>
                        <img src="<?php echo baseUrl('assets/images/placeholder.svg'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php endif; ?>
                    <?php if ($product['stock'] < 10): ?>
                        <span class="badge badge-warning">Stok Terbatas</span>
                    <?php endif; ?>
                </div>
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="product-price"><?php echo formatRupiah($product['price']); ?></p>
                    <div class="product-actions">
                        <a href="<?php echo baseUrl('product-detail.php?id=' . $product['id']); ?>" class="btn btn-secondary">Lihat Detail</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (count($products) >= 8): ?>
        <div class="text-center mt-4">
            <a href="<?php echo baseUrl('products.php'); ?>" class="btn btn-primary">Lihat Semua Produk</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
