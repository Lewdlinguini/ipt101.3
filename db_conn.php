<?php

//https://github.com/Lewdlinguini/ipt101.2

$sname = "localhost";

$uname = "root";

$password = "";
 
$db_name = "ipt101.3";

$conn = mysqli_connect($sname, $uname, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>