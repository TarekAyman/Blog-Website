<?php
    $host="localhost";
    $user="root";
    $password="";
    $dbname="Matter_Project";
    $conn=new mysqli($host,$user,$password,$dbname);
    if ($conn->connect_error) {
        die("Failed To Connect" . $conn->connect_error);
    }
?>