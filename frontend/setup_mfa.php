<?php
//session_start();
require "fungsi.php";
// include "totp_helper.php"; // Buka jika totp_helper belum otomatis ter-load

// 1. Cek apakah user datang dari proses login yang valid (butuh setup MFA)
if (!isset($_SESSION["pending_setup_id"])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION["pending_setup_id"];

// Ambil data username & data user dari DB berdasarkan ID sementara
$queryUser = "SELECT * FROM user WHERE id = $id";
$resultUser = mysqli_query($connection, $queryUser);
$user = mysqli_fetch_assoc($resultUser);
$username = $user["username"] ?? 'User';

// 2. Generate secret code baru kalau belum ada di session temp
if (!isset($_SESSION["temp_mfa_secret"])) {
    $_SESSION["temp_mfa_secret"] = generateSecret();
}
$secret = $_SESSION["temp_mfa_secret"];

// 3. Bikin URL QR Code buat di-scan aplikasi authenticator
$qrCodeUrl = getQRCodeUrl($secret, $username);

$error = "";
$success = "";

// 4. Proses verifikasi pas user submit kode pertama kali
if (isset($_POST["code"])) {
    $userCode = trim($_POST["code"]);

    if (verifyTOTP($secret, $userCode)) {
        $secretEsc = mysqli_real_escape_string($connection, $secret);
        
        // Update data user: simpen secret dan aktifin status mfa_enabled jadi 1
        $queryUpdate = "UPDATE user SET mfa_secret = '$secretEsc', mfa_enabled = 1 WHERE id = $id";
        
        if (mysqli_query($connection, $queryUpdate)) {
            // Hapus session temporary MFA setup
            unset($_SESSION["temp_mfa_secret"]);
            unset($_SESSION["pending_setup_id"]);
            
            // Login sukses! Sekarang set session login utamanya biar bisa masuk dashboard
            $_SESSION["login"] = true;
            $_SESSION["id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            $success = "MFA berhasil diaktifkan! <a href='mahasiswa.php'>Lanjut ke Dashboard</a>";
        } else {
            $error = "Gagal mengupdate database.";
        }
    } else {
        $error = "Kode salah atau expired. Coba scan ulang atau pastikan jam HP cocok.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Setup MFA</title>
</head>
<body>

    <h1>Setup Multi-Factor Authentication (MFA)</h1>
    <p>Halo <strong><?php echo htmlspecialchars($username); ?></strong>, scan QR Code di bawah ini pake aplikasi Google Authenticator lo, terus masukin kode 6 digitnya buat aktivasi.</p>

    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>

    <?php if (!$success): ?>
        <!-- Bagian render barcode / QR code -->
        <div style="margin-bottom: 20px;">
            <img src="<?php echo $qrCodeUrl; ?>" alt="QR Code MFA" style="border: 1px solid #ccc; padding: 10px;">
            <br>
            <small>Gak bisa scan? Masukin kode ini manual: <strong><?php echo $secret; ?></strong></small>
        </div>

        <form action="" method="POST">
            <input type="text" name="code" maxlength="6" placeholder="000000" required autofocus autocomplete="off">
            <button type="submit">Aktifkan MFA</button>
        </form>
    <?php endif; ?>

</body>
</html>