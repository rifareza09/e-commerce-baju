<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/helpers.php';

if (!isset($_GET['id'])) {
    header('Location: ' . baseUrl('products.php'));
    exit();
}

$product_id = intval($_GET['id']);
$conn = getConnection();

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ' . baseUrl('products.php'));
    exit();
}

$product = $result->fetch_assoc();
$stmt->close();

// Handle add to cart
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn() && !isAdmin()) {
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    
    // Validasi stok habis
    if ($product['stock'] <= 0) {
        $message = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Maaf, stok produk ini sudah habis!</div>';
    } elseif ($quantity > $product['stock']) {
        $message = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Stok tidak mencukupi! Stok tersedia: ' . $product['stock'] . '</div>';
    } elseif ($quantity < 1) {
        $message = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Jumlah minimal pembelian adalah 1</div>';
    } else {
        $user_id = $_SESSION['user_id'];
        
        // Cek apakah produk sudah ada di cart
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $cart_result = $stmt->get_result();
        
        if ($cart_result->num_rows > 0) {
            // Update quantity dengan cek stok
            $cart_item = $cart_result->fetch_assoc();
            $new_quantity = $cart_item['quantity'] + $quantity;
            
            // Validasi total quantity tidak melebihi stok
            if ($new_quantity > $product['stock']) {
                $message = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Total quantity di keranjang akan melebihi stok! Stok tersedia: ' . $product['stock'] . ', Di keranjang: ' . $cart_item['quantity'] . '</div>';
            } else {
                $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
                $stmt->bind_param("ii", $new_quantity, $cart_item['id']);
                $stmt->execute();
                $message = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Produk berhasil ditambahkan ke keranjang!</div>';
            }
        } else {
            // Insert new
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $user_id, $product_id, $quantity);
            $stmt->execute();
            $message = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Produk berhasil ditambahkan ke keranjang!</div>';
        }
    }
}

closeConnection($conn);

$title = $product['name'];
include __DIR__ . '/includes/header.php';
?>

<section class="product-detail">
    <div class="container">
        <?php echo $message; ?>
        
        <div class="product-detail-grid">
            <div class="product-detail-image">
                <?php if (!empty($product['image'])): ?>
                    <img src="<?php echo baseUrl('assets/images/products/' . $product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <?php else: ?>
                    <img src="<?php echo baseUrl('assets/images/placeholder.svg'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <?php endif; ?>
            </div>
            
            <div class="product-detail-info">
                <span class="product-category"><?php echo htmlspecialchars($product['category']); ?></span>
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="product-price-large"><?php echo formatRupiah($product['price']); ?></p>
                
                <div class="product-stock">
                    <?php if ($product['stock'] > 10): ?>
                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Stok tersedia (<?php echo $product['stock']; ?>)</span>
                    <?php elseif ($product['stock'] > 0): ?>
                        <span class="badge badge-warning"><i class="fas fa-exclamation-triangle"></i> Stok terbatas (<?php echo $product['stock']; ?>)</span>
                    <?php else: ?>
                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Stok habis</span>
                    <?php endif; ?>
                </div>
                
                <div class="product-description">
                    <h3>Deskripsi Produk</h3>
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
                
                <?php if (isLoggedIn() && !isAdmin() && $product['stock'] > 0): ?>
                <form method="POST" action="" class="add-to-cart-form">
                    <div class="quantity-selector">
                        <label for="quantity">Jumlah:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="form-control">
                    </div>
                    <input type="submit" value="🛒 Tambah ke Keranjang" class="btn btn-primary btn-lg">
                </form>
                <?php elseif (isLoggedIn() && !isAdmin() && $product['stock'] <= 0): ?>
                <div class="alert alert-danger" style="margin-top: 20px;">
                    <i class="fas fa-times-circle"></i> <strong>Maaf, produk ini sedang tidak tersedia</strong><br>
                    <small>Stok produk habis. Silakan cek produk lain atau hubungi admin.</small>
                </div>
                <?php elseif (!isLoggedIn()): ?>
                <div class="login-prompt">
                    <p><i class="fas fa-info-circle"></i> Silakan <a href="<?php echo baseUrl('login.php'); ?>">login</a> untuk membeli produk</p>
                </div>
                <?php endif; ?>
                
                <div class="product-features">
                    <div class="feature-item">
                        <i class="fas fa-truck"></i>
                        <span>Gratis Ongkir min. Rp 200.000</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-exchange-alt"></i>
                        <span>Garansi retur 7 hari</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>100% Original</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
