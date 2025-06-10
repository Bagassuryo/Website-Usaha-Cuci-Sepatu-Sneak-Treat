<?php
session_start();
require_once 'db_config.php';

// Jika admin sudah login, redirect ke halaman admin
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: ../Admin_Page/IndexAdmin.php");
    exit();
}

// Jika user biasa sudah login, redirect ke halaman utama
if (isset($_SESSION['user_id'])) {
    header("Location: ../Landing_page/index.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Check for admin credentials first
    $admin_email = "Sneak&Treat@gmail.com";
    $admin_password = "JayaSneak&Treat";
    
    if ($email === $admin_email && $password === $admin_password) {
        // Admin login successful
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $email;
        header("Location: ../Admin_Page/IndexAdmin.php");
        exit();
    } else {
        // Proceed with regular user login
        $stmt = $conn->prepare("SELECT a.idAkun, a.Password_Akun, a.Customer_IdCustomer, c.Nama 
                              FROM akun a
                              JOIN customer c ON a.Customer_IdCustomer = c.IdCustomer
                              WHERE a.Email_Akun = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $row['Password_Akun'])) {
                // Login successful
                $_SESSION['user_id'] = $row['idAkun'];
                $_SESSION['customer_id'] = $row['Customer_IdCustomer'];
                $_SESSION['email'] = $email;
                $_SESSION['nama'] = $row['Nama'];
                
                header("Location: ../Landing_page/index.php");
                exit();
            } else {
                // Invalid password
                $error = "invalid_credentials";
            }
        } else {
            // User not found
            $error = "user_not_found";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="style.css"/>
</head>
<body>
<div class="container">
  <div class="form-section">
    <img src="logo_sneakntreat.jpg" class="logo" alt="Sneak&Treat" />
    <h3>Welcome back</h3>
    <h2>Sign In to Sneak&Treat</h2>

    <form action="" method="POST">
      <?php 
        if (isset($error)) {
          echo '<div class="error-message">';
          if ($error == 'invalid_credentials') {
            echo 'Invalid email or password';
          } elseif ($error == 'user_not_found') {
            echo 'User not found';
          }
          echo '</div>';
        }
      ?>
      <div class="input-group">
        <i class="fas fa-envelope input-icon"></i>
        <input type="email" name="email" placeholder="E-mail" required />
      </div>

      <div class="input-group">
        <i class="fas fa-lock input-icon"></i>
        <input type="password" name="password" placeholder="Password" required />
      </div>

      <button type="submit" class="btn">Sign In</button>
    </form>

    <p class="or-text">or sign in with</p>
    <div class="social-buttons">
      <i class="fab fa-facebook-f"></i>
      <i class="fab fa-google"></i>
      <i class="fab fa-apple"></i>
    </div>

    <p class="bottom-text">Don't have an account? <a href="register.php">Sign Up</a></p>
  </div>

  <div class="slider-section">
    <div class="slider">
      <img src="slide1.jpg" class="slide active" />
      <img src="slide2.jpg" class="slide" />
      <img src="slide3.jpg" class="slide" />
    </div>
  </div>
</div>

<script src="script.js"></script>
</body>
</html>