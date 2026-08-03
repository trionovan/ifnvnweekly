<?php
    require "fungsi.php";

    if(isset($_SESSION["login"]) && $_SESSION["login"] == true)
    {
        header("Location: mahasiswa.php");
        exit;
    }

    if(isset($_POST["login"]))
    {
        $sukses = login($_POST);

        if($sukses === true)
        {
            header("Location: mahasiswa.php");
            exit;
        }
        else if($sukses === "mfa_required")
        {
            header("Location: verify_mfa.php");
            exit;
        }
        else
        {
            echo "<script>alert('Username atau Password salah!');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Informatika</title>
</head>
<body>

    <h1>Login</h1>

    <form action="" method="POST">
        <label>Username</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit" name="login">Login</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Register disini</a></p>

</body>
</html>