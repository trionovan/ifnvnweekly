<?php
    require "fungsi.php";

    if(!isset($_SESSION["login"]) || $_SESSION["login"] != true)
    {
        header("Location: login.php");
        exit;
    }

    $id = $_SESSION["id"];
    $secret = $_SESSION["temp_mfa_secret"] ?? generateSecret();
    $_SESSION["temp_mfa_secret"] = $secret;

    $error = "";

    if(isset($_POST["code"]))
    {
        if(verifyTOTP($secret, trim($_POST["code"])))
        {
            $secretEsc = mysqli_real_escape_string($connection, $secret);
            $query = "UPDATE user SET mfa_secret = '$secretEsc', mfa_enabled = 1 WHERE id = $id";
            mysqli_query($connection, $query);

            unset($_SESSION["temp_mfa_secret"]);
            echo "MFA berhasil diaktifkan! <a href='mahasiswa.php'>Lanjut</a>";
            exit;
        }
        else
        {
            $error = "Kode salah, coba lagi.";
        }
    }

    $qrUrl = getQRCodeUrl($secret, $_SESSION["username"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Setup MFA</title>
</head>
<body>

    <h1>Setup Google Authenticator</h1>
    <p>Scan QR code ini pake app Google Authenticator / Authy:</p>
    <img src="<?= $qrUrl ?>" alt="QR Code">
    <p>Atau input manual secret: <b><?= $secret ?></b></p>

    <?php if($error) echo "<p style='color:red'>$error</p>"; ?>

    <form action="" method="POST">
        <label>Masukin kode 6 digit dari app:</label><br>
        <input type="text" name="code" maxlength="6" required><br><br>
        <button type="submit">Aktifkan</button>
    </form>

</body>
</html>