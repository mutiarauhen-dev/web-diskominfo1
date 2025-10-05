<?php
// ===================================================================
// AWAL: BAGIAN PHP (LOGIKA SERVER)
// Bagian ini akan dieksekusi pertama kali di server SEBELUM dikirim ke browser.
// Tugasnya: Menghubungkan database dan mengambil semua data yang dibutuhkan.
// ===================================================================

require_once 'koneksi.php';

// 1. Ambil data untuk SLIDER dari database
 $sql_slider = "SELECT * FROM slider ORDER BY urutan ASC";
 $result_slider = $conn->query($sql_slider);

// 2. Ambil data untuk BERITA dari database
 $sql_berita = "SELECT * FROM berita ORDER BY tanggal_dibuat DESC";
 $result_berita = $conn->query($sql_berita);

// ===================================================================
// AKHIR: BAGIAN PHP (LOGIKA SERVER)
// Sekarang, semua data sudah ada di dalam variabel $result_slider dan $result_berita.
// Selanjutnya, kita akan menggunakan data ini untuk membangun HTML.
// ===================================================================
?>

<!-- =================================================================== -->
<!-- AWAL: BAGIAN HTML (STRUKTUR HALAMAN YANG AKAN DITAMPILKAN BROWSER) -->
<!-- =================================================================== -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Diskominfo</title>
    <!-- Ini adalah link ke file CSS eksternal (Bootstrap) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    
    <!-- Ini adalah CSS internal, khusus untuk tampilan slider di halaman ini -->
    <style>
        .carousel-item { height: 60vh; background: no-repeat center center scroll; background-size: cover; }
        .carousel-caption { background-color: rgba(0, 0, 0, 0.5); padding: 20px; border-radius: 10px; }
        .carousel-item a { text-decoration: none; color: inherit; }
    </style>
</head>
<body>

    <!-- Bagian Header (Murni HTML) -->
    <header>
        <div class="container">
            <div class="branding"><h1>Diskominfo</h1></div>
            <nav>
                <ul>
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="profil.html">Profil</a></li>
                    <li><a href="#">Layanan</a></li>
                    <li><a href="#">Kontak</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- =================================================================== -->
    <!-- AWAL: SLIDER (GABUNGAN HTML DAN PHP) -->
    <!-- Di sini, PHP akan "mencetak" (echo) HTML berdasarkan data dari database. -->
    <!-- =================================================================== -->
    <?php if ($result_slider->num_rows > 0): ?>
    <div id="mainSlider" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php
            $slide_count = 0;
            $result_slider->data_seek(0); // Kembali ke awal data slider
            // Loop PHP: Mencetak HTML untuk setiap indikator (titik) slider
            while($row_slider = $result_slider->fetch_assoc()) {
                $active_class = ($slide_count == 0) ? 'active' : '';
                echo '<button type="button" data-bs-target="#mainSlider" data-bs-slide-to="' . $slide_count . '" class="' . $active_class . '" aria-label="Slide ' . ($slide_count + 1) . '"></button>';
                $slide_count++;
            }
            $result_slider->data_seek(0); // Kembali ke awal lagi untuk loop item slide
            ?>
        </div>
        <div class="carousel-inner">
            <?php
            $slide_count = 0;
            // Loop PHP: Mencetak HTML untuk setiap item slide (gambar dan judul)
            while($row_slider = $result_slider->fetch_assoc()) {
                $active_class = ($slide_count == 0) ? 'active' : '';
                
                if (!empty($row_slider['link'])) {
                    echo '<a href="' . htmlspecialchars($row_slider['link']) . '">'; // Cetak tag <a> jika ada link
                }

                echo '<div class="carousel-item ' . $active_class . '">'; // Cetak div untuk item slide
                echo '<img src="uploads/' . htmlspecialchars($row_slider['gambar']) . '" class="d-block w-100" alt="' . htmlspecialchars($row_slider['judul']) . '">'; // Cetak tag gambar
                echo '<div class="carousel-caption d-none d-md-block">';
                echo '<h2>' . htmlspecialchars($row_slider['judul']) . '</h2>'; // Cetak judul slide
                echo '</div>';
                echo '</div>';

                if (!empty($row_slider['link'])) {
                    echo '</a>'; // Tutup tag </a>
                }
                $slide_count++;
            }
            ?>
        </div>
        <!-- Tombol Navigasi Slider (Murni HTML) -->
        <button class="carousel-control-prev" type="button" data-bs-target="#mainSlider" data-bs-slide="prev">...</button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainSlider" data-bs-slide="next">...</button>
    </div>
    <?php endif; ?>
    <!-- =================================================================== -->
    <!-- AKHIR: SLIDER -->
    <!-- =================================================================== -->

    <!-- =================================================================== -->
    <!-- AWAL: DAFTAR BERITA (GABUNGAN HTML DAN PHP) -->
    <!-- Sama seperti slider, PHP akan mengulang data berita dan mencetaknya dalam bentuk card. -->
    <!-- =================================================================== -->
    <section id="berita-utama">
        <div class="container">
            <h2>Berita Terkini</h2>
            <?php if ($result_berita->num_rows > 0): ?>
                <?php while($row = $result_berita->fetch_assoc()): ?>
                    <!-- Untuk setiap berita di database, PHP akan mencetak struktur HTML card ini -->
                    <div class="card">
                        <?php if (!empty($row['gambar'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['judul']); ?>">
                        <?php endif; ?>
                        <div class="card-content">
                            <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
                            <p><small>Dipublikasikan pada: <?php echo date('d F Y', strtotime($row['tanggal_dibuat'])); ?></small></p>
                            <p><?php echo substr(htmlspecialchars($row['isi']), 0, 200) . '...'; ?></p>
                            <a href="detail_berita.php?slug=<?php echo htmlspecialchars($row['slug']); ?>">Baca Selengkapnya &rarr;</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Belum ada berita yang dipublikasikan.</p>
            <?php endif; ?>
        </div>
    </section>
    <!-- =================================================================== -->
    <!-- AKHIR: DAFTAR BERITA -->
    <!-- =================================================================== -->

    <!-- Bagian Footer (Murni HTML) -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Dinas Komunikasi dan Informatika. All Rights Reserved.</p>
    </footer>

    <!-- JavaScript Bootstrap (Murni HTML, dijalankan di browser) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Tutup koneksi database di akhir file
 $conn->close();
?>