# 🛍️ PANDUAN INSTALASI & PENGGUNAAN
## E-Commerce Toko Baju - PHP Native

---

## 📋 LANGKAH INSTALASI

### 1. Persiapan Laragon
✅ Pastikan Laragon sudah terinstal dan berjalan
✅ Start Apache & MySQL dari Laragon

### 2. Setup Database
1. Buka browser dan akses: `http://localhost/e-comers/setup.php`
2. Script akan otomatis:
   - Membuat semua tabel database
   - Membuat akun admin
   - Menambahkan sample produk
3. Jika berhasil, akan muncul pesan sukses

### 3. Akses Website
- **Frontend (Customer)**: `http://localhost/e-comers`
- **Admin Panel**: Login dengan kredensial dibawah

---

## 🔑 AKUN DEFAULT

### Admin/Penjual
```
Email: admin@toko.com
Password: password
```

### Customer
Silakan daftar akun baru melalui halaman Register

---

## 📱 FITUR WEBSITE

### 👤 UNTUK CUSTOMER (Pembeli)

1. **Registrasi & Login**
   - Daftar akun baru di halaman Register
   - Login dengan email dan password

2. **Browse Produk**
   - Lihat semua produk di halaman Products
   - Filter berdasarkan kategori
   - Cari produk dengan search

3. **Detail Produk**
   - Klik produk untuk lihat detail
   - Cek stok tersedia
   - Baca deskripsi produk

4. **Keranjang Belanja**
   - Tambah produk ke keranjang
   - Update jumlah produk
   - Hapus item dari keranjang

5. **Checkout**
   - Isi alamat pengiriman lengkap
   - Review pesanan
   - Konfirmasi pemesanan

6. **Pesanan Saya**
   - Lihat riwayat pesanan
   - Cek status pesanan (Pending/Diproses/Dikirim/Selesai)
   - Lihat detail setiap pesanan

---

### 👨‍💼 UNTUK ADMIN (Penjual)

1. **Dashboard**
   - Statistik total produk, pesanan, customer, pendapatan
   - Menu navigasi ke semua fitur admin

2. **Kelola Produk** (CRUD Lengkap)
   
   **CREATE - Tambah Produk:**
   - Klik "Tambah Produk"
   - Isi form:
     * Nama Produk
     * Deskripsi
     * Harga
     * Stok
     * Kategori (Kaos/Kemeja/Jaket/Hoodie)
     * Upload gambar (JPG/PNG/GIF, max 5MB)
   - Klik "Simpan Produk"

   **READ - Lihat Produk:**
   - Tabel menampilkan semua produk
   - Info: ID, Gambar, Nama, Kategori, Harga, Stok

   **UPDATE - Edit Produk:**
   - Klik tombol Edit (ikon pensil)
   - Ubah data yang diperlukan
   - Upload gambar baru (opsional)
   - Klik "Update Produk"

   **DELETE - Hapus Produk:**
   - Klik tombol Delete (ikon sampah)
   - Konfirmasi penghapusan
   - Produk dan gambarnya akan terhapus

3. **Kelola Pesanan**
   - Lihat semua pesanan masuk
   - Update status pesanan:
     * Pending → Pesanan baru masuk
     * Processing → Sedang diproses
     * Shipped → Sudah dikirim
     * Completed → Selesai
     * Cancelled → Dibatalkan
   - Klik "Detail" untuk info lengkap pesanan

4. **Data Customer**
   - Lihat semua customer terdaftar
   - Info: Nama, Email, Total Pesanan, Total Belanja

---

## 📁 STRUKTUR PROJECT

```
e-comers/
├── admin/                  # Panel Admin
│   ├── dashboard.php      # Dashboard & statistik
│   ├── products.php       # Daftar produk
│   ├── product-add.php    # Tambah produk
│   ├── product-edit.php   # Edit produk
│   ├── product-delete.php # Hapus produk
│   ├── orders.php         # Kelola pesanan
│   ├── order-detail.php   # Detail pesanan
│   └── customers.php      # Data customer
│
├── assets/                # Asset Statis
│   ├── css/
│   │   └── style.css     # Styling lengkap & responsive
│   ├── js/
│   │   └── script.js     # JavaScript interaktif
│   └── images/
│       ├── products/     # Folder upload gambar produk
│       └── placeholder.svg # Gambar default
│
├── config/               # Konfigurasi
│   ├── database.php     # Koneksi database
│   ├── session.php      # Session & authentication
│   └── helpers.php      # Helper functions
│
├── database/
│   └── init.sql         # SQL database schema
│
├── includes/            # Template Parts
│   ├── header.php      # Header & navbar
│   └── footer.php      # Footer
│
├── index.php           # Homepage
├── products.php        # Halaman produk
├── product-detail.php  # Detail produk
├── cart.php           # Keranjang belanja
├── checkout.php       # Halaman checkout
├── orders.php         # Pesanan customer
├── order-detail.php   # Detail pesanan customer
├── login.php          # Login
├── register.php       # Register
├── logout.php         # Logout
├── setup.php          # Setup database (run once)
├── 404.php           # Error 404
├── .htaccess         # Apache config
└── README.md         # Dokumentasi
```

---

## 🎨 KUSTOMISASI

### Mengubah Warna Tema
Edit `assets/css/style.css` pada bagian `:root`:
```css
:root {
    --primary-color: #2563eb;    /* Biru - Warna utama */
    --secondary-color: #64748b;  /* Abu - Warna sekunder */
    --success-color: #10b981;    /* Hijau - Success */
    --danger-color: #ef4444;     /* Merah - Danger */
    --warning-color: #f59e0b;    /* Kuning - Warning */
}
```

### Menambah Kategori Produk
Edit file `admin/product-add.php` dan `admin/product-edit.php`:
```html
<option value="Kategori Baru">Kategori Baru</option>
```

### Mengubah Logo
Edit `includes/header.php`:
```php
<a href="..." class="logo">
    <i class="fas fa-tshirt"></i> Nama Toko Anda
</a>
```

---

## 🔒 KEAMANAN

✅ Password di-hash dengan `password_hash()`
✅ SQL Injection prevention (prepared statements)
✅ XSS protection (`htmlspecialchars()`)
✅ File upload validation
✅ Session management yang aman
✅ Config files protected (.htaccess)

---

## 📱 RESPONSIVE DESIGN

Website sudah responsive dan optimal di:
- 📱 Mobile (320px - 640px)
- 📱 Tablet (641px - 968px)
- 💻 Desktop (969px ke atas)

Fitur responsive:
- Hamburger menu untuk mobile
- Grid layout yang adaptif
- Touch-friendly buttons
- Optimized images

---

## 🚀 TIPS PENGGUNAAN

### Untuk Admin:
1. **Upload Gambar Produk:**
   - Gunakan gambar berkualitas baik
   - Ukuran optimal: 800x800px
   - Format: JPG, PNG, atau GIF
   - Max size: 5MB

2. **Kelola Stok:**
   - Update stok produk secara berkala
   - Stok otomatis berkurang saat ada pemesanan
   - Badge "Stok Terbatas" muncul jika stok < 10

3. **Update Status Pesanan:**
   - Update status pesanan secara real-time
   - Customer bisa tracking status pesanan mereka

### Untuk Customer:
1. **Gratis Ongkir:**
   - Belanja minimal Rp 200.000 dapat gratis ongkir
   - Ongkir normal: Rp 20.000

2. **Checkout:**
   - Isi alamat lengkap termasuk:
     * Nama jalan & nomor rumah
     * RT/RW
     * Kelurahan & Kecamatan
     * Kota & Kode Pos

---

## 🐛 TROUBLESHOOTING

### Database connection error
```
Solusi:
1. Pastikan MySQL di Laragon running
2. Cek database 'toko' sudah dibuat
3. Cek config/database.php (host, user, password)
```

### Gambar tidak muncul
```
Solusi:
1. Pastikan folder assets/images/products/ ada
2. Cek permission folder (chmod 777)
3. Upload ulang gambar melalui admin
```

### Error 404
```
Solusi:
1. Pastikan .htaccess sudah ada
2. Enable mod_rewrite di Apache
3. Restart Laragon
```

### Session error
```
Solusi:
1. Clear browser cookies
2. Restart browser
3. Logout dan login ulang
```

---

## 📞 SUPPORT

Butuh bantuan? Hubungi:
- 📧 Email: info@tokokaos.com
- 📱 WhatsApp: +62 812-3456-7890

---

## 📝 CATATAN PENTING

⚠️ **Setelah setup berhasil:**
1. Hapus atau rename file `setup.php` untuk keamanan
2. Ubah password admin default
3. Backup database secara berkala

⚠️ **Untuk Production:**
1. Ganti semua password default
2. Enable HTTPS
3. Update email contact di footer
4. Konfigurasi error reporting
5. Optimize database

---

## ✨ FITUR UNGGULAN

- ✅ CRUD Lengkap & Professional
- ✅ Responsive Design (Mobile, Tablet, Desktop)
- ✅ User-friendly Interface
- ✅ Secure Authentication
- ✅ Real-time Cart Management
- ✅ Order Tracking System
- ✅ Image Upload & Management
- ✅ Search & Filter Products
- ✅ Clean & Modern Design
- ✅ Easy to Customize

---

## 🎉 SELAMAT!

Website E-Commerce Anda sudah siap digunakan!

**Next Steps:**
1. ✅ Upload gambar produk yang sesuai
2. ✅ Tambah produk baru
3. ✅ Test pemesanan end-to-end
4. ✅ Kustomisasi sesuai brand Anda
5. ✅ Launch dan promosikan!

---

© 2025 TokoKaos - E-Commerce Website
Made with ❤️ using PHP Native
