<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helpers.php';

requireAdmin();

if (!isset($_GET['id'])) {
    header('Location: ' . baseUrl('admin/orders.php'));
    exit();
}

$order_id = intval($_GET['id']);
$conn = getConnection();

// Ambil data order
$stmt = $conn->prepare("SELECT o.*, u.name as customer_name, u.email as customer_email 
                        FROM orders o 
                        JOIN users u ON o.user_id = u.id 
                        WHERE o.id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ' . baseUrl('admin/orders.php'));
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
$stmt->close();
closeConnection($conn);

$title = 'Detail Pesanan #' . $order_id;
include __DIR__ . '/../includes/header.php';
?>

<section class="admin-section">
    <div class="container">
        <div class="admin-header">
            <h1>Detail Pesanan #<?php echo $order['id']; ?></h1>
            <a href="<?php echo baseUrl('admin/orders.php'); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        
        <div class="order-detail-grid">
            <div class="order-info-card">
                <h3>Informasi Customer</h3>
                <p><strong>Nama:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                <p><strong>Alamat Pengiriman:</strong><br><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
            </div>
            
            <div class="order-info-card">
                <h3>Informasi Pesanan</h3>
                <p><strong>Tanggal:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
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
                <p><strong>Total:</strong> <span class="text-primary"><?php echo formatRupiah($order['total_amount']); ?></span></p>
            </div>
        </div>
        
        <div class="order-items-section">
            <h3>Item Pesanan</h3>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <div class="product-info-inline">
                                <?php if (!empty($item['image'])): ?>
                                    <img src="<?php echo baseUrl('assets/images/products/' . $item['image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="table-img">
                                <?php endif; ?>
                                <span><?php echo htmlspecialchars($item['product_name']); ?></span>
                            </div>
                        </td>
                        <td><?php echo formatRupiah($item['price']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo formatRupiah($item['price'] * $item['quantity']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                        <td><strong><?php echo formatRupiah($order['total_amount']); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
