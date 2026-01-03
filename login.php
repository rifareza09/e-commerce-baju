<?php
session_start();

// Tampilkan error untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

$error = '';
$success = '';

// Proses login - GUNAKAN KODE YANG SUDAH TERBUKTI BERHASIL
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi';
    } else {
        // Koneksi database langsung
        $host = 'localhost';
        $username = 'root';
        $db_password = '';
        $database = 'toko';
        
        $conn = new mysqli($host, $username, $db_password, $database);
        
        if ($conn->connect_error) {
            $error = 'Koneksi database gagal. Pastikan MySQL running!';
        } else {
            // Query user
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                
                // Verifikasi password
                if (password_verify($password, $user['password'])) {
                    // Set session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    
                    // Redirect berdasarkan role dengan full URL
                    if ($user['role'] === 'admin') {
                        header('Location: http://localhost/e-comers/admin/dashboard.php');
                    } else {
                        header('Location: http://localhost/e-comers/index.php');
                    }
                    exit();
                } else {
                    $error = 'Email atau password salah';
                }
            } else {
                $error = 'Email atau password salah';
            }
            
            $stmt->close();
            $conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Baju Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-wrapper">
                <a href="index.php" class="logo">
                    <i class="fas fa-tshirt"></i> TokoKaos
                </a>
                
                <div class="nav-menu" id="navMenu">
                    <a href="index.php">Home</a>
                    <a href="products.php">Produk</a>
                    <a href="login.php" class="btn btn-outline">Login</a>
                    <a href="register.php" class="btn btn-primary">Register</a>
                </div>
                
                <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </nav>
?>

<section class="auth-section">
    <div class="container">
        <div class="auth-wrapper">
            <div class="auth-card">
                <h2>Login</h2>
                <p class="auth-subtitle">Masuk ke akun Anda</p>
                
                <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" id="loginForm">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    
                    <input type="submit" value="Login" class="btn btn-primary btn-block">
                </form>
                
                <p class="auth-footer">
                    Belum punya akun? <a href="register.php">Daftar di sini</a>
                </p>
            </div>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <p>&copy; 2024 TokoKaos. All rights reserved.</p>
    </div>
</footer>

<!-- JANGAN LOAD SCRIPT.JS - ADA KONFLIK -->
<script>
// Hamburger menu only
const hamburger = document.getElementById('hamburger');
const navMenu = document.getElementById('navMenu');

if (hamburger) {
    hamburger.addEventListener('click', function() {
        navMenu.classList.toggle('active');
    });
}
</script>
</body>
</html>
