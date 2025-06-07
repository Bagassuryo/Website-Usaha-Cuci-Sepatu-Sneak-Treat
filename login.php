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
    <img src="gambar/logo_sneakntreat.jpg" class="logo" alt="Sneak&Treat" />
    <h3>Welcome back</h3>
    <h2>Sign In to Sneak&Treat</h2>

    <form action="conn.php" method="POST">
  <input type="hidden" name="action" value="login" />
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
      <img src="gambar/slide 1.jpg" class="slide active" />
      <img src="gambar/slide 2.jpg" class="slide" />
      <img src="gambar/slide 3.jpg" class="slide" />
    </div>
  </div>
</div>

<script src="script.js"></script>
</body>
</html>
