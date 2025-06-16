<?php
$host = "localhost";
$user = "root"; // Ganti jika username berbeda
$pass = "";     // Ganti jika password ada
$db   = "db_sepatu"; // Ganti sesuai nama database Anda

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>