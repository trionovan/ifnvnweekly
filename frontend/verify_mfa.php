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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi MFA | Informatika</title>
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
            height: 100vh;
        }

        .auth-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .auth-card h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .auth-card p {
            font-size: 14px;
            color: #666;
            margin-bottom: 24px;
            line-height: 1.4;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #dc2626;
            padding: 10px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 20px;
            border: 1px solid #fca5a5;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .input-otp {
            width: 100%;
            padding: 14px;
            border: 1px solid #dcdfe6;
            border-radius: 8px;
            font-size: 22px;
            letter-spacing: 6px;
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

    <div class="auth-card">
        <h1>Verifikasi Kode</h1>
        <p>Masukkan 6 digit kode dari aplikasi Google Authenticator Anda</p>

        <?php if($error): ?>
            <div class="alert-error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <input type="text" name="code" class="input-otp" maxlength="6" placeholder="000000" required autofocus autocomplete="off">
            </div>

            <button type="submit" class="btn-submit">Verifikasi</button>
        </form>
    </div>

</body>
</html>