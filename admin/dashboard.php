<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helpers.php';

requireAdmin();

$title = 'Dashboard Admin';
include __DIR__ . '/../includes/header.php';

$conn = getConnection();

// Statistik
$total_products = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$total_orders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
$total_customers = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='customer'")->fetch_assoc()['total'];
$total_revenue = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE status='completed'")->fetch_assoc()['total'] ?? 0;

closeConnection($conn);
?>

<section class="admin-section">
    <div class="container">
        <h1>Dashboard Admin</h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $total_products; ?></h3>
                    <p>Total Produk</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $total_orders; ?></h3>
                    <p>Total Pesanan</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $total_customers; ?></h3>
                    <p>Total Customer</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo formatRupiah($total_revenue); ?></h3>
                    <p>Total Pendapatan</p>
                </div>
            </div>
        </div>
        
        <div class="admin-menu-grid">
            <a href="<?php echo baseUrl('admin/products.php'); ?>" class="admin-menu-item">
                <i class="fas fa-tshirt"></i>
                <h3>Kelola Produk</h3>
                <p>Tambah, edit, dan hapus produk</p>
            </a>
            
            <a href="<?php echo baseUrl('admin/orders.php'); ?>" class="admin-menu-item">
                <i class="fas fa-clipboard-list"></i>
                <h3>Kelola Pesanan</h3>
                <p>Lihat dan update status pesanan</p>
            </a>
            
            <a href="<?php echo baseUrl('admin/customers.php'); ?>" class="admin-menu-item">
                <i class="fas fa-user-friends"></i>
                <h3>Data Customer</h3>
                <p>Lihat daftar customer</p>
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
