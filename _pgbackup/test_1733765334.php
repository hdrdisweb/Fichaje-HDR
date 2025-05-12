<?php
 $servername = "db5016800281.hosting-data.io";
$username = "dbu605190";
$password = "HDRdisweb@123456";
$database = "dbs13578445";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
