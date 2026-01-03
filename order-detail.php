<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/helpers.php';

requireLogin();

if (isAdmin()) {
    header('Location: ' . baseUrl('admin/dashboard.php'));
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: ' . baseUrl('orders.php'));
    exit();
}

$order_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];
$conn = getConnection();

// Ambil data order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ' . baseUrl('orders.php'));
    exit();
}

$order = $result->fetch_assoc();
$stmt->close();

// Ambil item order
$stmt = $conn->prepare("SELECT oi.*, p.name as product_name, p.image 
                        FROM order_items oi 
                        JOIN products p ON oi.product_id = p.id 
                        WHERE oi.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$items = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}

closeConnection($conn);

$title = 'Detail Pesanan #' . $order['order_number'];
include __DIR__ . '/includes/header.php';
?>

<section class="order-detail-section">
    <div class="container">
        <div class="admin-header">
            <h1>Detail Pesanan #<?php echo $order['order_number']; ?></h1>
            <a href="<?php echo baseUrl('orders.php'); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        
        <div class="order-status-track">
            <div class="status-step <?php echo in_array($order['status'], ['pending', 'processing', 'shipped', 'completed']) ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart"></i>
                <span>Pending</span>
            </div>
            <div class="status-step <?php echo in_array($order['status'], ['processing', 'shipped', 'completed']) ? 'active' : ''; ?>">
                <i class="fas fa-box"></i>
                <span>Diproses</span>
            </div>
            <div class="status-step <?php echo in_array($order['status'], ['shipped', 'completed']) ? 'active' : ''; ?>">
                <i class="fas fa-truck"></i>
                <span>Dikirim</span>
            </div>
            <div class="status-step <?php echo $order['status'] === 'completed' ? 'active' : ''; ?>">
                <i class="fas fa-check-circle"></i>
                <span>Selesai</span>
            </div>
        </div>
        
        <div class="order-detail-grid">
            <div class="order-info-card">
                <h3>Informasi Pesanan</h3>
                <p><strong>Tanggal Pesanan:</strong> <?php echo date('d M Y, H:i', strtotime($order['created_at'])); ?></p>
                <p><strong>Status:</strong> 
                    <span class="badge badge-<?php echo $order['status']; ?>">
                        <?php 
                        $status_text = [
                            'pending' => 'Pending',
                            'processing' => 'Diproses',
                            'shipped' => 'Dikirim',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan'
                        ];
                        echo $status_text[$order['status']] ?? $order['status'];
                        ?>
                    </span>
                </p>
                <p><strong>Total Pembayaran:</strong> <span class="text-primary"><?php echo formatRupiah($order['total_amount']); ?></span></p>
            </div>
            
            <div class="order-info-card">
                <h3>Alamat Pengiriman</h3>
                <p><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
            </div>
        </div>
        
        <div class="order-items-section">
            <h3>Item Pesanan</h3>
            <div class="order-items-list">
                <?php foreach ($items as $item): ?>
                <div class="order-item">
                    <div class="order-item-image">
                        <?php if (!empty($item['image'])): ?>
                            <img src="<?php echo baseUrl('assets/images/products/' . $item['image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                        <?php else: ?>
                            <img src="<?php echo baseUrl('assets/images/placeholder.svg'); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="order-item-info">
                        <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                        <p><?php echo formatRupiah($item['price']); ?> x <?php echo $item['quantity']; ?></p>
                    </div>
                    <div class="order-item-total">
                        <strong><?php echo formatRupiah($item['price'] * $item['quantity']); ?></strong>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="order-total-summary">
                <div class="summary-row">
                    <span>Total:</span>
                    <strong><?php echo formatRupiah($order['total_amount']); ?></strong>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
