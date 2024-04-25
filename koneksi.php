<?php
$serverName = 'localhost';
$userName = 'root';
$password = '';
$dbName = 'paketwisata'; 
$conn = mysqli_connect($serverName, $userName, $password, $dbName);
if (!$conn) {
    die("koneksi gagal:".mysqli_connect_error());
}
?>