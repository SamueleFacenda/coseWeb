<?php

$servername = "localhost"; // 127.0.0.1
$username = "samu"; // pastapizza
$password = "";
$dbname = "my_pastapizza";
//mysqli_report(MYSQLI_REPORT_ALL);

$conn = new mysqli($servername, $username, $password, $dbname, null, "/var/run/mysqld/mysqld.sock");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>