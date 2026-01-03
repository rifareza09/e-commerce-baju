<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/helpers.php';

requireAdmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($_POST['name']);
    $description = clean($_POST['description']);
    $price = clean($_POST['price']);
    $stock = clean($_POST['stock']);
    $category = clean($_POST['category']);
    
    if (empty($name) || empty($price) || empty($stock) || empty($category)) {
        $error = 'Nama, harga, stok, dan kategori harus diisi';
    } else {
        $image_name = '';
        
        // Upload gambar jika ada
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_result = uploadImage($_FILES['image']);
            if ($upload_result['success']) {
                $image_name = $upload_result['filename'];
            } else {
                $error = $upload_result['message'];
            }
        }
        
        if (empty($error)) {
            $conn = getConnection();
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image, category) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdiss", $name, $description, $price, $stock, $image_name, $category);
            
            if ($stmt->execute()) {
                $success = 'Produk berhasil ditambahkan';
                header('refresh:2;url=' . baseUrl('admin/products.php'));
            } else {
                $error = 'Terjadi kesalahan, silakan coba lagi';
            }
            
            $stmt->close();
            closeConnection($conn);
        }
    }
}

$title = 'Tambah Produk';
include __DIR__ . '/../includes/header.php';
?>

<section class="admin-section">
    <div class="container">
        <div class="admin-header">
            <h1>Tambah Produk</h1>
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
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" class="form-control" rows="4"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Harga (Rp)</label>
                        <input type="number" id="price" name="price" class="form-control" required min="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="stock">Stok</label>
                        <input type="number" id="stock" name="stock" class="form-control" required min="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="category">Kategori</label>
                    <select id="category" name="category" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Kaos">Kaos</option>
                        <option value="Kemeja">Kemeja</option>
                        <option value="Jaket">Jaket</option>
                        <option value="Hoodie">Hoodie</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="image">Gambar Produk</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    <small class="form-text">Format: JPG, PNG, GIF (Max 5MB)</small>
                </div>
                
                <input type="submit" value="💾 Simpan Produk" class="btn btn-primary">
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
