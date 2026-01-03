-- ================================================
-- E-COMMERCE TOKO BAJU - DATABASE SETUP
-- ================================================
-- 
-- CARA IMPORT:
-- 1. Buka phpMyAdmin (http://localhost/phpmyadmin)
-- 2. Klik "New" untuk buat database baru
-- 3. Nama database: toko
-- 4. Collation: utf8mb4_unicode_ci
-- 5. Klik database "toko" yang baru dibuat
-- 6. Klik tab "Import"
-- 7. Pilih file init.sql ini
-- 8. Klik "Go"
--
-- ATAU gunakan command line:
-- mysql -u root -p < init.sql
--
-- Login Admin Default:
-- Email: admin@toko.com
-- Password: password
--
-- ================================================

-- Drop tables if exists (untuk re-import fresh)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- Buat tabel users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Buat tabel products
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    category VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Buat tabel orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_number INT NOT NULL DEFAULT 0,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'completed', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Buat tabel order_items
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Buat tabel cart
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert admin default
INSERT INTO users (name, email, password, role) VALUES 
('Admin', 'admin@toko.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- Password: password

-- Insert sample products
INSERT INTO products (name, description, price, stock, category) VALUES
('Kaos Distro Hitam', 'Kaos distro berkualitas tinggi dengan desain keren', 150000, 50, 'Kaos'),
('Kaos Anime Original', 'Kaos dengan desain anime eksklusif', 175000, 30, 'Kaos'),
('Kaos Fitness Cream', 'Kaos olahraga nyaman untuk fitness', 120000, 40, 'Kaos'),
('Kaos Japanese Style', 'Kaos dengan desain Jepang tradisional', 160000, 35, 'Kaos'),
('Kaos Casual Blue', 'Kaos santai warna biru untuk daily wear', 95000, 60, 'Kaos'),
('Kaos Typography', 'Kaos dengan desain typography modern', 135000, 45, 'Kaos'),
('Kaos Pocket Hitam', 'Kaos hitam dengan saku kecil', 110000, 55, 'Kaos'),
('Kaos Streetwear', 'Kaos streetwear dengan desain bold', 165000, 25, 'Kaos'),
('Kaos Distro Putih', 'Kaos distro putih clean design', 140000, 50, 'Kaos');
