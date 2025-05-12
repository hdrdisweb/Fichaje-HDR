<?php
 $servername = "db5016791658.hosting-data.io";
$username = "dbu727649";
$password = "HDRdisweb@123456";
$database = "dbs13573726";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
