<?php
session_start();

// Database configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'db_sepatu';

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = trim($_POST['password']);
    
    // Validate password strength
    if (strlen($password) < 8) {
        header("Location: register.php?error=password_length");
        exit();
    }
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if email already exists
    $check_stmt = $conn->prepare("SELECT idAkun FROM akun WHERE Email_Akun = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        header("Location: register.php?error=email_exists");
        exit();
    }
    
    // PERBAIKAN 1: Gunakan format ID yang lebih sederhana
    $Customer_idCustomer = 'CP' . strtoupper(substr(bin2hex(random_bytes(5)), 0, 8)); // Total max 10 karakter
    
    // PERBAIKAN 2: Cek struktur kolom foreign key
    // Ganti nama kolom jika diperlukan
    $foreign_key_column = 'Customer_IdCustomer'; // Sesuaikan dengan nama sebenarnya di database
    
    // BUAT RECORD DI TABEL CUSTOMER TERLEBIH DAHULU
    $insert_customer = $conn->prepare("INSERT INTO customer (IdCustomer) VALUES (?)");
    $insert_customer->bind_param("s", $Customer_idCustomer);
    
    if (!$insert_customer->execute()) {
        // Tampilkan error untuk debugging
        die("Error creating customer record: " . $insert_customer->error);
    }
    $insert_customer->close();
    
    // Insert new user with idCustomer
    // PERBAIKAN 3: Gunakan nama kolom yang tepat
    $insert_stmt = $conn->prepare("INSERT INTO akun (Customer_IdCustomer, Email_Akun, Password_Akun) VALUES (?, ?, ?)");
    $insert_stmt->bind_param("sss", $Customer_idCustomer, $email, $hashed_password);
    
    if ($insert_stmt->execute()) {
        // Registration successful
        $_SESSION['registration_success'] = true;
        header("Location: login.php");
        exit();
    } else {
        // Tampilkan error untuk debugging
        die("Error creating account: " . $insert_stmt->error);
    }
    
    $insert_stmt->close();
    $check_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="style.css"/>
</head>
<body>
<div class="container">
  <div class="form-section">
    <img src="logo_sneakntreat.jpg" class="logo" alt="Sneak&Treat" />
    <h3>Start your journey</h3>
    <h2>Sign Up to Sneak&Treat</h2>

    <form action="" method="POST">
      <?php 
        if (isset($_GET['error'])) {
          echo '<div class="error-message">';
          switch ($_GET['error']) {
            case 'password_length':
              echo 'Password must be at least 8 characters long';
              break;
            case 'email_exists':
              echo 'Email already registered';
              break;
            case 'registration_failed':
              echo 'Registration failed. Please try again';
              break;
            default:
              echo 'An error occurred';
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
        <input type="password" name="password" placeholder="Password (min 8 characters)" required />
      </div>

      <button type="submit" class="btn">Sign Up</button>
    </form>

    <p class="or-text">or sign up with</p>
    <div class="social-buttons">
      <i class="fab fa-facebook-f"></i>
      <i class="fab fa-google"></i>
      <i class="fab fa-apple"></i>
    </div>

    <p class="bottom-text">Have an account? <a href="login.php">Sign In</a></p>
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