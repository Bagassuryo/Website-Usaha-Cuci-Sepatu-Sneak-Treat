<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h1>Selamat Datang, <?= $_SESSION["name"] ?>!</h1>
    <p>Anda berhasil login ke sistem cuci sepatu.</p>
    <a href="logout.php" class="btn btn-danger">Logout</a>
  </div>
</body>
</html>
