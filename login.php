<?php
$conn = new mysqli("localhost", "root", "", "db_sepatu");
session_start();
$pesan = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["name"] = $name; 
            header("Location: main.php");
            exit;
        } else {
            $pesan = "Password salah.";
        }
    } else {
        $pesan = "Email tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Cuci Sepatu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <form method="POST" action="" class="form-box">
      <h2>Log In</h2>
      <p class="subtitle">Welcome back! Log in to continue.</p>

      <?php if ($pesan): ?>
        <div class="alert"><?= $pesan ?></div>
      <?php endif; ?>

      <label>Email</label>
      <input type="email" name="email" placeholder="Enter Email" required>

      <label>Password</label>
      <input type="password" name="password" placeholder="Enter Password" required>

      <div class="forgot"><a href="#">Forgot Password</a></div>

      <button type="submit" class="btn">Log In</button>

      <p class="switch">Don't have an account? <a href="register.php">Sign Up</a></p>
    </form>
  </div>
</body>
</html>
