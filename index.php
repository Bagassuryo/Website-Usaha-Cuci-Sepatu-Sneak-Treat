<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sneak & Treat</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-emerald-800 text-white font-sans">

  <!-- Navbar Section -->
<header class="bg-emerald-800">
  <div class="mx-auto flex h-16 max-w-screen-xl items-center gap-8 px-4 sm:px-6 lg:px-8">
    <a class="block text-teal-600 dark:text-teal-300" href="#">
      <span class="sr-only">Home</span>
      <img src="Gambar/Logo.jpg" alt="Logo" class="h-12 rounded-full" viewBox="0 0 28 24" fill="none"> </img>
    </a>

    <div class="flex flex-1 items-center justify-end md:justify-between">
      <nav aria-label="Global" class="hidden md:block">
        <ul class="flex items-center gap-6 text-sm">
          <li> <a class="text-white transition hover:text-yellow-400" href="#About"> About </a> </li>

          <li> <a class="text-white transition hover:text-yellow-400" href="#Gallery"> Gallery </a> </li>

          <li> <a class="text-white transition hover:text-yellow-400" href="#Maps"> Maps </a> </li>

          <li> <a class="text-white transition hover:text-yellow-400" href="#Contact"> Contact </a> </li>

        </ul>
      </nav>

      <div class="flex items-center gap-4">
        <div class="sm:flex sm:gap-4">
          <a class="block rounded-md bg-yellow-400 px-5 py-2.5 text-sm font-medium text-black transition hover:bg-yellow-300" href="login.php"> Login </a>

          <a class="hidden rounded-md bg-white px-5 py-2.5 text-sm font-medium text-black transition hover:bg-black transition hover:text-white sm:block" href="register.php"> Register </a>
        </div>

        <button
          class="block rounded-sm bg-gray-100 p-2.5 text-gray-600 transition hover:text-gray-600/75 md:hidden dark:bg-gray-800 dark:text-white dark:hover:text-white/75"
        >
          <span class="sr-only">Toggle menu</span>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="size-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</header>

  <!-- Hero Section -->
  <section class="bg-emerald-800 lg:grid lg:h-screen lg:place-content-center">
        <div class="mx-auto w-screen max-w-screen-xl px-4 py-16 sm:px-6 sm:py-24 md:grid md:grid-cols-2 md:items-center md:gap-4 lg:px-8 lg:py-32">
            <div class="max-w-prose text-left">
                <h1 class="text-4xl font-bold mb-6 text-yellow-300 sm:text-5xl"> Sneak & Treat </h1>
                <h2 class="text-white text-4xl md:text-5xl font-bold max-w-xl mb-6 leading-tight">Solusi Cuci Sepatu Profesional - Cepat, Bersih, Aman.</h2>

                <div class="mt-4 flex gap-4 sm:mt-6">
                    <a class="inline-block rounded border border-yellow-400 bg-yellow-400 px-5 py-3 font-medium text-black shadow-sm transition-colors hover:bg-yellow-300" href="pemesanan.php"> Pesan Sekarang </a>
                    <a class="inline-block rounded border border-gray-200 px-5 py-3 font-medium text-white shadow-sm transition-colors hover:bg-gray-50 hover:text-black" href="#layanan"> Layanan Kami </a>
                </div>
            </div>
            <img src="Gambar/Sepatuu.png" alt="Sepatu" class="bottom-30 w-100 h-100 rounded-full bg-yellow-400 md:justify-self-end md:self-start mx-auto md:mx-0">
        </div>
    </section>

  <!-- Tentang Kami -->
  <section class="bg-white text-gray-800 py-20 px-10">
    <h2 class="text-3xl font-bold mb-10 text-center">Kenapa Memilih Sneak & Treat?</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
      <div>
        <p class="text-4xl mb-4">🔥</p>
        <p>Pengerjaan Cepat dan Rapi</p>
      </div>
      <div>
        <p class="text-4xl mb-4">💦</p>
        <p>Teknologi Cuci Premium</p>
      </div>
      <div>
        <p class="text-4xl mb-4">👟</p>
        <p>Bisa Semua Jenis Sepatu</p>
      </div>
      <div>
        <p class="text-4xl mb-4">🌿</p>
        <p>Ramah Lingkungan</p>
      </div>
    </div>
  </section>

  <!-- Layanan -->
  <section id="layanan" class="bg-gray-100 text-gray-800 py-20 px-10">
    <h2 class="text-3xl font-bold mb-10 text-center">Layanan Kami</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="bg-white p-6 rounded-lg shadow text-center">
        <h3 class="text-xl font-semibold mb-2">Deep Clean</h3>
        <p>Rp 30.000</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow text-center">
        <h3 class="text-xl font-semibold mb-2">Reglue</h3>
        <p>Rp 15.000 - 30.000</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow text-center">
        <h3 class="text-xl font-semibold mb-2">Repaint</h3>
        <p>Rp 65.000</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow text-center">
        <h3 class="text-xl font-semibold mb-2">Unyellowing</h3>
         <p>Rp 40.000</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow text-center">
        <h3 class="text-xl font-semibold mb-2">Reparasi Sepatu</h3>
         <p>Rp 65.000</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow text-center">
        <h3 class="text-xl font-semibold mb-2">Kid Shoes</h3>
         <p>Rp 20.000</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow text-center">
        <h3 class="text-xl font-semibold mb-2">Express Clean</h3>
         <p>Rp 50.000</p>
      </div>
    </div>
  </section>

  <!-- Galeri Before After -->
 <section id="Gallery" class="bg-white text-gray-800 py-20 px-10">
  <h2 class="text-3xl font-bold mb-10 text-center">Galeri Before & After</h2>

  <!-- Container utama tengah -->
  <div class="flex flex-col items-center gap-8">
    
    <div class="flex flex-col md:flex-row justify-center gap-6">
      <div class="text-center">
        <img src="Gambar/Before1.jpg" alt="Before 1" class="max-w-xs w-full h-auto rounded-lg mb-2 mx-auto">
        <p>Before</p>
      </div>
      <div class="text-center">
        <img src="Gambar/After1.jpg" alt="After 1" class="max-w-xs w-full h-auto rounded-lg mb-2 mx-auto">
        <p>After</p>
      </div>
    </div>
    <div class="flex flex-col md:flex-row justify-center gap-6">
      <div class="text-center">
        <img src="Gambar/Before2.jpg" alt="Before 2" class="max-w-xs w-full h-auto rounded-lg mb-2 mx-auto">
        <p>Before</p>
      </div>
      <div class="text-center">
        <img src="Gambar/After2.jpg" alt="After 2" class="max-w-xs w-full h-auto rounded-lg mb-2 mx-auto">
        <p>After</p>
      </div>
    </div>
    <div class="flex flex-col md:flex-row justify-center gap-6">
      <div class="text-center">
        <img src="Gambar/Before3.jpg" alt="Before 2" class="max-w-xs w-full h-auto rounded-lg mb-2 mx-auto">
        <p>Before</p>
      </div>
      <div class="text-center">
        <img src="Gambar/After3.jpg" alt="After 2" class="max-w-xs w-full h-auto rounded-lg mb-2 mx-auto">
        <p>After</p>
      </div>
    </div>

  </div>
</section>



  <!-- Testimoni Pelanggan -->
 <section class="bg-emerald-800 text-white py-20 px-10">
  <h2 class="text-3xl font-bold mb-10 text-center">Apa Kata Pelanggan Kami?</h2>
  <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-emerald-600 p-6 rounded-lg">
      <p class="italic">"Sepatu saya jadi kayak baru lagi! Suka banget sama hasilnya."</p>
      <p class="mt-4 text-right font-semibold">- Andi</p>
    </div>
    <div class="bg-emerald-600 p-6 rounded-lg">
      <p class="italic">"Pelayanannya cepat dan ramah. Rekomendasi banget buat kalian!"</p>
      <p class="mt-4 text-right font-semibold">- Rina</p>
    </div>
    <div class="bg-emerald-600 p-6 rounded-lg">
      <p class="italic">"Awalnya ragu, tapi setelah lihat hasilnya, saya puas banget! Sepatunya jadi kinclong"</p>
      <p class="mt-4 text-right font-semibold">- Dewa</p>
    </div>
    <div class="bg-emerald-600 p-6 rounded-lg">
      <p class="italic">"Senang banget nemu tempat bersihin sepatu sebagus ini. Worth it lah pokoknya!"</p>
      <p class="mt-4 text-right font-semibold">- Ferdi</p>
    </div>
  </div>
</section>


<!-- Contact Me Section -->
<section class="bg-white text-gray-800 py-20 px-10" id="contact">
  <h2 class="text-3xl font-bold mb-10 text-center">Contact <span class="text-emerald-600">Me</span></h2>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
    
    <!-- Info Kontak -->
    <div class="space-y-6">
      <div>
        <h4 class="text-xl font-semibold">Address</h4>
        <p>📍JL. Krukah Selatan 106, Wonokromo</p>
      </div>
      <div>
        <h4 class="text-xl font-semibold">Instagram</h4>
        <p>@sneakandtreat</p>
      </div>
      <div>
        <h4 class="text-xl font-semibold">No. HP</h4>
        <p>0813-1212-0433</p>
      </div>
    </div>

    <!-- Google Maps -->
    <iframe 
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.5199224346643!2d112.7531303!3d-7.2953304999999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fb86546f5141%3A0xdd7908d1c51de6a1!2sLight%20Service%20%26%20Space!5e0!3m2!1sen!2sid!4v1747744325456!5m2!1sen!2sid" 
      width="100%" 
      height="350" 
      style="border:0;" 
      allowfullscreen="" 
      loading="lazy" 
      referrerpolicy="no-referrer-when-downgrade">
    </iframe>
  </div>
</section>


  <!-- Footer -->
  <footer class="bg-emerald-800 text-white py-10 text-center">
    <p class="mb-4">&copy; 2025 Sneak & Treat. All rights reserved.</p>
    <div class="flex justify-center space-x-6">
      <a href="https://linktr.ee/sneakandtreat?fbclid=PAZXh0bgNhZW0CMTEAAadVlw7MkI0uRAENVoK80kG26txNXvdgK2F_rt6iZgbfhnvdjkR4IkMq12wiHA_aem_tSmAhZMSX-O4rsGkNZNV9w">LinkTree🍀</a>
    </div>
  </footer>
</body>
</html>