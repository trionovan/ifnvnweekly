<?php
    // Mengimpor file fungsi.php yang berisi koneksi database & helper function (verifyTOTP, dll)
    require "fungsi.php";

    // Cek apakah ada session 'pending_mfa_id' (artinya user baru aja lolos cek password
    // dan mfa_enabled-nya sudah 1). Kalau session ini gak ada, berarti user akses
    // halaman ini secara langsung tanpa lewat proses login, jadi dilempar balik ke login.php
    if(!isset($_SESSION["pending_mfa_id"]))
    {
        header("Location: login.php");
        exit;
    }

    // Inisialisasi variabel penampung pesan error
    $error = "";

    // Cek apakah form input kode OTP sudah disubmit via metode POST
    if(isset($_POST["code"]))
    {
        // Mengambil ID user dari session sementara yang di-set pas proses login
        $id = $_SESSION["pending_mfa_id"];

        // Query untuk mengambil data user (termasuk mfa_secret) berdasarkan ID
        $query = "SELECT * FROM user WHERE id = $id";
        $result = mysqli_query($connection, $query);
        $user = mysqli_fetch_assoc($result);

        // Memvalidasi kode OTP yang diinput user dengan secret key yang tersimpan di database
        if(verifyTOTP($user["mfa_secret"], trim($_POST["code"])))
        {
            // Kode benar, hapus session sementara karena proses verifikasi udah selesai
            unset($_SESSION["pending_mfa_id"]);

            // Set session login resmi, menandakan user sudah terautentikasi penuh (password + MFA)
            $_SESSION["login"] = true;
            $_SESSION["id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            // Redirect ke halaman utama/dashboard
            header("Location: mahasiswa.php");
            exit;
        }
        else
        {
            // Pesan error jika kode salah, kadaluwarsa, atau jam HP client gak sinkron
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

        <!-- Menampilkan pesan alert error jika kode OTP yang diinput salah -->
        <?php if($error): ?>
            <div class="alert-error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Form input untuk memasukkan 6 digit kode OTP dari Google Authenticator -->
        <form action="" method="POST">
            <div class="form-group">
                <input type="text" name="code" class="input-otp" maxlength="6" placeholder="000000" required autofocus autocomplete="off">
            </div>

            <button type="submit" class="btn-submit">Verifikasi</button>
        </form>
    </div>

</body>
</html>