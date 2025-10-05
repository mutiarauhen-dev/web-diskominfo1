<?php
require_once 'koneksi.php';

// Ambil slug dari URL
 $slug = isset($_GET['slug']) ? $_GET['slug'] : '';

// Ambil data berita berdasarkan slug
 $sql = "SELECT * FROM berita WHERE slug = ?";
 $stmt = $conn->prepare($sql);
 $stmt->bind_param("s", $slug);
 $stmt->execute();
 $result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "<h1>Berita tidak ditemukan!</h1>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['judul']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="branding">
                <h1>Dinas Kominfo Kabupaten</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="#">Profil</a></li>
                    <li><a href="#">Layanan</a></li>
                    <li><a href="#">Kontak</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section id="berita-utama">
        <div class="container">
            <h1><?php echo htmlspecialchars($row['judul']); ?></h1>
            <p><small>Dipublikasikan pada: <?php echo date('d F Y H:i', strtotime($row['tanggal_dibuat'])); ?></small></p>
            <hr>
            <?php if (!empty($row['gambar'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['judul']); ?>" style="max-width: 100%; height: auto; margin-bottom: 20px;">
            <?php endif; ?>
            <div style="text-align: justify;">
                <?php echo nl2br(htmlspecialchars($row['isi'])); ?>
            </div>
            <br>
            <a href="index.php">&larr; Kembali ke Beranda</a>
        </div>
    </section>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Dinas Komunikasi dan Informatika. All Rights Reserved.</p>
    </footer>
</body>
</html>

<?php $conn->close(); ?>