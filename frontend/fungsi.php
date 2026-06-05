<?php
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
?>