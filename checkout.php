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

// Ambil cart items
$query = "SELECT c.*, p.name, p.price, p.stock 
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
} else {
    header('Location: ' . baseUrl('cart.php'));
    exit();
}

$shipping_fee = $total >= 200000 ? 0 : 20000;
$grand_total = $total + $shipping_fee;

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipping_address = clean($_POST['shipping_address']);
    
    if (empty($shipping_address)) {
        $error = 'Alamat pengiriman harus diisi';
    } else {
        // Validasi stok sebelum checkout
        $stock_error = false;
        $stock_messages = [];
        
        foreach ($cart_items as $item) {
            if ($item['stock'] <= 0) {
                $stock_error = true;
                $stock_messages[] = $item['name'] . ' sudah habis';
            } elseif ($item['quantity'] > $item['stock']) {
                $stock_error = true;
                $stock_messages[] = $item['name'] . ' hanya tersisa ' . $item['stock'] . ' item';
            }
        }
        
        if ($stock_error) {
            $error = '<strong>Checkout gagal!</strong><br>' . implode('<br>', $stock_messages) . '<br><small>Silakan sesuaikan quantity di keranjang Anda.</small>';
        } else {
            // Mulai transaksi
            $conn->begin_transaction();
            
            try {
                // Generate order number - cari nomor terkecil yang tersedia
                $order_number = 1;
                
                // Ambil semua order_number yang sudah ada untuk user ini
                $stmt = $conn->prepare("SELECT order_number FROM orders WHERE user_id = ? ORDER BY order_number ASC");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                $used_numbers = [];
                while ($row = $result->fetch_assoc()) {
                    $used_numbers[] = $row['order_number'];
                }
                
                // Cari gap (nomor yang hilang) atau ambil nomor berikutnya
                if (empty($used_numbers)) {
                    $order_number = 1;
                } else {
                    // Cari gap dalam urutan
                    for ($i = 1; $i <= count($used_numbers) + 1; $i++) {
                        if (!in_array($i, $used_numbers)) {
                            $order_number = $i;
                            break;
                        }
                    }
                }
                
                // Insert order dengan order_number
                $stmt = $conn->prepare("INSERT INTO orders (user_id, order_number, total_amount, shipping_address, status) VALUES (?, ?, ?, ?, 'pending')");
                $stmt->bind_param("iids", $user_id, $order_number, $grand_total, $shipping_address);
                $stmt->execute();
                $order_id = $conn->insert_id;
                
                // Insert order items dan update stok
                foreach ($cart_items as $item) {
                    // Cek stok lagi untuk keamanan
                    if ($item['stock'] < $item['quantity']) {
                        throw new Exception('Stok ' . $item['name'] . ' tidak mencukupi');
                    }
                    
                    // Insert order item
                    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
                    $stmt->execute();
                    
                    // Update stok produk - pastikan tidak minus
                    $new_stock = $item['stock'] - $item['quantity'];
                    if ($new_stock < 0) {
                        throw new Exception('Stok ' . $item['name'] . ' tidak mencukupi');
                    }
                    
                    $stmt = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
                    $stmt->bind_param("ii", $new_stock, $item['product_id']);
                    $stmt->execute();
                }
                
                // Hapus cart
                $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                
                $conn->commit();
                
                $success = 'Pesanan berhasil dibuat!';
                header('refresh:2;url=' . baseUrl('orders.php'));
            } catch (Exception $e) {
                $conn->rollback();
                $error = 'Terjadi kesalahan: ' . $e->getMessage();
            }
        }
    }
}

closeConnection($conn);

$title = 'Checkout';
include __DIR__ . '/includes/header.php';
?>

<section class="checkout-section">
    <div class="container">
        <h1>Checkout</h1>
        
        <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="checkout-wrapper">
            <div class="checkout-form-section">
                <h2>Informasi Pengiriman</h2>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="shipping_address">Alamat Lengkap</label>
                        <textarea id="shipping_address" name="shipping_address" class="form-control" rows="4" required placeholder="Masukkan alamat lengkap termasuk nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota, dan kode pos"></textarea>
                    </div>
                    
                    <div class="checkout-summary-mobile">
                        <h3>Ringkasan Pesanan</h3>
                        <div class="summary-row">
                            <span>Subtotal (<?php echo count($cart_items); ?> item)</span>
                            <strong><?php echo formatRupiah($total); ?></strong>
                        </div>
                        <div class="summary-row">
                            <span>Ongkos Kirim</span>
                            <strong><?php echo $shipping_fee > 0 ? formatRupiah($shipping_fee) : 'GRATIS'; ?></strong>
                        </div>
                        <hr>
                        <div class="summary-row total">
                            <span>Total Pembayaran</span>
                            <strong><?php echo formatRupiah($grand_total); ?></strong>
                        </div>
                    </div>
                    
                    <input type="submit" value="✅ Buat Pesanan" class="btn btn-primary btn-block">
                </form>
            </div>
            
            <div class="checkout-summary">
                <h3>Ringkasan Pesanan</h3>
                
                <div class="checkout-items">
                    <?php foreach ($cart_items as $item): ?>
                    <div class="checkout-item">
                        <div class="checkout-item-info">
                            <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                            <span><?php echo $item['quantity']; ?>x <?php echo formatRupiah($item['price']); ?></span>
                        </div>
                        <div class="checkout-item-total">
                            <?php echo formatRupiah($item['price'] * $item['quantity']); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <hr>
                
                <div class="summary-row">
                    <span>Subtotal</span>
                    <strong><?php echo formatRupiah($total); ?></strong>
                </div>
                <div class="summary-row">
                    <span>Ongkos Kirim</span>
                    <strong><?php echo $shipping_fee > 0 ? formatRupiah($shipping_fee) : 'GRATIS'; ?></strong>
                </div>
                <hr>
                <div class="summary-row total">
                    <span>Total Pembayaran</span>
                    <strong><?php echo formatRupiah($grand_total); ?></strong>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
