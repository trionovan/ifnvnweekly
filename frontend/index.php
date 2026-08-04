<?php
require "fungsi.php";

$username = $_SESSION["username"] ?? null;
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informatika 2026 | Portal Resmi</title>
    <!-- Font Google: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        }

        body {
            background-color: #f4f6f9;
            color: #1f2937;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Floating Navbar Light Theme */
        header {
            position: sticky;
            top: 20px;
            z-index: 50;
            padding: 0 20px;
            margin-bottom: 40px;
        }

        .navbar {
            max-width: 950px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid #e5e7eb;
            border-radius: 99px;
            padding: 12px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .brand {
            font-size: 18px;
            font-weight: 800;
            color: #4f46e5;
            letter-spacing: 0.5px;
        }

        .nav-links {
            display: flex;
            gap: 6px;
        }

        .nav-links a {
            color: #4b5563;
            text-decoration: none;
            padding: 8px 18px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 99px;
            transition: all 0.2s ease;
        }

        .nav-links a:hover, .nav-links a.active {
            color: #4f46e5;
            background: #eef2ff;
        }

        /* Hero Section Light */
        .hero {
            text-align: center;
            max-width: 800px;
            margin: 10px auto 45px auto;
            padding: 0 20px;
            animation: fadeInUp 0.6s ease;
        }

        .badge-tag {
            display: inline-block;
            padding: 6px 16px;
            background-color: #e0e7ff;
            color: #4338ca;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 18px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .hero h1 {
            font-size: 44px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 16px;
            color: #111827;
            letter-spacing: -1px;
        }

        .hero h1 span {
            color: #4f46e5;
        }

        .hero p {
            font-size: 16px;
            color: #6b7280;
            line-height: 1.6;
            max-width: 620px;
            margin: 0 auto;
        }

        /* Main Showcase Container */
        .main-container {
            max-width: 900px;
            margin: 0 auto 20px auto;
            padding: 0 20px;
            flex: 1;
        }

        .showcase-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 40px;
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 40px;
            align-items: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
            animation: fadeInUp 0.7s ease;
        }

        /* Profile Side (Kiri) */
        .profile-side {
            text-align: center;
        }

        .avatar-wrapper {
            position: relative;
            width: 140px;
            height: 140px;
            margin: 0 auto 16px auto;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #e0e7ff;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.18);
            transition: transform 0.25s ease;
        }

        .avatar-img:hover {
            transform: scale(1.04);
        }

        .profile-side h3 {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .role-pill {
            display: inline-block;
            background: #f3f4f6;
            color: #4b5563;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 99px;
            border: 1px solid #e5e7eb;
        }

        /* Content Side (Kanan) */
        .content-side {
            border-left: 1px solid #e5e7eb;
            padding-left: 40px;
        }

        .quote-badge {
            font-size: 12px;
            font-weight: 700;
            color: #4f46e5;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
            display: block;
        }

        .speech-text {
            font-size: 15px;
            line-height: 1.8;
            color: #4b5563;
            font-style: italic;
            margin-bottom: 28px;
        }

        .action-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #4338ca;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background-color: #e5e7eb;
            color: #111827;
            transform: translateY(-2px);
        }

        /* Feature Section */
        .features {
            max-width: 900px;
            margin: 50px auto 60px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .feature-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px;
            text-align: left;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(79, 70, 229, 0.1);
        }

        .feature-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #e0e7ff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 14px;
        }

        .feature-card h4 {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 6px;
        }

        .feature-card p {
            font-size: 13.5px;
            color: #6b7280;
            line-height: 1.6;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 24px;
            font-size: 13px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            background: #ffffff;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Mobile */
        @media (max-width: 768px) {
            .showcase-card {
                grid-template-columns: 1fr;
                gap: 24px;
                padding: 24px;
            }

            .content-side {
                border-left: none;
                border-top: 1px solid #e5e7eb;
                padding-left: 0;
                padding-top: 24px;
            }

            .hero h1 {
                font-size: 32px;
            }

            .navbar {
                flex-direction: column;
                gap: 12px;
                border-radius: 20px;
            }

            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Navbar Light -->
    <header>
        <div class="navbar">
            <div class="brand">INFORMATIKA 2026</div>
            <nav class="nav-links">
                <a href="index.php" class="<?= $current_page === 'index.php' ? 'active' : '' ?>">Home</a>
                <a href="profile.php" class="<?= $current_page === 'profile.php' ? 'active' : '' ?>">Profile</a>
                <a href="contact.php" class="<?= $current_page === 'contact.php' ? 'active' : '' ?>">Contact</a>
                <a href="mahasiswa.php" class="<?= $current_page === 'mahasiswa.php' ? 'active' : '' ?>">Mahasiswa</a>
                <?php if(!$username): ?>
                    <a href="login.php">Login</a>
                <?php else: ?>
                    <a href="logout.php" onclick="return confirm('Yakin mau logout?');">Logout</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <span class="badge-tag">Portal Resmi Mahasiswa</span>
        <h1>Inovasi & Teknologi Untuk <span>Masa Depan</span></h1>
        <p>Platform terintegrasi Teknik Informatika. Mengelola data mahasiswa, sistem autentikasi aman, serta informasi angkatan secara terpusat.</p>
    </section>

    <!-- Main Content Showcase -->
    <main class="main-container">
        <div class="showcase-card">

            <!-- Kolom Kiri: Profil Ketua -->
            <div class="profile-side">
                <div class="avatar-wrapper">
                    <img src="../assets/Image/kaprodi.jpg" alt="kaprodi" class="avatar-img" onerror="this.src='https://via.placeholder.com/140?text=Andi';">
                </div>
                <h3>Almighty Lord Uncle</h3>
                <span class="role-pill">Student of Colleague</span>
            </div>

            <!-- Kolom Kanan: Sambutan -->
            <div class="content-side">
                <span class="quote-badge">// Sambutan Ketua Angkatan</span>
                <p class="speech-text">
                    "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Enim omnis praesentium quo maxime tenetur aliquid magni numquam iusto ipsum, in atque delectus quae. Minima facilis nam eos, fugiat quasi voluptates? H<sub>2</sub>O 2<sup>2</sup>"
                </p>

                <div class="action-group">
                    <a href="profile.php" class="btn btn-primary">Lihat Profile</a>
                    <a href="contact.php" class="btn btn-secondary">Hubungi Contact</a>
                    <a href="https://youtube.com" target="_blank" class="btn btn-secondary">Channel Youtube</a>
                </div>
            </div>

        </div>
    </main>

    <!-- Feature Highlights -->
    <section class="features">
        <div class="feature-card">
            <div class="feature-icon">🗂️</div>
            <h4>Data Terpusat</h4>
            <p>Seluruh data mahasiswa angkatan tersimpan rapi dan mudah diakses dalam satu portal.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔐</div>
            <h4>Login Aman dengan MFA</h4>
            <p>Autentikasi dua langkah pakai TOTP biar akun tetap aman dari akses yang gak sah.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📢</div>
            <h4>Info Angkatan</h4>
            <p>Update informasi terbaru seputar kegiatan dan pengumuman angkatan secara real-time.</p>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        &copy; 2026 Informatika UNIMUS &bull; Powered by PHP, MySQL & TOTP Auth
    </footer>

</body>
</html>