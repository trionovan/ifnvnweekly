<?php
    require 'fungsi.php';

    if(isset($_POST["register"]))
    {
        if (register($_POST) > 0)
        {
            echo "<script>
            alert('User Berhasil ditambahkan!');
            window.location.href='login.php';
            </script>
            ";

        } else {
        echo "<script>
            alert('User Gagal ditambahkan!');
            window.location.href='register.php';
            </script>
            ";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User | Informatika</title>
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

        .register-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 400px;
        }

        .register-card h1 {
            font-size: 26px;
            color: #333;
            margin-bottom: 24px;
            text-align: center;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #555;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #dcdfe6;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
            outline: none;
        }

        .form-group input:focus {
            border-color: #4f46e5;
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
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: #4338ca;
        }

        .footer-text {
            margin-top: 24px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }

        .footer-text a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="register-card">
        <h1>Register User</h1>

        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Input username" required autofocus autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password1">Password</label>
                <input type="password" id="password1" name="password1" placeholder="Input password" required>
            </div>

            <div class="form-group">
                <label for="password2">Konfirmasi Password</label>
                <input type="password" id="password2" name="password2" placeholder="Confirm password" required>
            </div>

            <button type="submit" name="register" class="btn-submit">Register</button>
        </form>

        <p class="footer-text">Sudah punya akun? <a href="login.php">Login disini</a></p>
    </div>

</body>
</html>