# 📦 Setup Database untuk Teman Anda

File SQL sudah siap diimport! Teman Anda tinggal ikuti langkah berikut:

## ✅ Cara 1: Import via phpMyAdmin (MUDAH)

1. **Start Laragon/XAMPP**
   - Klik "Start All" di Laragon
   - Atau start Apache & MySQL di XAMPP

2. **Buka phpMyAdmin**
   - URL: http://localhost/phpmyadmin
   - Login biasanya: username `root`, password kosong

3. **Buat Database Baru**
   - Klik tab **"Databases"**
   - Database name: `toko`
   - Collation: `utf8mb4_unicode_ci`
   - Klik **"Create"**

4. **Import SQL File**
   - Klik database **"toko"** yang baru dibuat (di sidebar kiri)
   - Klik tab **"Import"**
   - Klik tombol **"Choose File"**
   - Pilih file: `database/init.sql`
   - Scroll ke bawah, klik **"Go"**
   - Tunggu sampai muncul "Import has been successfully finished"

5. **Selesai!** ✅
   - Refresh halaman untuk melihat 5 tabel yang terbuat:
     - users
     - products
     - orders
     - order_items
     - cart

## ✅ Cara 2: Import via Command Line (ADVANCED)

Buka **PowerShell** atau **CMD**, lalu:

```bash
# Masuk ke folder database
cd path\to\e-comers\database

# Import SQL
mysql -u root -p toko < init.sql
# (Tekan Enter jika tidak ada password)
```

## 🚀 Akses Website

Setelah import berhasil:

### Frontend (Customer):
```
http://localhost/e-comers
```

### Login Admin:
```
URL: http://localhost/e-comers/login.php
Email: admin@toko.com
Password: password
```

### Register Customer:
```
http://localhost/e-comers/register.php
```

## 📊 Yang Sudah Ada di Database

### ✅ Admin User
- Email: admin@toko.com
- Password: password
- Role: admin

### ✅ Sample Products (9 produk kaos)
- Kaos Distro Hitam - Rp 150.000
- Kaos Anime Original - Rp 175.000
- Kaos Fitness Cream - Rp 120.000
- Kaos Japanese Style - Rp 160.000
- Kaos Casual Blue - Rp 95.000
- Kaos Typography - Rp 135.000
- Kaos Pocket Hitam - Rp 110.000
- Kaos Streetwear - Rp 165.000
- Kaos Distro Putih - Rp 140.000

## 🎨 Upload Gambar Produk

Setelah login sebagai admin:

1. Klik menu **"Dashboard Admin"**
2. Pilih **"Kelola Produk"**
3. Klik tombol **"Edit"** (icon pensil) pada produk
4. Upload gambar produk
5. Klik **"Update Produk"**

Format gambar: JPG, PNG, GIF (Max 5MB)

## ❓ Troubleshooting

### "Error establishing database connection"
✅ Pastikan MySQL running di Laragon/XAMPP
✅ Pastikan nama database = `toko`
✅ Cek file `config/database.php` (seharusnya sudah benar)

### "Table doesn't exist"
✅ Import ulang file `database/init.sql`
✅ Pastikan import berhasil tanpa error

### "Login gagal"
✅ Email: `admin@toko.com` (huruf kecil semua)
✅ Password: `password` (huruf kecil semua)
✅ Pastikan tabel `users` ada dan berisi data admin

### "Gambar produk tidak muncul"
✅ Normal! Gambar perlu di-upload manual via admin panel
✅ Atau copy gambar ke folder `assets/images/products/`

## 📝 Notes

- Database menggunakan **order_number system** (nomor pesanan per user)
- Validasi stok otomatis (mencegah stok minus)
- Session-based authentication
- Password hashing dengan bcrypt

---

**Selamat mencoba! 🎉**

Jika ada error, screenshot dan tanyakan!
