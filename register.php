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
    <img src="gambar/logo_sneakntreat.jpg" class="logo" alt="Sneak&Treat" />
    <h3>Start your journey</h3>
    <h2>Sign Up to Sneak&Treat</h2>

    <form action="conn.php" method="POST">
  <input type="hidden" name="action" value="register" />
  <div class="input-group">
    <i class="fas fa-user input-icon"></i>
    <input type="text" name="nama" placeholder="Full Name" required />
  </div>

  <div class="input-group">
    <i class="fas fa-envelope input-icon"></i>
    <input type="email" name="email" placeholder="E-mail" required />
  </div>

  <div class="input-group">
    <i class="fas fa-lock input-icon"></i>
    <input type="password" name="password" placeholder="Password" required />
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
      <img src="gambar/slide 1.jpg" class="slide active" />
      <img src="gambar/slide 2.jpg" class="slide" />
      <img src="gambar/slide 3.jpg" class="slide" />
    </div>
  </div>
</div>

<script src="script.js"></script>
</body>
</html>
