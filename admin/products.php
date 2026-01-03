<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helpers.php';

requireAdmin();

$title = 'Kelola Produk';
include __DIR__ . '/../includes/header.php';

$conn = getConnection();
$query = "SELECT * FROM products ORDER BY id ASC";
$result = $conn->query($query);
$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
closeConnection($conn);
?>

<section class="admin-section">
    <div class="container">
        <div class="admin-header">
            <h1>Kelola Produk</h1>
            <a href="<?php echo baseUrl('admin/product-add.php'); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td>
                            <?php if (!empty($product['image'])): ?>
                                <img src="<?php echo baseUrl('assets/images/products/' . $product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="table-img">
                            <?php else: ?>
                                <img src="<?php echo baseUrl('assets/images/placeholder.svg'); ?>" alt="No Image" class="table-img">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                        <td><?php echo formatRupiah($product['price']); ?></td>
                        <td><?php echo $product['stock']; ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo baseUrl('admin/product-edit.php?id=' . $product['id']); ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?php echo baseUrl('admin/product-delete.php?id=' . $product['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="7" class="text-center">Belum ada produk</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
