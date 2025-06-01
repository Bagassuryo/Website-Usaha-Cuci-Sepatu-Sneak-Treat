<?php
session_start();

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "db_sepatu");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah form login atau register
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'register') {
        // Register
        $name = $_POST['nama'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Cek email sudah terdaftar
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            header("Location: register.php?error=exists");
            exit();
        }

        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            header("Location: login.php?success=registered");
            exit();
        } else {
            header("Location: register.php?error=failed");
            exit();
        }
    }

    if ($action === 'login') {
        // Login
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: index.php");
                exit();
            } else {
                header("Location: login.php?error=password");
                exit();
            }
        } else {
            header("Location: login.php?error=email");
            exit();
        }
    }
} else {
    header("Location: login.php");
    exit();
}
