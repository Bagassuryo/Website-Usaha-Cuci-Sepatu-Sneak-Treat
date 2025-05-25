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
    <img src="https://media.discordapp.net/attachments/464434558792630282/1376161137321578606/logo_sneakntreat.jpg?ex=6834516e&is=6832ffee&hm=e38c92afb660e7532219c3b99aa0c54efd6a31bfc28835be6ea1ac417b466f0a&=&format=webp&width=859&height=859" class="logo" alt="InsideBox" />
    <h3>Welcome back</h3>
    <h2>Sign In to Sneak&Treat</h2>

    <form action="login_process.php" method="POST">
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
      <img src="https://media.discordapp.net/attachments/464434558792630282/1376161134666715226/slide1.jpg?ex=6834516e&is=6832ffee&hm=0d6a94039c68e0ba28cbfd14b35e30e9aba7ce8ef00559fe41612501443e5a3d&=&format=webp&width=589&height=859" class="slide active" />
      <img src="https://media.discordapp.net/attachments/464434558792630282/1376161135648313425/slide2.jpg?ex=6834516e&is=6832ffee&hm=1fa9eb250c7e97db6d775b5767e698a9eff8050b2d4d4480f3a92a4361714e10&=&format=webp&width=686&height=859" class="slide" />
      <img src="https://media.discordapp.net/attachments/464434558792630282/1376161136659136625/slide3.jpg?ex=6834516e&is=6832ffee&hm=f0cd0dfe365e89d8417a7aa3c75c26da2530c0c85aec15f89cee218f2d3eca37&=&format=webp&width=614&height=859" class="slide" />
    </div>
  </div>
</div>

<script src="script.js"></script>
</body>
</html>
