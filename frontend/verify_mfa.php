<?php
    require "fungsi.php";

    if(!isset($_SESSION["pending_mfa_id"]))
    {
        header("Location: login.php");
        exit;
    }

    $error = "";

    if(isset($_POST["code"]))
    {
        $id = $_SESSION["pending_mfa_id"];
        $query = "SELECT * FROM user WHERE id = $id";
        $result = mysqli_query($connection, $query);
        $user = mysqli_fetch_assoc($result);

        if(verifyTOTP($user["mfa_secret"], trim($_POST["code"])))
        {
            unset($_SESSION["pending_mfa_id"]);
            $_SESSION["login"] = true;
            $_SESSION["id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            header("Location: mahasiswa.php");
            exit;
        }
        else
        {
            $error = "Kode salah atau sudah expired, coba lagi.";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi MFA</title>
</head>
<body>

    <h1>Verifikasi Kode</h1>
    <p>Masukin kode 6 digit dari Google Authenticator lo</p>

    <?php if($error) echo "<p style='color:red'>$error</p>"; ?>

    <form action="" method="POST">
        <input type="text" name="code" maxlength="6" required autofocus>
        <button type="submit">Verifikasi</button>
    </form>

</body>
</html>