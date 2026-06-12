<?php

    require "fungsi.php";

    $id = $_GET["id"];

    $query = "DELETE FROM mahasiswa WHERE id = $id";

    mysqli_query($connection, $query);

    if(mysqli_affected_rows($connection) > 0)
    {
    echo "<script>
        alert('Data berhasil dihapus!');
        window.location.href='mahasiswa.php';
    </script>";
    }
    else
    {
    echo "<script>
        alert('Data gagal dihapus!');
        window.location.href='mahasiswa.php';
    </script>";
    }

?>