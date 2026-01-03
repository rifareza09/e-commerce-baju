<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helpers.php';

requireAdmin();

$title = 'Data Customer';
include __DIR__ . '/../includes/header.php';

$conn = getConnection();
$query = "SELECT u.*, COUNT(o.id) as total_orders, COALESCE(SUM(o.total_amount), 0) as total_spent
          FROM users u
          LEFT JOIN orders o ON u.id = o.user_id
          WHERE u.role = 'customer'
          GROUP BY u.id
          ORDER BY u.created_at DESC";
$result = $conn->query($query);
$customers = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
}
closeConnection($conn);
?>

<section class="admin-section">
    <div class="container">
        <h1>Data Customer</h1>
        
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Total Pesanan</th>
                        <th>Total Belanja</th>
                        <th>Terdaftar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?php echo $customer['id']; ?></td>
                        <td><?php echo htmlspecialchars($customer['name']); ?></td>
                        <td><?php echo htmlspecialchars($customer['email']); ?></td>
                        <td><?php echo $customer['total_orders']; ?></td>
                        <td><?php echo formatRupiah($customer['total_spent']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($customer['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="6" class="text-center">Belum ada customer</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
