<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/helpers.php';

requireLogin();

if (isAdmin()) {
    header('Location: ' . baseUrl('admin/dashboard.php'));
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = getConnection();

// Handle update quantity
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update' && isset($_POST['cart_id']) && isset($_POST['quantity'])) {
        $cart_id = intval($_POST['cart_id']);
        $quantity = intval($_POST['quantity']);
        
        // Ambil data cart dan produk untuk validasi stok
        $stmt = $conn->prepare("SELECT c.*, p.stock, p.name FROM cart c JOIN products p ON c.product_id = p.id WHERE c.id = ? AND c.user_id = ?");
        $stmt->bind_param("ii", $cart_id, $user_id);
        $stmt->execute();
        $cart_result = $stmt->get_result();
        
        if ($cart_result->num_rows > 0) {
            $cart_data = $cart_result->fetch_assoc();
            
            if ($quantity <= 0) {
                $message = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Jumlah minimal adalah 1</div>';
            } elseif ($quantity > $cart_data['stock']) {
                $message = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Stok ' . htmlspecialchars($cart_data['name']) . ' hanya tersedia ' . $cart_data['stock'] . ' item</div>';
            } else {
                $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
                $stmt->bind_param("iii", $quantity, $cart_id, $user_id);
                $stmt->execute();
                $message = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Quantity berhasil diupdate</div>';
            }
        }
    } elseif ($_POST['action'] === 'remove' && isset($_POST['cart_id'])) {
        $cart_id = intval($_POST['cart_id']);
        
        $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $cart_id, $user_id);
        $stmt->execute();
        $message = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Produk berhasil dihapus dari keranjang</div>';
    }
}

// Ambil cart items
$query = "SELECT c.*, p.name, p.price, p.stock, p.image 
          FROM cart c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = [];
$total = 0;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total += $row['price'] * $row['quantity'];
    }
}

closeConnection($conn);

$title = 'Keranjang Belanja';
include __DIR__ . '/includes/header.php';
?>

<section class="cart-section">
    <div class="container">
        <h1>Keranjang Belanja</h1>
        
        <?php echo $message; ?>
        
        <?php if (!empty($cart_items)): ?>
        <div class="cart-wrapper">
            <div class="cart-items">
                <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <div class="cart-item-image">
                        <?php if (!empty($item['image'])): ?>
                            <img src="<?php echo baseUrl('assets/images/products/' . $item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <?php else: ?>
                            <img src="<?php echo baseUrl('assets/images/placeholder.svg'); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <?php endif; ?>
                    </div>
                    
                    <div class="cart-item-info">
                        <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                        <p class="cart-item-price"><?php echo formatRupiah($item['price']); ?></p>
                        
                        <?php if ($item['stock'] <= 0): ?>
                            <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Stok Habis</span>
                        <?php elseif ($item['quantity'] > $item['stock']): ?>
                            <span class="badge badge-warning"><i class="fas fa-exclamation-triangle"></i> Stok hanya tersisa <?php echo $item['stock']; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="cart-item-actions">
                        <?php if ($item['stock'] > 0): ?>
                        <form method="POST" action="" class="quantity-form">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>" class="form-control" onchange="this.form.submit()">
                        </form>
                        <?php else: ?>
                        <p style="color: #ef4444; font-size: 14px;"><strong>Tidak tersedia</strong></p>
                        <?php endif; ?>
                        
                        <form method="POST" action="" class="remove-form">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                            <input type="submit" value="🗑️" class="btn btn-danger btn-sm">
                        </form>
                    </div>
                    
                    <div class="cart-item-total">
                        <strong><?php echo formatRupiah($item['price'] * $item['quantity']); ?></strong>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="cart-summary">
                <h3>Ringkasan Belanja</h3>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <strong><?php echo formatRupiah($total); ?></strong>
                </div>
                <div class="summary-row">
                    <span>Ongkir</span>
                    <strong><?php echo $total >= 200000 ? 'GRATIS' : 'Rp 20.000'; ?></strong>
                </div>
                <hr>
                <div class="summary-row total">
                    <span>Total</span>
                    <strong><?php echo formatRupiah($total >= 200000 ? $total : $total + 20000); ?></strong>
                </div>
                
                <a href="<?php echo baseUrl('checkout.php'); ?>" class="btn btn-primary btn-block">
                    <i class="fas fa-credit-card"></i> Checkout
                </a>
                
                <a href="<?php echo baseUrl('products.php'); ?>" class="btn btn-secondary btn-block">
                    <i class="fas fa-shopping-bag"></i> Lanjut Belanja
                </a>
            </div>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-shopping-cart"></i>
            <h2>Keranjang Kosong</h2>
            <p>Belum ada produk di keranjang Anda</p>
            <a href="<?php echo baseUrl('products.php'); ?>" class="btn btn-primary">Mulai Belanja</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
