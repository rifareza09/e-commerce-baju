# 📦 Panduan Push ke GitHub

## 1️⃣ Install Git (Jika Belum Ada)

Download dan install Git dari:
👉 https://git-scm.com/download/win

Pilih semua default options saat install.

## 2️⃣ Setup Git (First Time)

Buka **Git Bash** atau **PowerShell** dan jalankan:

```bash
git config --global user.name "Nama Anda"
git config --global user.email "email@anda.com"
```

## 3️⃣ Buat Repository di GitHub

1. Buka https://github.com
2. Login ke akun GitHub Anda
3. Klik tombol **"New"** atau **"+"** (pojok kanan atas)
4. Isi:
   - Repository name: `e-comers` atau `toko-baju-php`
   - Description: "E-commerce website menggunakan PHP Native"
   - Pilih: **Public** (agar teman bisa clone) atau **Private**
   - **JANGAN** centang "Add a README file" (sudah ada)
5. Klik **"Create repository"**

## 4️⃣ Push Project ke GitHub

Buka **PowerShell** atau **Git Bash**, lalu jalankan:

```bash
# 1. Masuk ke folder project
cd C:\laragon\www\e-comers

# 2. Initialize Git
git init

# 3. Add semua file
git add .

# 4. Commit pertama
git commit -m "Initial commit: E-commerce PHP Native"

# 5. Rename branch ke main (opsional tapi recommended)
git branch -M main

# 6. Tambahkan remote GitHub (GANTI dengan URL repo Anda!)
git remote add origin https://github.com/USERNAME_ANDA/NAMA_REPO.git

# 7. Push ke GitHub
git push -u origin main
```

**⚠️ PENTING:** Ganti `USERNAME_ANDA` dan `NAMA_REPO` dengan username GitHub dan nama repository Anda!

Contoh:
```bash
git remote add origin https://github.com/johndoe/e-comers.git
```

## 5️⃣ Clone di Laptop Teman

Di laptop teman, buka **PowerShell** atau **Git Bash**:

```bash
# 1. Clone repository
git clone https://github.com/USERNAME_ANDA/NAMA_REPO.git

# 2. Masuk ke folder
cd NAMA_REPO

# 3. Copy ke Laragon/XAMPP
# Laragon: C:\laragon\www\
# XAMPP: C:\xampp\htdocs\
```

## 6️⃣ Setup di Laptop Teman

Setelah clone, teman Anda perlu:

1. **Install Laragon/XAMPP** (jika belum)
2. **Start Apache & MySQL**
3. **Buat database** di phpMyAdmin:
   - Nama database: `toko`
   - Jalankan SQL dari file `README-GITHUB.md`
4. **Akses website:**
   - Frontend: `http://localhost/NAMA_FOLDER`
   - Admin: `http://localhost/NAMA_FOLDER/login.php`

## 📝 Tips

### Jika Ada Update Code:

```bash
# Di laptop Anda (yang original)
git add .
git commit -m "Update: deskripsi perubahan"
git push

# Di laptop teman (untuk ambil update)
git pull
```

### Jika Ada Conflict:

```bash
git stash        # Simpan perubahan lokal
git pull         # Ambil update
git stash pop    # Kembalikan perubahan lokal
```

### File yang TIDAK di-push (sudah di .gitignore):

- ❌ Gambar produk yang di-upload (di `assets/images/products/`)
- ❌ File migrasi temporary
- ❌ File backup

Jadi setelah clone, teman Anda perlu upload gambar produk sendiri via admin panel.

## 🔐 Keamanan

**PERINGATAN:** File `config/database.php` berisi kredensial database!

Untuk production:
1. Jangan push kredensial yang sensitif
2. Gunakan environment variables (.env file)
3. Tambahkan `.env` ke `.gitignore`

Tapi untuk development/testing dengan teman, ini tidak masalah karena kredensial default (`root` tanpa password).

## ❓ Troubleshooting

### "git is not recognized"
→ Install Git dari https://git-scm.com/download/win

### "Permission denied (publickey)"
→ Gunakan HTTPS URL, bukan SSH

### "failed to push"
→ Pastikan sudah `git pull` dulu jika ada perubahan di GitHub

---

**Semoga berhasil! 🚀**
