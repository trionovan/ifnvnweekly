<?php
// Mengimpor file fungsi.php yang berisi helper function (seperti generateSecret, verifyTOTP, dll)
require "fungsi.php";

// Cek apakah ada session 'pending_setup_id', kalau gak ada (akses langsung/belum login) dilempar balik ke login.php
if (!isset($_SESSION["pending_setup_id"])) {
    header("Location: login.php");
    exit;
}

// Mengambil ID user dari session sementara
$id = $_SESSION["pending_setup_id"];

// Query untuk mengambil data detail user berdasarkan ID
$queryUser = "SELECT * FROM user WHERE id = $id";
$resultUser = mysqli_query($connection, $queryUser);
$user = mysqli_fetch_assoc($resultUser);

// Mengambil data username user, kalau gak ada fallback ke 'User'
$username = $user["username"] ?? 'User';

// Mengecek & membuat secret key MFA sementara jika belum ada di session
if (!isset($_SESSION["temp_mfa_secret"])) {
    $_SESSION["temp_mfa_secret"] = generateSecret(); // Memanggil fungsi pembuat secret key acak
}
$secret = $_SESSION["temp_mfa_secret"];

// Memanggil fungsi untuk generate URL QR Code dari secret key & username
$qrCodeUrl = getQRCodeUrl($secret, $username);

// Inisialisasi variabel penampung pesan error & success
$error = "";
$success = "";

// Cek apakah form input kode OTP sudah disubmit via metode POST
if (isset($_POST["code"])) {
    // Mengambil inputan kode OTP dari user & membersihkan spasi di awal/akhir
    $userCode = trim($_POST["code"]);

    // Memvalidasi apakah kode OTP yang diinput sesuai dengan secret key
    if (verifyTOTP($secret, $userCode)) {
        // Amankan string secret sebelum dimasukkan ke database untuk cegah SQL Injection
        $secretEsc = mysqli_real_escape_string($connection, $secret);
        
        // Query update untuk menyimpan secret key MFA dan mengaktifkan status MFA user (mfa_enabled = 1)
        $queryUpdate = "UPDATE user SET mfa_secret = '$secretEsc', mfa_enabled = 1 WHERE id = $id";
        
        // Eksekusi query update ke database
        if (mysqli_query($connection, $queryUpdate)) {
            // Hapus session setup sementara karena setup MFA sudah berhasil
            unset($_SESSION["temp_mfa_secret"]);
            unset($_SESSION["pending_setup_id"]);
            
            // Set session login resmi untuk menandakan user sudah terautentikasi penuh
            $_SESSION["login"] = true;
            $_SESSION["id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            // Set pesan sukses
            $success = "MFA berhasil diaktifkan!";
        } else {
            // Pesan error jika query database gagal
            $error = "Gagal mengupdate database.";
        }
    } else {
        // Pesan error jika kode TOTP salah, kadaluwarsa, atau jam HP client gak sinkron
        $error = "Kode salah atau expired. Coba scan ulang atau pastikan jam HP cocok.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup MFA | Informatika</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px 0;
        }

        .mfa-card {
            background: #ffffff;
            padding: 35px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        .mfa-card h1 {
            font-size: 22px;
            color: #333;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .mfa-card p.subtitle {
            font-size: 13px;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #dc2626;
            padding: 10px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 18px;
            border: 1px solid #fca5a5;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #059669;
            padding: 15px;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 18px;
            border: 1px solid #6ee7b7;
            font-weight: 600;
        }

        .alert-success a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #059669;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .qr-container {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 18px;
            border-radius: 10px;
            display: inline-block;
            margin-bottom: 16px;
        }

        .qr-container img {
            width: 170px;
            height: 170px;
            display: block;
            margin: 0 auto;
            border-radius: 6px;
        }

        .secret-box {
            background-color: #e0e7ff;
            color: #3730a3;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            display: inline-block;
            margin-top: 6px;
            word-break: break-all;
        }

        .manual-text {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .input-otp {
            width: 100%;
            padding: 12px;
            border: 1px solid #dcdfe6;
            border-radius: 8px;
            font-size: 20px;
            letter-spacing: 5px;
            text-align: center;
            font-weight: 700;
            color: #333;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .input-otp:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: #4f46e5;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-submit:hover {
            background-color: #4338ca;
        }
    </style>
</head>
<body>

    <div class="mfa-card">
        <h1>Setup MFA</h1>
        <!-- Menampilkan username yang sudah di-escape dengan htmlspecialchars untuk cegah XSS -->
        <p class="subtitle">Halo <strong><?php echo htmlspecialchars($username); ?></strong>, scan QR code di bawah ini menggunakan aplikasi <strong>Google Authenticator</strong></p>

        <!-- Menampilkan pesan alert error jika verifikasi OTP gagal -->
        <?php if ($error): ?>
            <div class="alert-error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Kondisi tampilan: jika sukses, tampilkan alert sukses + tombol redirect. Jika belum, tampilkan QR Code & Form -->
        <?php if ($success): ?>
            <div class="alert-success">
                <?php echo $success; ?>
                <br>
                <a href="mahasiswa.php">Lanjut ke Dashboard &rarr;</a>
            </div>
        <?php else: ?>
            <!-- Menampilkan gambar QR Code hasil eksekusi fungsi getQRCodeUrl() -->
            <div class="qr-container">
                <img src="<?php echo $qrCodeUrl; ?>" alt="QR Code MFA">
            </div>

            <!-- Menampilkan secret key text manual sebagai alternatif jika camera gagal scan -->
            <p class="manual-text">
                Terkendala scan? Masukkan kode manual ini:<br>
                <span class="secret-box"><?php echo $secret; ?></span>
            </p>

            <!-- Form input untuk memasukkan 6 digit kode OTP dari Google Authenticator -->
            <form action="" method="POST">
                <div class="form-group">
                    <input type="text" name="code" class="input-otp" maxlength="6" placeholder="000000" required autofocus autocomplete="off">
                </div>
                <button type="submit" class="btn-submit">Aktifkan MFA</button>
            </form>
        <?php endif; ?>
    </div>

</body>
</html>