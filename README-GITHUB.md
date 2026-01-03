# 🛍️ E-Commerce Toko Baju - PHP Native

Website e-commerce untuk jual beli baju dengan fitur lengkap, dibuat menggunakan PHP Native (tanpa framework).

## 📋 Fitur

### Admin (Penjual):
- ✅ Dashboard dengan statistik lengkap
- ✅ CRUD Produk (Create, Read, Update, Delete)
- ✅ Upload gambar produk
- ✅ Kelola pesanan & update status
- ✅ Lihat data customer

### Customer (Pembeli):
- ✅ Register & Login
- ✅ Browse & Search produk
- ✅ Filter berdasarkan kategori
- ✅ Keranjang belanja
- ✅ Checkout & tracking pesanan
- ✅ Gratis ongkir min. Rp 200.000
- ✅ Order number per user dengan auto re-use

## 🎨 Teknologi

- PHP Native (No Framework)
- MySQL Database
- HTML5 & CSS3
- JavaScript (Vanilla)
- Font Awesome Icons
- Google Fonts (Poppins)

## 🚀 Cara Install

### 1. Clone Repository
```bash
git clone https://github.com/YOUR_USERNAME/e-comers.git
cd e-comers
```

### 2. Setup di Laragon/XAMPP
- Copy folder `e-comers` ke `C:\laragon\www\` (Laragon) atau `C:\xampp\htdocs\` (XAMPP)
- Start Apache & MySQL

### 3. Setup Database

**PENTING:** Buat database terlebih dahulu!

Buka phpMyAdmin dan jalankan SQL ini:

```sql
CREATE DATABASE toko CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE toko;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    category VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    order_number INT NOT NULL DEFAULT 0,
    total_amount DECIMAL(10,2) NOT NULL,
    shipping_address TEXT NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order items table
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Cart table
CREATE TABLE cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insert admin user
INSERT INTO users (name, email, password, role) 
VALUES ('Admin', 'admin@toko.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample products
INSERT INTO products (name, description, price, stock, category) VALUES
('Kaos Polos Hitam', 'Kaos polos warna hitam, bahan cotton combed 30s', 75000, 50, 'Kaos'),
('Kaos Polos Putih', 'Kaos polos warna putih, bahan cotton combed 30s', 75000, 50, 'Kaos'),
('Kemeja Flanel Kotak', 'Kemeja flanel motif kotak-kotak', 150000, 30, 'Kemeja'),
('Jaket Hoodie Navy', 'Jaket hoodie warna navy, bahan fleece tebal', 200000, 20, 'Jaket'),
('Hoodie Zipper Grey', 'Hoodie dengan zipper warna grey', 180000, 25, 'Hoodie');
```

### 4. Konfigurasi Database (Opsional)

Jika menggunakan database dengan kredensial berbeda, edit file `config/database.php`:

```php
$host = 'localhost';
$username = 'root';
$password = ''; // Ganti jika ada password
$database = 'toko';
```

### 5. Akses Website

- **Frontend (Customer):** `http://localhost/e-comers`
- **Admin Panel:** `http://localhost/e-comers/login.php`

### Default Login Admin:
```
Email: admin@toko.com
Password: password
```

## 📱 Responsive Design

Website sudah responsive untuk:
- 📱 Mobile
- 📱 Tablet  
- 💻 Desktop

## 🔒 Keamanan

- ✅ Password di-hash dengan bcrypt
- ✅ SQL Injection protection (Prepared Statements)
- ✅ XSS protection (htmlspecialchars)
- ✅ File upload validation
- ✅ Session management

## 📁 Struktur Folder

```
e-comers/
├── admin/              # Admin panel
├── assets/             # CSS, JS, Images
├── config/             # Database & helpers
├── includes/           # Header & footer
├── index.php           # Homepage
├── login.php           # Login page
├── register.php        # Register page
├── products.php        # Product listing
├── product-detail.php  # Product detail
├── cart.php            # Shopping cart
├── checkout.php        # Checkout
├── orders.php          # Order history
└── order-detail.php    # Order detail
```

## 🐛 Troubleshooting

### Error "Cannot connect to database"
- Pastikan MySQL running
- Cek kredensial di `config/database.php`
- Pastikan database `toko` sudah dibuat

### Gambar produk tidak muncul
- Cek folder `assets/images/products/` ada dan writable
- Upload ulang gambar melalui admin panel

### Login tidak bisa
- Pastikan sudah insert user admin di database
- Cek password hash sudah benar

## 📝 Notes

- Setelah clone, hapus file `migrate-order-number.php` jika ada
- Untuk production, ganti password admin default
- Backup database secara berkala

## 👨‍💻 Developer

Made with ❤️ using PHP Native

---

**Happy Coding!** 🎉
