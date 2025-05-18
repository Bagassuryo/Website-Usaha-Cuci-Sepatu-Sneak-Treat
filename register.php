<?php
$conn = new mysqli("localhost", "root", "", "db_sepatu");
$pesan = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $cek = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $cek->bind_param("s", $email);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        $pesan = "Email sudah terdaftar. Silakan gunakan email lain atau login.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $pesan = "Registrasi berhasil! Silakan login.";
        } else {
            $pesan = "Terjadi kesalahan saat registrasi.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Cuci Sepatu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <form method="POST" class="form-box">
      <h2>Sign Up</h2>
      <p class="subtitle">Start your journey on Sneak&Treat</p>

      <?php if ($pesan): ?>
        <div class="alert"><?= $pesan ?></div>
      <?php endif; ?>

      <label>Nama</label>
      <input type="text" name="name" placeholder="Enter Name" required>

      <label>Email</label>
      <input type="email" name="email" placeholder="Enter Email" required>

      <label>Password</label>
      <input type="password" name="password" placeholder="Enter Password" required>

      <button type="submit" class="btn">Register</button>

      <p class="switch">Already has an account? <a href="login.php">Login di sini</a></p>
    </form>
  </div>
</body>
</html>
