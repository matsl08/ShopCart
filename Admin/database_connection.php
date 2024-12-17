<?php
    $connect = mysqli_connect("localhost","root","","shopcart");
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>