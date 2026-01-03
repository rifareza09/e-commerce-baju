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

// Ambil data produk untuk hapus gambar
$stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    
    // Hapus gambar jika ada
    if (!empty($product['image'])) {
        deleteImage($product['image']);
    }
    
    // Hapus produk dari database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
}

$stmt->close();
closeConnection($conn);

header('Location: ' . baseUrl('admin/products.php'));
exit();
?>
