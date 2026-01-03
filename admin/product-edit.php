<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helpers.php';

requireAdmin();

if (!isset($_GET['id'])) {
    header('Location: ' . baseUrl('admin/products.php'));
    exit();
}

$product_id = intval($_GET['id']);
$conn = getConnection();

$error = '';
$success = '';

// Ambil data produk
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ' . baseUrl('admin/products.php'));
    exit();
}

$product = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($_POST['name']);
    $description = clean($_POST['description']);
    $price = clean($_POST['price']);
    $stock = clean($_POST['stock']);
    $category = clean($_POST['category']);
    
    if (empty($name) || empty($price) || empty($stock) || empty($category)) {
        $error = 'Nama, harga, stok, dan kategori harus diisi';
    } else {
        $image_name = $product['image'];
        
        // Upload gambar baru jika ada
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_result = uploadImage($_FILES['image']);
            if ($upload_result['success']) {
                // Hapus gambar lama
                if (!empty($product['image'])) {
                    deleteImage($product['image']);
                }
                $image_name = $upload_result['filename'];
            } else {
                $error = $upload_result['message'];
            }
        }
        
        if (empty($error)) {
            $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, image = ?, category = ? WHERE id = ?");
            $stmt->bind_param("ssdissi", $name, $description, $price, $stock, $image_name, $category, $product_id);
            
            if ($stmt->execute()) {
                $success = 'Produk berhasil diupdate';
                header('refresh:2;url=' . baseUrl('admin/products.php'));
            } else {
                $error = 'Terjadi kesalahan, silakan coba lagi';
            }
            
            $stmt->close();
        }
    }
}

closeConnection($conn);

$title = 'Edit Produk';
include __DIR__ . '/../includes/header.php';
?>

<section class="admin-section">
    <div class="container">
        <div class="admin-header">
            <h1>Edit Produk</h1>
            <a href="<?php echo baseUrl('admin/products.php'); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        
        <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="form-wrapper">
            <form method="POST" action="" enctype="multipart/form-data" class="admin-form">
                <div class="form-group">
                    <label for="name">Nama Produk</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" class="form-control" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Harga (Rp)</label>
                        <input type="number" id="price" name="price" class="form-control" value="<?php echo $product['price']; ?>" required min="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="stock">Stok</label>
                        <input type="number" id="stock" name="stock" class="form-control" value="<?php echo $product['stock']; ?>" required min="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="category">Kategori</label>
                    <select id="category" name="category" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Kaos" <?php echo $product['category'] === 'Kaos' ? 'selected' : ''; ?>>Kaos</option>
                        <option value="Kemeja" <?php echo $product['category'] === 'Kemeja' ? 'selected' : ''; ?>>Kemeja</option>
                        <option value="Jaket" <?php echo $product['category'] === 'Jaket' ? 'selected' : ''; ?>>Jaket</option>
                        <option value="Hoodie" <?php echo $product['category'] === 'Hoodie' ? 'selected' : ''; ?>>Hoodie</option>
                    </select>
                </div>
                
                <?php if (!empty($product['image'])): ?>
                <div class="form-group">
                    <label>Gambar Saat Ini</label>
                    <div class="current-image">
                        <img src="<?php echo baseUrl('assets/images/products/' . $product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="image">Gambar Produk Baru (Opsional)</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    <small class="form-text">Format: JPG, PNG, GIF (Max 5MB)</small>
                </div>
                
                <input type="submit" value="💾 Update Produk" class="btn btn-primary">
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
