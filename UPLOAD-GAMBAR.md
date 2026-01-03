# 📸 PANDUAN UPLOAD GAMBAR PRODUK

## Gambar Baju yang Tersedia

Berdasarkan gambar yang Anda berikan, berikut adalah 9 desain kaos yang dapat diupload:

### 1. **Kaos Distro Hitam - "GAMESLOVERRS"**
   - Desain depan: Text "GAMESLOVERRS"
   - Desain belakang: Karakter anime gaming
   - Warna: Hitam
   - Harga saran: Rp 150.000

### 2. **Kaos Fitness Cream**
   - Desain depan: Text "BE KIND TO FITNESS"  
   - Desain belakang: Logo fitness
   - Warna: Cream/Beige
   - Harga saran: Rp 120.000

### 3. **Kaos Grafis Hitam - Astronot**
   - Desain depan: Gambar astronot dalam lingkaran
   - Desain belakang: Grafis detil astronot
   - Warna: Hitam
   - Harga saran: Rp 165.000

### 4. **Kaos Japanese Style Hitam**
   - Desain depan: Karakter Jepang (勇気)
   - Desain belakang: Ilustrasi samurai/daruma
   - Warna: Hitam
   - Harga saran: Rp 160.000

### 5. **Kaos Casual Biru**
   - Desain: Logo kecil di dada
   - Warna: Biru muda
   - Style: Minimalis
   - Harga saran: Rp 95.000

### 6. **Kaos Typography "INC ONE SIX"**
   - Desain: Text typography
   - Warna: Cokelat/Mustard
   - Style: Modern streetwear
   - Harga saran: Rp 135.000

### 7. **Kaos Pocket Hitam**
   - Desain depan: Logo kecil di dada (saku)
   - Warna: Hitam
   - Style: Minimalis
   - Harga saran: Rp 110.000

### 8. **Kaos Streetwear - Oni Mask**
   - Desain depan: Logo kecil
   - Desain belakang: Topeng Oni Jepang dengan text
   - Warna: Hitam
   - Harga saran: Rp 165.000

### 9. **Kaos Distro Putih - "DISTRESS"**
   - Desain depan: Text "DISTRESS"
   - Desain belakang: Ilustrasi karakter
   - Warna: Putih
   - Harga saran: Rp 140.000

---

## 📤 CARA UPLOAD GAMBAR

### Metode 1: Via Admin Panel (RECOMMENDED)

1. **Login sebagai Admin**
   ```
   URL: http://localhost/e-comers/login.php
   Email: admin@toko.com
   Password: password
   ```

2. **Buka Menu Kelola Produk**
   - Klik "Kelola Produk" dari dashboard
   - atau akses: http://localhost/e-comers/admin/products.php

3. **Edit Produk yang Sudah Ada**
   - Database sudah terisi dengan 9 produk sample
   - Klik tombol "Edit" (ikon pensil) pada produk
   - Scroll ke bagian "Gambar Produk Baru"
   - Klik "Choose File" dan pilih gambar yang sesuai
   - Klik "Update Produk"

4. **Atau Tambah Produk Baru**
   - Klik "Tambah Produk"
   - Isi semua data produk
   - Upload gambar
   - Klik "Simpan Produk"

### Metode 2: Upload Manual (Advanced)

1. **Siapkan Gambar**
   - Format: JPG, PNG, atau GIF
   - Ukuran optimal: 800x800px
   - Max size: 5MB
   - Rename file dengan nama yang jelas

2. **Upload ke Folder**
   - Buka folder: `c:\laragon\www\e-comers\assets\images\products\`
   - Copy paste file gambar ke folder tersebut

3. **Update Database**
   - Buka phpMyAdmin
   - Pilih database `toko`
   - Pilih tabel `products`
   - Edit record produk
   - Isi kolom `image` dengan nama file gambar
   - Contoh: `kaos_distro_hitam.jpg`

---

## 🎨 TIPS GAMBAR PRODUK

### Ukuran & Format
- **Ukuran optimal**: 800x800px (square/persegi)
- **Format**: JPG (recommended), PNG, GIF
- **Max size**: 5MB
- **Aspect ratio**: 1:1 (persegi)

### Kualitas
- Gunakan gambar dengan resolusi tinggi
- Background sebaiknya putih atau transparan
- Lighting yang baik
- Produk terlihat jelas

### Penamaan File
```
Format: kategori_deskripsi_warna.jpg

Contoh yang baik:
✓ kaos_distro_hitam.jpg
✓ kaos_fitness_cream.jpg
✓ kaos_japanese_hitam.jpg

Hindari:
✗ IMG001.jpg
✗ foto baju.jpg (spasi)
✗ DSC_0001.JPG
```

---

## 🗂️ MAPPING GAMBAR KE PRODUK

Berikut saran mapping gambar ke produk yang sudah ada di database:

| No | Nama Produk (Database) | File Gambar Saran | Keterangan |
|----|----------------------|------------------|------------|
| 1  | Kaos Distro Hitam | kaos_gamesloverrs.jpg | Kaos hitam depan-belakang |
| 2  | Kaos Anime Original | kaos_gamesloverrs_back.jpg | Bisa pakai gambar belakang |
| 3  | Kaos Fitness Cream | kaos_fitness_cream.jpg | Kaos cream fitness |
| 4  | Kaos Japanese Style | kaos_japanese_hitam.jpg | Kaos dengan karakter Jepang |
| 5  | Kaos Casual Blue | kaos_casual_biru.jpg | Kaos biru polos |
| 6  | Kaos Typography | kaos_inc_one_six.jpg | Kaos cokelat typography |
| 7  | Kaos Pocket Hitam | kaos_pocket_hitam.jpg | Kaos hitam dengan logo |
| 8  | Kaos Streetwear | kaos_oni_mask.jpg | Kaos dengan topeng Oni |
| 9  | Kaos Distro Putih | kaos_distress_putih.jpg | Kaos putih distress |

---

## ⚠️ TROUBLESHOOTING

### Gambar tidak muncul setelah upload
**Solusi:**
1. Cek folder `assets/images/products/` apakah file ada
2. Cek nama file di database cocok dengan file fisik
3. Refresh browser (Ctrl + F5)
4. Clear cache browser

### Error saat upload "Failed to upload"
**Solusi:**
1. Cek ukuran file tidak lebih dari 5MB
2. Pastikan format file adalah JPG/PNG/GIF
3. Cek permission folder (777)
4. Cek php.ini: `upload_max_filesize` dan `post_max_size`

### Gambar terdistorsi/tidak proporsional
**Solusi:**
1. Resize gambar ke ukuran 800x800px sebelum upload
2. Gunakan aspect ratio 1:1 (square)
3. Crop gambar agar fokus ke produk

---

## 🎯 CHECKLIST UPLOAD

Sebelum upload, pastikan:

- [ ] Gambar sudah di-resize ke 800x800px
- [ ] Format file: JPG/PNG/GIF
- [ ] Ukuran file < 5MB
- [ ] Nama file jelas dan tanpa spasi
- [ ] Background bersih
- [ ] Produk terlihat jelas
- [ ] Login sebagai admin
- [ ] Edit produk yang sesuai
- [ ] Test tampilan di frontend

---

## 📝 NOTES

1. Setiap produk bisa punya 1 gambar utama
2. Gambar lama otomatis terhapus saat upload gambar baru
3. Produk tanpa gambar akan menampilkan placeholder
4. Semua gambar tersimpan di: `assets/images/products/`

---

Selamat mengupload! 🎉

Jika ada pertanyaan, buka file PANDUAN.md atau MULAI-DISINI.txt
