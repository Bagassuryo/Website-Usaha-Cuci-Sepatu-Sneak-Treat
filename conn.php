<?php
session_start();

$host = "localhost"; // sesuaikan dengan host
$user = "root";      // sesuaikan dengan user database
$pass = "";          // sesuaikan dengan password database
$db   = "db_sepatu"; // sesuaikan dengan nama database kamu

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if ($action === "login") {
        $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
        if (mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);
            $_SESSION['name'] = $data['name']; // konsisten pakai 'name'
            header("Location: index.php");
            exit;
        } else {
            echo "<script>alert('Login gagal! Email atau password salah.'); window.location='login.php';</script>";
        }

    } elseif ($action === "register") {
        $name = mysqli_real_escape_string($conn, $_POST['nama']);

        // Cek apakah email sudah terdaftar
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            echo "<script>alert('Email sudah terdaftar!'); window.location='register.php';</script>";
        } else {
            $insert = mysqli_query($conn, "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");
            if ($insert) {
                $_SESSION['name'] = $name; // konsisten pakai 'name'
                header("Location: index.php");
                exit;
            } else {
                echo "<script>alert('Registrasi gagal!'); window.location='register.php';</script>";
            }
        }
    }
}
?>
