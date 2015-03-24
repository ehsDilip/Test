<?php


$con = mysqli_connect("localhost", "root", "12345", "yourdbname");
if (mysqli_connect_errno()) {
    echo "Database did not connect";
    exit();
}
?>