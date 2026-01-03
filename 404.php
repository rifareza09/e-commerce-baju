<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/helpers.php';

$title = '404 - Halaman Tidak Ditemukan';
include __DIR__ . '/includes/header.php';
?>

<section class="error-page">
    <div class="container">
        <div class="error-content">
            <h1 class="error-code">404</h1>
            <h2>Halaman Tidak Ditemukan</h2>
            <p>Maaf, halaman yang Anda cari tidak ditemukan.</p>
            <a href="<?php echo baseUrl(); ?>" class="btn btn-primary">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</section>

<style>
.error-page {
    min-height: calc(100vh - 300px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 4rem 0;
}

.error-content {
    text-align: center;
}

.error-code {
    font-size: 8rem;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
}

.error-content h2 {
    font-size: 2rem;
    color: var(--dark-color);
    margin-bottom: 1rem;
}

.error-content p {
    font-size: 1.2rem;
    color: var(--secondary-color);
    margin-bottom: 2rem;
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
