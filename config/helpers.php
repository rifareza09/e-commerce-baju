<?php
// Helper functions

// Format harga ke Rupiah
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Sanitize input
function clean($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Upload gambar
function uploadImage($file, $folder = 'products') {
    $target_dir = __DIR__ . "/../assets/images/" . $folder . "/";
    
    // Buat folder jika belum ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Cek apakah file adalah gambar
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return ['success' => false, 'message' => 'File bukan gambar'];
    }
    
    // Cek ukuran file (max 5MB)
    if ($file["size"] > 5000000) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar (max 5MB)'];
    }
    
    // Allowed file types
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($file_extension, $allowed)) {
        return ['success' => false, 'message' => 'Format file tidak diizinkan'];
    }
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['success' => true, 'filename' => $new_filename];
    } else {
        return ['success' => false, 'message' => 'Gagal upload gambar'];
    }
}

// Delete image
function deleteImage($filename, $folder = 'products') {
    $file_path = __DIR__ . "/../assets/images/" . $folder . "/" . $filename;
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

// Base URL
function baseUrl($path = '') {
    return '/e-comers/' . $path;
}
?>
