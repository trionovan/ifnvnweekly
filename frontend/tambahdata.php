<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Mahasiswa</title>
</head>
<body>
    <h2>Tambah Data Mahasiswa</h2>
    <form action="mahasiswa.php" method="post">
        <table cellPadding="5px">
            <tr>
                <td><label for="nama">Nama</label></td>
                <td>:<td>
                <td><input type="text" name="nama" id="nama" require/></td>
            </tr>
            <tr>
                <td><label for="nim">NIM</label></td>
                <td>:<td>
                <td><input type="number" name="nim" id="nim" require/><td>
            </tr>
                <td><label for="jurusan">Jurusan</label></td>
                <td>:<td>
                <td><input type="text" name="jurusan" id="jurusan" require/><td>
            </tr>
            </tr>
                <td><label for="email">Email</label></td>
                <td>:<td>
                <td><input type="text" name="email" id="email" require/><td>
            </tr>
            </tr>
                <td><label for="no.hp">No.Hp</label></td>
                <td>:<td>
                <td><input type="number" name="no.hp" id="no.hp" require/><td>
            </tr>
                        </tr>
                <td><label for="foto">Foto</label></td>
                <td>:<td>
                <td><input type="foto" name="no.hp" id="foto" require/><td>
            </tr>
            <tr>
                <td colspan="3">
                    <button type="submit" name="submit">Tambah</button>
                </td>
            </tr>
        </table>
        <br>
        <hr>
            </table>
        </form>
        
    </form>
</body>
</html>