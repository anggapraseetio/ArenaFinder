<?php
session_start();

// Database connection
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "arenafinder";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Inisialisasi variabel
$imageData = [];
$id_venue = null;
$latitude = null;
$longitude = null;
$harga = 0;
$harga_member = 0;
$isLoggedIn = false;

// Cek apakah pengguna login
if (isset($_SESSION['email'])) {
  $email = $_SESSION['email'];
  $isLoggedIn = true;

  // Ambil id_venue berdasarkan email pengguna
  $stmt = $conn->prepare("SELECT id_venue FROM venues WHERE email = ?");
  if ($stmt === false) {
    die('Query prepare failed: ' . $conn->error);
  }
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id_venue = $row['id_venue']; // Simpan id_venue
  }



  // Ambil id_venue berdasarkan email pengguna
  $stmt = $conn->prepare("SELECT username FROM users WHERE email = ?");
  if ($stmt === false) {
    die('Query prepare failed: ' . $conn->error);
  }
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username']; // Simpan id_venue
  }

  // Ambil data nama dan jumlah lapangan dari database untuk venue yang sedang login
  $stmt = $conn->prepare("SELECT venue_name, total_lapangan, sport FROM venues WHERE email = ?");
  if ($stmt === false) {
    die('Query prepare failed: ' . $conn->error);
  }
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nama_venue = $row['venue_name']; // Nama venue
    $lapangan = $row['total_lapangan']; // Jumlah lapangan
    $sport = $row['sport']; // Sport

  } else {
    $nama_venue = "Nama Venue Tidak Ditemukan";
    $lapangan = 0;
  }

  // Mengambil data foto venue dari database
  $venueId = $id_venue; // Ambil id_venue jika sudah ada
  $imagePath = "/ArenaFinder/img_asset/logo2.png"; // Gambar default jika tidak ada

  $stmt = $conn->prepare("SELECT venue_photo FROM venues WHERE id_venue = ?");
  $stmt->bind_param("i", $venueId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $imageName = $row['venue_photo'];

    // Tentukan path gambar, misalnya gambar disimpan dalam folder 'public/img/venue/'
    $imagePath = "/ArenaFinder/public/img/venue/" . $imageName;
  }

  // Ambil deskripsi dari database
  $deskripsi = "Deskripsi belum tersedia."; // Default jika tidak ada data
  $stmt = $conn->prepare("SELECT desc_venue, price_membership, price FROM venues WHERE email = ?");
  if ($stmt === false) {
    die('Query prepare failed: ' . $conn->error);
  }
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $deskripsi = $row['desc_venue'];
    $harga_member = $row['price_membership'];
    $harga = $row['price'];
  }

  // Ambil data koordinat berdasarkan id_venue
  if ($isLoggedIn && $id_venue) {
    $stmt = $conn->prepare("SELECT coordinate FROM venues WHERE id_venue = ?");
    if ($stmt === false) {
      die('Query prepare failed: ' . $conn->error);
    }
    $stmt->bind_param("i", $id_venue);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $coordinates = $row['coordinate'];

      // Pisahkan latitude dan longitude
      if (!empty($coordinates)) {
        list($latitude, $longitude) = explode(',', $coordinates);
        $latitude = trim($latitude);
        $longitude = trim($longitude);
      }
    }
  }

  // Ambil data galeri jika login
  $sql = "SELECT id_galery, photo FROM venue_galery WHERE id_venue = ?";
  $stmt = $conn->prepare($sql);
  if ($stmt === false) {
    die('Query prepare failed: ' . $conn->error);
  }
  $stmt->bind_param("i", $id_venue);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $imageData[] = $row;
    }
  }
}

// File upload & delete logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Upload file
  if (!empty($_FILES['fileUpload']['name'])) {
    $nama_file = basename($_FILES['fileUpload']['name']);
    $tmp = $_FILES['fileUpload']['tmp_name'];

    $upload_folder = __DIR__ . 'ArenaFinder/public/img/venue/';
    $target_file = $upload_folder . $nama_file;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validasi file
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
      echo "<script>alert('Format file tidak didukung. Hanya JPG, JPEG, PNG, dan GIF diperbolehkan.');</script>";
    } elseif (move_uploaded_file($tmp, $target_file)) {
      // Insert ke database
      $id_galery = time();
      $stmt = $conn->prepare("INSERT INTO venue_galery (id_galery, id_venue, photo) VALUES (?, ?, ?)");
      if ($stmt === false) {
        die('Query prepare failed: ' . $conn->error);
      }
      $stmt->bind_param("iis", $id_galery, $id_venue, $nama_file);
      if ($stmt->execute()) {
        echo "<script>alert('Foto berhasil ditambahkan.'); window.location.href='info_mitra.php';</script>";
      } else {
        echo "<script>alert('Gagal menyimpan data ke database.');</script>";
      }
    } else {
      echo "<script>alert('Gagal mengunggah file.');</script>";
    }
  }

  // Hapus file
  if (isset($_POST['deleteImage'])) {
    $imageIdToDelete = $_POST['deleteImage'];
    $stmt = $conn->prepare("DELETE FROM venue_galery WHERE id_galery = ?");
    if ($stmt === false) {
      die('Query prepare failed: ' . $conn->error);
    }
    $stmt->bind_param("i", $imageIdToDelete);

    if ($stmt->execute()) {
      echo "<script>alert('Foto berhasil dihapus.'); window.location.href='info_mitra.php';</script>";
    } else {
      echo "<script>alert('Gagal menghapus foto dari database.');</script>";
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Info Mitra</title>
  <link rel="stylesheet" href="/ArenaFinder/css/info_mitra.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/924b40cfb7.js" crossorigin="anonymous"></script>
  <link rel="icon" href="/ArenaFinder/img_asset/login.png">
  <style>
    body {
      overflow-x: hidden;
      font-family: "Kanit", sans-serif;
    }

    .img-container {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .img-container img {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: 50px;
      max-width: 100%;
      height: 300px;
      border-bottom-right-radius: 50px;
      border-bottom-left-radius: 50px;
    }

    /* Menampilkan section1 terlebih dahulu */
    #section1 {
      display: block;
    }

    .link.active {
      color: #02406d;
    }

    .title-con {
      margin-left: 400px;
    }

    /* Menyembunyikan semua bagian kecuali section1 */
    section:not(#section1) {
      display: none;
    }

    .title button:active {
      background-color: #02406D;
    }

    .title-con {
      margin-left: 50%;
      transform: translateX(-50%);
    }

    #drop-menu {
      background-color: white;
      border: 1px solid #02406D;
    }

    .dropdown-divider {
      border: 1px solid #02406D;
    }

    /* Saat dropdown-item di-hover */
    .dropdown-menu a.dropdown-item:hover {
      background-color: #02406D;
      color: #A1FF9F;
    }

    /* Mengatur warna teks dan latar belakang default */
    .dropdown-menu a.dropdown-item {
      color: initial;
      /* Atur warna teks kembali ke nilai default */
      background-color: initial;
      /* Atur latar belakang kembali ke nilai default */
      color: #02406D;
    }


    #nav-down-item1 {
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 6px;
      color: white;
      border: 1px solid white;
      width: 100px;
      height: 30px;
      text-align: center;
    }

    #nav-down-item2 {
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 6px;
      color: #02406D;
      background-color: #A1FF9F;
      width: 100px;
      height: 30px;
      text-align: center;
    }

    #nav-down-item1:hover {
      background-color: white;
      color: #02406D;
      transition: 0.5s;
      transform: scale(1.1);
    }

    #nav-down-item1:active {
      color: white;
    }

    #nav-down-item2:hover {
      background-color: #A1FF9F;
      color: #02406D;
      transition: 0.5s;
      transform: scale(1.1);
    }

    #nav-down-item2:active {
      color: white;
    }

    .main-con {
      margin-left: 45px;
    }

    .social-buttons {
      text-align: end;
      margin-top: -75px;
      margin-right: 420px;
    }

    .social-button {
      display: inline-block;
      padding: 10px 20px;
      margin: 10px;
      text-align: end;
      color: white;
      text-decoration: none;
      font-weight: bold;
      border-radius: 5px;
      font-size: 20px;
      transition: transform 0.2s;
      /* Sesuaikan ukuran ikon */
    }



    .social-button:hover {
      background-color: #02406D;
      color: #A1FF9F;
      transform: translateY(-5px);
      /* Warna latar belakang saat tombol dihover */
    }

    .pengelola {
      margin-top: -825px;
      margin-left: 720px;
    }

    #fasilitasTempat {
      margin-left: -10px;
      display: flex;
      /* Menggunakan display: flex; untuk membuat tampilan horizontal */
      align-items: start;
      /* Untuk pusatkan elemen vertikal di dalam container */
      gap: 30px;
    }

    #fasilitasTempat img {
      margin: 0 10px;
      /* Spasi antara gambar */
      width: 100px;
      height: 100px;
      margin-top: 20px;
      border-radius: 10px;
    }

    #pengelolaTempat {
      margin-left: -20px;
      display: flex;
      /* Menggunakan display: flex; untuk membuat tampilan horizontal */
      align-items: start;
      /* Untuk pusatkan elemen vertikal di dalam container */
      gap: 20px;
    }

    #pengelolaTempat img {
      margin: 0 20px;
      /* Spasi antara gambar */
      width: 100px;
      height: 100px;
      margin-top: 20px;
      border-radius: 10px;
    }

    #pengelolaTempat h5 {
      margin: 0 30px;
      /* Spasi antara teks */
      padding-top: 20px;
    }

    .pengelola-item h5 {
      text-align: center;
      width: 80px;
    }

    .keanggotaan {
      margin-left: 720px;
      margin-top: 50px;
    }

    .lokasi {
      margin-left: 720px;
      margin-top: 50px;
    }

    .image-container {
      display: flex;
      flex-wrap: wrap;
      text-align: center;
      margin-left: 0px;
      margin-top: 30px;
      gap: 85px;
    }

    .file-input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
      text-align: center;
      justify-content: center;
      overflow: hidden;
      margin-top: 100px;
    }

    #card-body-4 {
      width: 100%;
      background-color: white;
      text-align: center;
      margin-top: 20px;
    }

    #btn-s4 {
      position: relative;
      width: 250px;
      height: 50px;
      margin-top: 20px;
      border: 1px solid #02406D;
      border-radius: 10px;
      background-color: white;
      color: #02406D;

    }

    #btn-s4:hover {
      background-color: #02406D;
      color: white;
      transition: 0.5s;
    }

    .cards-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-top: 20px;
      margin-left: -10px;
    }

    .card-text {
      margin: 5px;
    }

    #card-act img {
      width: 100%;
      height: 300px;
      border-radius: 10px;
      transition: transform 1s;
    }

    #card-act img:hover {
      transform: scale(1.1);
    }

    .footer {
      height: 300px;
      width: 100%;
      margin-left: 0px;
      margin-top: 250px;
      background-color: #02406d;
      font-family: "Kanit", sans-serif;
      color: white;
      padding: 20px;
      display: flex;
    }


    @media (max-width: 900px) {
      .img-container img {
        margin-left: -20px;
        max-width: 100%;
        height: 300px;
        border-bottom-right-radius: 50px;
        border-bottom-left-radius: 50px;
      }

      .title-con {
        margin-left: 80px;
      }

      .social-buttons {
        text-align: end;
        margin-top: -65px;
        margin-right: 60px;
      }

      .pengelola {
        margin-top: 30px;
        margin-left: -118px;
      }

      #pengelolaTempat {
        display: flex;
        /* Menggunakan display: flex; untuk membuat tampilan horizontal */
        align-items: start;
        /* Untuk pusatkan elemen vertikal di dalam container */
        gap: 20px;
      }

      #pengelolaTempat img {
        margin: 0 20px;
        /* Spasi antara gambar */
        width: 100px;
        height: 100px;
        margin-top: 20px;
        margin-left: 10px;
        border-radius: 20px;
      }

      #pengelolaTempat h5 {
        margin: 0 10px;
        /* Spasi antara teks */
        padding-top: 20px;
      }

      .keanggotaan {
        margin-left: -118px;
        margin-top: 30px;
      }

      .lokasi {
        margin-left: -118px;
        margin-top: 30px;
      }
    }
  </style>
</head>

<body>
  <nav class="navbar fixed-top navbar-expand-lg navbar-light" style="background-color: #02406D;">
    <div class="container">
      <a class="navbar-brand" href="#">
        <span style="font-family: 'Kanit', sans-serif; color: white;">Arena</span>
        <span style="font-family: 'Kanit', sans-serif; color: #A1FF9F;">Finder</span>
        <span style="font-family: 'Kanit', sans-serif; color: white;">|</span>
      </a>

      <button class="navbar-toggler bg-white" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto my-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="aktivitas.php">Aktivitas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="referensi.php">Referensi</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="">Info Mitra</a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto"> <!-- Menggunakan 'ml-auto' untuk komponen di akhir navbar -->
          <?php
          // Check if the user is logged in
          if (isset($_SESSION['email'])) {
            // User is logged in, show the "Panel Pengelola" button
            echo '<li class="nav-item dropdown" id="nav-down1">
                <a class="nav-link" id="nav-down-item1" href="/ArenaFinder/cpanel-admin-arenafinder/startbootstrap-sb-admin-2-gh-pages/index.php" style="width: 200px;">
                  <i class="fa-solid fa-id-card fa-flip" style="margin-right: 5px;"></i>
                  Panel Pengelola
                </a>
              </li>';
          } else {
            // User is not logged in, show the "Login" and "Register" buttons
            echo '<li class="nav-item dropdown" id="nav-down1">
                <a class="nav-link" id="nav-down-item1" href="/ArenaFinder/cpanel-admin-arenafinder/startbootstrap-sb-admin-2-gh-pages/login.php" style="width: 100px;">Masuk</a>
              </li>
              <li class="nav-item dropdown" id="nav-down1">
                <a class="nav-link" id="nav-down-item2" href="/ArenaFinder/cpanel-admin-arenafinder/startbootstrap-sb-admin-2-gh-pages/register.php" style="width: 100px;">Daftar</a>
              </li>';
          }
          ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- HTML untuk menampilkan gambar -->
  <div class="first-con">
    <div class="img-container">
      <div>
        <!-- Menampilkan gambar dari database -->
        <img src="<?php echo $imagePath; ?>" alt="Venue Photo">
      </div>
    </div>
  </div>

  <div class="title-con">
    <div class="box">
      <div class="group">
        <div class="rectangle"></div>
        <div class="div"></div>
      </div>
    </div>

    <div class="title">
      <h2><?php echo $nama_venue; ?></h2> <!-- Menampilkan nama venue -->
      <h5><?php echo $lapangan . " Lapangan"; ?></h5> <!-- Menampilkan jumlah lapangan -->
    </div>

  </div>

  <script>
    function simpanPerubahan() {
      // Ambil teks yang diedit dan simpan dalam variabel
      var editedText = document.getElementById('editableText').innerText;
      var harga1 = document.getElementById('harga1').innerText;
      var harga2 = document.getElementById('harga2').innerText;
      var harga3 = document.getElementById('harga3').innerText;
      var harga4 = document.getElementById('harga4').innerText;

      // Tampilkan notifikasi atau lakukan apa yang Anda inginkan dengan teks yang diedit
      alert('Perubahan Teks: ' + editedText + '\nHarga 1: ' + harga1 + '\nHarga 2: ' + harga2 + '\nHarga 3: ' + harga3 + '\nHarga 4: ' + harga4);
    }
  </script>

  <div class="main-con">
    <div class="nav-body">
      <div class="link-body">
        <nav>
          <ul>
            <li><a class="link" id="link1" href="#section1" data-target="section1">TENTANG</a></li>
            <li><a class="link" id="link2" href="#section2" data-target="section2">AKTIVITAS</a></li>
            <li><a class="link" id="link3" href="#section3" data-target="section3">MEMBER</a></li>
            <li><a class="link" id="link4" href="#section4" data-target="section4">GALERI</a></li>
            <li><a class="link" id="link5" href="#section5" data-target="section5">KONTAK</a></li>
          </ul>

        </nav>
      </div>
    </div>

    <section id="section1">
      <div class="tentang-con">
        <div class="deskripsi">
          <h3>Deskripsi</h3>
          <h6 id="editableText"><?= htmlspecialchars($deskripsi); ?></h6>

          <div class="harga">
            <h3>Rincian Harga</h3>
            <div id="rincianHarga">
              <h6>Harga Member
                <h6><strong>Rp. <?= number_format($harga_member, 0, ',', '.'); ?></strong></h6>
              </h6>
              <h6>Harga Non-Member
                <h6><strong>Rp. <?= number_format($harga, 0, ',', '.'); ?></strong></h6>
              </h6>
            </div>
          </div>



          <div class="fasilitas">
            <h3>Fasilitas Tempat</h3>
            <div id="fasilitasTempat">
              <div style="margin-bottom: 20px;">
                <img src="/ArenaFinder/img_asset/toilet.jpg" alt="">
                <h5 style="text-align: center;">Toilet</h5>
              </div>

              <div style="margin-bottom: 20px;">
                <img src="/ArenaFinder/img_asset/kursi.jpg" alt="">
                <h5 style="text-align: center; width: 80px;">Kursi Penonton</h5>
              </div>
              <div style="margin-bottom: 20px;">
                <img src="/ArenaFinder/img_asset/parkiran.jpg" alt="">
                <h5 style="text-align: center;">Area Parkir</h5>
              </div>
              <div style="margin-bottom: 20px;">
                <img src="/ArenaFinder/img_asset/alex-_AOL4_fDQ3M-unsplash.jpg" alt="">
                <h5 style="text-align: center; width: 80px;">Lapangan Futsal</h5>
              </div>
            </div>
          </div>

          <div class="pengelola">
            <h1>Pengelola</h1>
            <div id="pengelolaTempat">
              <div class="pengelola-item">
                <img src="/ArenaFinder/img_asset/alex-_AOL4_fDQ3M-unsplash.jpg" alt="">
                <h5>Admin</h5>
              </div>
              <div class="pengelola-item">
                <img src="/ArenaFinder/img_asset/alex-_AOL4_fDQ3M-unsplash.jpg" alt="">
                <h5>Super Admin</h5>
              </div>
            </div>
          </div>

          <div class="keanggotaan">
            <h3>Sistem Keanggotaan</h3>
            <div id="pengelolaTempat">
              <div class="pengelola-item">
                <img src="/ArenaFinder/img_asset/alex-_AOL4_fDQ3M-unsplash.jpg" alt="">
                <h5>Member</h5>
              </div>
              <div class="pengelola-item">
                <img src="/ArenaFinder/img_asset/alex-_AOL4_fDQ3M-unsplash.jpg" alt="">
                <h5>Non Member</h5>
              </div>
            </div>
          </div>

          <div class="lokasi">
            <h3>Lokasi</h3>
            <div id="pengelolaTempat">
              <div class="pengelola-item">
                <?php if ($latitude && $longitude): ?>
                  <iframe
                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAnmgFUsdMdCWQ6s3wcBr2Cd0Eolqu3Fqs&q=<?= htmlspecialchars($latitude); ?>,<?= htmlspecialchars($longitude); ?>&center=<?= htmlspecialchars($latitude); ?>,<?= htmlspecialchars($longitude); ?>&zoom=15"
                    width="440" height="350" style="border:0; margin-left: 20px; padding-top: 20px;" allowfullscreen=""
                    loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <?php else: ?>
                  <p>Lokasi tidak tersedia.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>




        </div>
      </div>
    </section>

    <section id="section2">
      <div class="tentang-con">
        <div class="deskripsi" style="width: 100%; margin-left: -65px;">
          <div class="card shadow"
            style="color: white; background: linear-gradient(to right, #02406D, 50%, white); border: none;">
            <div class="card-body">
              <h3>
                <h2><?php echo $nama_venue; ?></h2> <!-- Menampilkan nama venue -->
              </h3>
              <h6 id="editableText" style="color: white;"><strong style="color: #A1FF9F;"><?php echo $sport ?></strong> oleh <strong
                  style="color: #A1FF9F;"><?php echo $username ?></strong>
              </h6>
            </div>
          </div>
          <h3 style="margin-top: 30px;">Semua Aktivitas</h3>
        </div>

        <div class="cards-container">
          <?php

          $sql3 = "SELECT va.*, v.location, v.email, v.venue_name
          FROM venue_aktivitas va
          JOIN venues v ON va.id_venue = v.id_venue
          WHERE v.venue_name = 'Blessing Futsal'
          ORDER BY va.id_aktivitas DESC";

          $q3 = mysqli_query($conn, $sql3);
          $count = 0; // Untuk menghitung jumlah kartu pada setiap baris

          while ($row = mysqli_fetch_array($q3)) {
            // Membuka baris baru setiap kali 6 kartu telah ditampilkan
            if ($count % 4 == 0) {
              echo '</div><div class="card-container" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 70px; margin-top: 20px; margin-left: -125px;">';
            }

            // Card untuk data
            echo '<div class="card" style="flex: 0 0 calc(20% - 10px); background-color: white; border: none;">'; // Adjust flex properties for the desired width
            echo '<div class="card-body" style="display: flex; flex-direction: column; align-items: center; text-align: center; color: #02406d;">';

            $namaGambar = $row['photo'];
            $gambarURL = "/ArenaFinder/public/img/venue/" . $namaGambar;

            echo '<img src="' . $gambarURL . '" alt="Gambar" style="width: 250px; height: 250px; border-radius: 10px;">';
            echo '<h5 class="card-title mt-3">' . $row['nama_aktivitas'] . '</h5>';
            echo '<i class="fa-solid fa-location-dot"></i>';
            echo '<p class="card-text" style="overflow: hidden; word-wrap: break-word; white-space: normal;">' . $row['location'] . '</p>';
            echo '<p class="card-text">' . $row['date'] . '</p>';
            echo '<p class="card-text">' . $row['jam_main'] . ' Jam</p>';
            echo '<p class="card-text" hidden>' . $row['sport'] . '</p>';
            echo '<p class="card-text">Harga : Rp ' . $row['price'] . '</p>';

            echo '</div></div>';

            $count++;
          }

          echo '</div>';
          ?>

        </div>

      </div>

    </section>

  </div>

  <section id="section3">
    <div class="tentang-con">
      <div class="deskripsi" style="width: 100%; margin-left: -65px;">
        <div class="card shadow"
          style="color: white; background: linear-gradient(to right, #02406D, 50%, white); border: none;">
          <div class="card-body">
            <h3><?php echo $nama_venue ?></h3>
            <h6 id="editableText" style="color: white;"><strong style="color: #A1FF9F;"><?php echo $sport ?></strong> oleh <strong
                style="color: #A1FF9F;"><?php echo $username ?></strong>
            </h6>
          </div>
        </div>
        <h3 style="margin-top: 30px;">Semua Member</h3>

        <div class="cards-container">
          <?php
          // Simpan email ke dalam session
          $email = 'afrizal.alkautsar@gmail.com';

          // Fetch and display data from keanggotaan table
          // Menggunakan prepared statement untuk menghindari SQL injection
          $sql = "SELECT * FROM venue_membership WHERE email = ?";
          $stmt = mysqli_prepare($conn, $sql);

          // Check jika prepared statement berhasil dibuat
          if ($stmt) {
            // Bind parameter email
            mysqli_stmt_bind_param($stmt, "s", $email);

            // Execute statement
            mysqli_stmt_execute($stmt);

            // Fetch result
            $result = mysqli_stmt_get_result($stmt);

            $count = 0;

            echo '<div class="card-container" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 70px; margin-top: 35px; margin-left: 12px;">';

            while ($row = mysqli_fetch_assoc($result)) {
              echo '<div class="card" style="flex: 0 0 calc(20% - 10px); background-color: white; border: 1px solid #02406d; margin-right: 20px; margin-bottom: 20px; border-radius: 10px;">'; // Sesuaikan properti flex untuk lebar yang diinginkan
              echo '<div class="card-body" style="display: flex; flex-direction: column; align-items: center; text-align: center; color: #02406d;">';
              echo '<img src="https://cdn-icons-png.flaticon.com/512/1077/1077012.png" width="50" height="50" alt="" title="" class="img-small">';
              echo '<h3 style="margin-top: 10px;">' . $row['nama'] . '</h3>';
              echo '<p>' . $row['alamat'] . '</p>';
              echo '<p>' . $row['status'] . '</p>';
              echo '</div>';
              echo '</div>';

              $count++;
            }

            echo '</div>';
          } else {
            // Handle kesalahan pembuatan prepared statement
            echo "Error: " . mysqli_error($conn);
          }

          ?>
        </div>
      </div>
    </div>
  </section>

  <section id="section4">
    <div class="tentang-con">
      <?php
      // Jika pengguna sudah login, tampilkan elemen-elemen berikut
      if ($isLoggedIn) {
      ?>
        <div class="deskripsi" style="width: 100%; margin-left: -65px;">
          <div class="card shadow"
            style="color: white; background: linear-gradient(to right, #02406D, 50%, white); border: none;">
            <div class="card-body">
              <h3><?php echo $nama_venue ?></h3>
              <h6 id="editableText" style="color: white;"><strong style="color: #A1FF9F;"><?php echo $sport ?></strong> oleh <strong
                  style="color: #A1FF9F;"><?php echo $username ?></strong>
              </h6>
            </div>
          </div>
          <h3 style="margin-top: 30px;">Semua Foto</h3>
          <!-- Tampilkan data gambar -->
          <div class="image-container">
            <?php
            foreach ($imageData as $image) {
              $imageId = $image['id_galery'];
              $imageFilename = $image['photo'];
              $imagePath = "/ArenaFinder/public/img/venue/" . $imageFilename;
            ?>
              <div class="card" style="border: none;">
                <img src="<?= $imagePath; ?>" alt="Gambar" style="width: 250px; height: 250px; border-radius: 5px;">

                <!-- Tombol Delete -->
                <form action="" method="post">
                  <input type="hidden" name="deleteImage" value="<?= $imageId; ?>">
                  <button type="submit" name="submit"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus foto ini?')" id="btn-s4"
                    style="margin-bottom: 23px;">Hapus</button>
                </form>
              </div>
            <?php
            }
            ?>
          </div>
        </div>

        <form action="" method="post" enctype="multipart/form-data">
          <div class="file-input-wrapper">
            <div class="file-input-button">Pilih Gambar</div>
            <input type="file" name="fileUpload" id="fileUpload" accept="image/*">
            <button type="submit" name="submit" id="btn-s4">Upload dan Simpan</button>
          </div>

          <!-- Tambahkan input hidden untuk menyimpan ID gambar yang akan dihapus -->
          <input type="hidden" name="deleteImage" value="<?php echo $imageId; ?>">

        </form>


      <?php
      } else {
        // Jika pengguna belum login dan email tidak sesuai, tampilkan hanya data gambar
      ?>
        <div class="deskripsi" style="width: 100%; margin-left: -65px;">
          <div class="card shadow"
            style="color: white; background: linear-gradient(to right, #02406D, 50%, white); border: none;">
            <div class="card-body">
              <h3><?php echo $nama_venue ?> Gallery</h3>
              <h6 id="editableText" style="color: white;"><strong style="color: #A1FF9F;"><? echo $sport ?></strong> oleh <strong
                  style="color: #A1FF9F;"><?php echo $username  ?></strong>
              </h6>
            </div>
          </div>
          <h3 style="margin-top: 30px;">Semua Foto</h3>
        </div>
        <div class="image-container">
          <?php
          if (!empty($imageData)) {
            foreach ($imageData as $image) {
              $imageId = $image['id_galery'];
              $imageFilename = $image['photo'];
              $imagePath = "/ArenaFinder/public/img/venue/" . $imageFilename;
          ?>
              <div class="card" style="border: none;">
                <img src="<?= htmlspecialchars($imagePath); ?>" alt="Gambar" style="width: 250px; height: 250px; border-radius: 5px;">
              </div>
          <?php
            }
          } else {
            echo "<p>Tidak ada gambar tersedia.</p>";
          }
          ?>

        </div>
      <?php
      }

      ?>

    </div>
    </div>
    </div>
    </div>
  </section>

  <section id="section5">
    <div class="tentang-con">
      <div class="deskripsi" style="width: 100%; margin-left: -65px;">
        <div class="card shadow"
          style="color: white; background: linear-gradient(to right, #02406D, 50%, white); border: none;">
          <div class="card-body">
            <h3><?php echo $nama_venue ?> Contact Person</h3>
            <h6 id="editableText" style="color: white;"><strong style="color: #A1FF9F;"><?php echo $sport ?></strong> oleh <strong
                style="color: #A1FF9F;"><?php echo $username ?></strong>
            </h6>
          </div>
        </div>
        <h3 style="margin-top: 30px;">Semua Kontak</h3>
      </div>
      <div class="card" style="margin-top: 30px; margin-left: -70px; width: 500px; border: 1px solid white;">
        <div class="card-body" style="display: flex; align-items: center;">
          <img src="/ArenaFinder/img_asset/telepon.png" alt="" style="width: 20%; border-radius: 5px;">
          <h3 style="margin-left: 20px;">082247344544</h3>
          <div class="card"
            style="display: flex; width: 150px; text-align: center; height: 30px; background-color: #02406D; color: white; margin-left: 50px;">
            Pengelola
          </div>
        </div>
      </div>
    </div>
  </section>
  </div>


  <script>
    const navLinks = document.querySelectorAll(".link");

    // Tambahkan event listener untuk setiap tautan navigasi
    navLinks.forEach((link) => {
      link.addEventListener("click", (event) => {
        event.preventDefault();

        // Ambil target dari atribut data-target
        const targetId = link.getAttribute("data-target");

        // Sembunyikan semua bagian
        const sections = document.querySelectorAll("section");
        sections.forEach((section) => {
          section.style.display = "none";
        });

        // Tampilkan bagian yang sesuai dengan tautan yang diklik
        const targetSection = document.getElementById(targetId);
        targetSection.style.display = "block";

        // Hapus kelas "active" dari semua tautan
        navLinks.forEach((navLink) => {
          navLink.classList.remove("active");
        });

        // Tambahkan kelas "active" ke tautan yang diklik
        link.classList.add("active");
      });
    });
  </script>

  <div class="footer">
    <h1 style="font-size: 20px; color: white;">Arena</h1>
    <h1 style="font-size: 20px; color: #A1FF9F;">Finder</h1>
    <div class="hierarki">
      <p style="font-size: 20px; color: white; margin-left: 55px;">Hierarki
        <a href="index.php" style="margin-top: 10px;">Beranda</a>
        <a href="aktivitas.php">Aktivitas</a>
        <a href="referensi.php">Referensi</a>
        <a href="info_mitra.php">Info Mitra</a>
      </p>
      <p style="font-size: 20px; color: white; margin-left: 150px;">Bantuan
        <a href="bantuan.html" style="margin-top: 10px;">Apa saja layanan yang disediakan?</a>
        <a href="bantuan.html">Siapa target penggunanya?</a>
        <a href="bantuan.html">Bagaimana sistem ini bekerja?</a>
        <a href="bantuan.html">Saat kapan pengguna dapat mengetahui pesanan?</a>
        <a href="/ArenaFinder/cpanel-admin-arenafinder/startbootstrap-sb-admin-2-gh-pages/login.php">Masuk aplikasi??</a>
        <a href="/ArenaFinder/cpanel-admin-arenafinder/startbootstrap-sb-admin-2-gh-pages/register.php">Daftar aplikasi??</a>
      </p>
      <p style="font-size: 20px; color: white; margin-left: 100px;">Narahubung
        <a href="https://wa.me/6285785488403">https://wa.me/087860616270</a>
      </p>
      <p style="font-size: 20px; color: white; margin-left: 100px;">Aplikasi Mobile
        <a href="https://wa.me/62895807400305">Download Aplikasi?</a>
      </p>
    </div>
  </div>

  <!-- Include Bootstrap JS and jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


  <script src="/ArenaFinder/js/script.js"></script>
</body>

</html>