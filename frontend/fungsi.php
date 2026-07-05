<?php
        session_start();

        function login($data)
        {
            global $connection;

            $username = mysqli_real_escape_string($connection, $data["username"]);
            $password = $data["password"]; // password mentah dari form, JANGAN di-hash disini

            $query = "SELECT * FROM user WHERE username = '$username'";
            $result = mysqli_query($connection, $query);

            if(mysqli_num_rows($result) == 1)
            {
                $user = mysqli_fetch_assoc($result);

                // Ini nih bagian pentingnya bre, verify password pake hash yg disimpan pas register
                if(password_verify($password, $user["password"]))
                {
                    $_SESSION["login"] = true;
                    $_SESSION["id"] = $user["id"];
                    $_SESSION["username"] = $user["username"];

                    return true;
                }
            }

            return false;
        }

        function logout()
        {
            session_unset();
            session_destroy();
        }

        $connection = mysqli_connect("localhost", "root", "root", "ifnvnweekly");

        function tampildata($query)
        {
            global $connection;
            $result = mysqli_query($connection, $query);

            $rows = [];
            while($row = mysqli_fetch_assoc($result))
                {
                    $rows[] = $row;
                }
            
            return $rows;
        }

        function tambahdata($data)
        {
            global $connection;

            $nama = htmlspecialchars($data["nama"]);
            $nim = htmlspecialchars($data["nim"]);
            $jurusan = htmlspecialchars($data["jurusan"]);
            $email = htmlspecialchars($data["email"]);
            $nohp = htmlspecialchars($data["no_hp"]);
            $foto = $data["foto"];

            $query = "INSERT INTO mahasiswa (nama, nim, jurusan, email, no_hp, foto)
              VALUES ('$nama', '$nim', '$jurusan', '$email', '$nohp', '$foto')";

            if(mysqli_query($connection, $query));
            return mysqli_affected_rows($connection);
        }

        function hapusdata($id)
        {
            global $connection;

            $query = "DELETE FROM mahasiswa WHERE id=$id";

            mysqli_query($connection,$query);

            return mysqli_affected_rows($connection);
        }

        function register($data)
        {
            global $connection;

            $username = stripslashes($data["username"]);
            $password1 = mysqli_real_escape_string($connection,$data
            ["password1"]);
            $password2 = mysqli_real_escape_string($connection,$data
            ["password2"]);

            if($password1 != $password2)
            {
                echo "<script>
                alert('Konfirmasi Password Tidak Sesuai!');
                window.location.href='register.php';
                </script>
                ";
                return false;
            }

            //Enkripsi Password
            $password_hash = password_hash($password1, PASSWORD_DEFAULT);
            $query = "INSERT INTO user(username, password) VALUES 
            ('$username', '$password_hash')";

            mysqli_query($connection,$query);

            return mysqli_affected_rows($connection);
        }
?>