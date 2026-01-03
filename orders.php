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

$query = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_number DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

closeConnection($conn);

$title = 'Pesanan Saya';
include __DIR__ . '/includes/header.php';
?>

<section class="orders-section">
    <div class="container">
        <h1>Pesanan Saya</h1>
        
        <?php if (!empty($orders)): ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
            <div class="order-card">
                <div class="order-header">
                    <div class="order-id">
                        <strong>Pesanan #<?php echo $order['order_number']; ?></strong>
                        <span class="order-date"><?php echo date('d M Y, H:i', strtotime($order['created_at'])); ?></span>
                    </div>
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
                </div>
                
                <div class="order-body">
                    <div class="order-info">
                        <p><strong>Total:</strong> <?php echo formatRupiah($order['total_amount']); ?></p>
                        <p><strong>Alamat Pengiriman:</strong><br><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                    </div>
                </div>
                
                <div class="order-footer">
                    <a href="<?php echo baseUrl('order-detail.php?id=' . $order['id']); ?>" class="btn btn-secondary">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h2>Belum Ada Pesanan</h2>
            <p>Anda belum pernah melakukan pemesanan</p>
            <a href="<?php echo baseUrl('products.php'); ?>" class="btn btn-primary">Mulai Belanja</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
