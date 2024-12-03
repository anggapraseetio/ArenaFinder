<?php
session_start();
// $host = "localhost";
// $user = "tifz1761_root";
// $pass = "tifnganjuk321";
// $db = "tifz1761_arenafinder";
$host = "localhost";
$user = "root";
$pass = "";
$db = "arenafinder1";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
  die("Tidak bisa terkoneksi");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Referensi</title>
  <link rel="stylesheet" href="/ArenaFinder/css/referensi.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/924b40cfb7.js" crossorigin="anonymous"></script>
  <link rel="icon" href="/ArenaFinder/img_asset/login.png">
  <style>
    .title_activity {
      margin-top: 0px;
      margin-left: 40px;
      font-size: 35px;
      font-weight: 500;
      color: #02406d;
    }

    #drop-menu {
      background-color: white;
      border: 1px solid #02406d;
    }

    .dropdown-divider {
      border: 1px solid #02406d;
    }

    /* Saat dropdown-item di-hover */
    .dropdown-menu a.dropdown-item:hover {
      background-color: #02406d;
      color: #a1ff9f;
    }

    /* Mengatur warna teks dan latar belakang default */
    .dropdown-menu a.dropdown-item {
      color: initial;
      /* Atur warna teks kembali ke nilai default */
      background-color: initial;
      /* Atur latar belakang kembali ke nilai default */
      color: #02406d;
    }

    #auth-con {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-left: 75px;
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
      color: #02406d;
      background-color: #a1ff9f;
      width: 100px;
      height: 30px;
      text-align: center;
    }

    #nav-down-item1:hover {
      background-color: white;
      color: #02406d;
      transition: 0.5s;
      transform: scale(1.1);
    }

    #nav-down-item1:active {
      color: white;
    }

    #nav-down-item2:hover {
      background-color: #a1ff9f;
      color: #02406d;
      transition: 0.5s;
      transform: scale(1.1);
    }

    #nav-down-item2:active {
      color: white;
    }

    .card-container {
      margin-top: -52px;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
    }

    .card {
      width: 300px;
      margin-top: 50px;
      text-align: center;
      border: none;
    }

    .card-text {
      margin: 5px;
    }

    .card img {
      width: 100%;
      height: 300px;
      border-radius: 10px;
      transition: transform 1s;
    }

    .card img:hover {
      transform: scale(1.1);
    }

    .tipe-lap {
      display: flex;
      width: 500px;
      height: 100%;
      gap: 20px;
      margin-top: -48px;
      margin-left: 2rem;
    }

    .tipe-lap button {
      border: none;
      border-radius: 10px;
      width: 100px;
      position: relative;
      overflow: hidden;
      background-color: white;
      color: #02406d;
      box-shadow: 0 0 5px #02406d;
      transition: box-shadow 0.3s ease;
    }

    .fourth-sep {
      height: 50px;
      width: 5px;
      background-color: #02406d;
      margin-left: 13rem;
      margin-top: -3rem;
      border-radius: 10px;
    }

    #ref-btn {
      width: 200px;
      height: auto;
      background-color: white;
      display: flex;
      align-items: center;
      padding-left: 30px;
      border-radius: 10px;
      text-decoration: none;
      color: #02406d;
      box-shadow: 0 0 5px #02406d;
      transition: box-shadow 0.3s ease;
    }

    #ref-btn:hover {
      background-color: #02406d;
      color: white;
      transition: 1s;
      box-shadow: 0 0 10px #02406d;
    }

    .tipe-lap img {
      width: 100%;
      height: 100%;
      position: absolute;
      top: 100%;
      left: 0;
      opacity: 0;
      transition: top 0.3s ease, opacity 0.3s ease;
    }

    .tipe-lap button:hover img {
      top: 0;
      opacity: 1;
    }

    .con-type {
      width: 90%;
      display: flex;
      overflow: hidden;
      white-space: nowrap;
      touch-action: cross-slide-x;
      user-select: none;
      margin-top: 30px;
      margin-right: 20px;
      margin-left: 50px;
      position: relative;
    }

    .con-type button {
      display: flex;
      flex-direction: column;
      align-items: center;
      border: none;
      background: none;
      cursor: pointer;
    }

    .con-type img {
      max-width: 100%;
      height: auto;
      /* Atur ukuran margin sesuai preferensi Anda */
    }

    button.semua {
      width: 60px;
      height: 60px;
      padding-top: 10px;
      margin-top: 20px;
      margin-left: 0px;
      margin-right: 15px;
      text-align: center;
      border: 1px solid #02406d;
      border-radius: 10px;
      background-color: white;
      color: #02406d;
      font-size: 25px;
      font-weight: regular;
      animation: slideRight 2s ease-in-out;
    }

    .semua {
      margin-top: 30px;
      margin-left: 0px;
      border: 1px solid #02406d;
      border-radius: 10px;
      background-color: white;
      color: #02406d;
      font-size: 25px;
      font-weight: regular;
      animation: slideRight 2s ease-in-out;
    }

    button.all {
      width: 100px;
      height: 100px;
    }

    .all img {
      width: 60px;
      height: 60px;
      border: 1px solid white;
      border-radius: 10px;
      margin-bottom: 15px;
      transition: scale 1s;
    }

    .all img:hover {
      scale: 105%;
    }

    .semua-act {
      display: flex;
      text-align: justify;
      justify-content: flex-start;
      background-color: #02406d;
      color: white;
      margin-left: 50px;
      height: 50px;
      width: fit-content;
      border-radius: 10px;
    }

    .con-main {
      margin-left: 30px;
    }

    #con-3 {
      margin-top: -54px;
    }

    #swipe-btn {
      position: absolute;
      right: 0;
      margin-right: 150px;
      margin-top: 30px;
      font-size: 12px;
      animation: slideRight 2s ease-in-out;
      font-weight: lighter;
      color: #02406d;
    }

    #title-con {
      margin-top: -5px;
      width: 88.5rem;
      margin-left: 10px;
    }

    @keyframes slideRight {
      0% {
        transform: translateX(100%);
        /* Elemen dimulai dari bawah */
        opacity: 0;
        /* Elemen transparan saat dimulai */
      }

      100% {
        transform: translateX(0%);
        /* Elemen dimulai dari bawah */
        opacity: 1;
        /* Elemen transparan saat dimulai */
      }
    }

    section {
      display: none;
      /* Sembunyikan semua section secara default */
    }

    section.show {
      display: block;
      /* Tampilkan section yang memiliki kelas 'show' */
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
      .con-type {
        width: 85%;
        display: flex;
        overflow: hidden;
        white-space: nowrap;
        touch-action: pan-y;
        user-select: none;
        margin-top: 50px;
        margin-right: 20px;
        margin-left: 50px;
        position: relative;
      }

      .con-type button {
        display: flex;
        flex-direction: column;
        align-items: center;
        border: none;
        background: none;
        cursor: pointer;
      }

      .con-type img {
        max-width: 100%;
        height: auto;
        /* Atur ukuran margin sesuai preferensi Anda */
      }

      button.semua {
        width: 60px;
        height: 60px;
        margin-top: 20px;
        margin-left: 0px;
        margin-right: 15px;
        border: 1px solid #02406d;
        border-radius: 10px;
        background-color: white;
        color: #02406d;
        font-size: 25px;
        font-weight: regular;
        animation: slideRight 2s ease-in-out;
      }

      .all img {
        width: 60px;
        height: 60px;
        border: 1px solid white;
        border-radius: 10px;
        margin-bottom: 15px;
        transition: scale 1s;
      }

      .all img:hover {
        scale: 105%;
      }

      .semua-act {
        display: flex;
        text-align: justify;
        justify-content: flex-start;
        background-color: #02406d;
        color: white;
        margin-left: 50px;
        height: 50px;
        width: fit-content;
        border-radius: 10px;
      }

      .con-main {
        margin-left: 10px;
      }

      .semua-act {
        margin-left: 35px;
      }

      .footer {
        margin-left: -100px;
      }

      #swipe-btn {
        position: absolute;
        right: 0;
        margin-right: 20px;
        margin-top: 30px;
        font-size: 12px;
        animation: slideRight 2s ease-in-out;
        font-weight: lighter;
        color: #02406d;
      }

      .footer {
        margin-left: -100px;
      }
    }
  </style>
</head>

<body>
  <nav class="navbar fixed-top navbar-expand-lg navbar-light" style="background-color: #02406d">
    <div class="container">
      <a class="navbar-brand" href="#">
        <span style="font-family: 'Kanit', sans-serif; color: white">Arena</span>
        <span style="font-family: 'Kanit', sans-serif; color: #a1ff9f">Finder</span>
        <span style="font-family: 'Kanit', sans-serif; color: white">|</span>
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
            <a class="nav-link active" href="">Referensi</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="info_mitra.php">Info Mitra</a>
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

  <div class="box">
    <div class="group">
      <div class="rectangle"></div>
      <div class="div"></div>
    </div>
  </div>

  <div class="con-main">
    <div id="title-con">
      <div class="title_activity">Referensi Tempat Olahraga</div>
      <div class="sub_title_activity">
        Berbagai macam lapangan olahraga yang ada di Kabupaten Nganjuk baik
        indoor maupun outdoor
      </div>
    </div>

    <!-- Include jQuery library (you can download and host it locally if needed) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <div class="con-type">
      <form method="post" action="" id="sportForm">
        <div style="display: flex;">
          <button class="semua" type="submit" name="all" value="Semua">All</button>
          <?php
          // Query to get unique sports from the venues table
          $sportsQuery = mysqli_query($koneksi, "SELECT DISTINCT sport FROM venues");
          $sports = mysqli_fetch_all($sportsQuery, MYSQLI_ASSOC);

          // Loop through each sport and generate a button
          foreach ($sports as $sport) {
            $sportName = $sport['sport'];
            ?>

            <button class="all" type="submit" name="sport" value="<?php echo $sportName; ?>"
              data-sport-name="<?php echo $sportName; ?>">
              <!-- You may want to use a more specific image for each sport -->
              <img src="/ArenaFinder/img_asset/<?php echo strtolower($sportName); ?>.jpg" alt="" />
              <span>
                <?php echo $sportName; ?>
              </span>
            </button>
            <?php
          }
          ?>
        </div>
      </form>
    </div>

    <script>
      const container = document.querySelector(".con-type");
      let isDragging = false;
      let startX, currentX, scrollLeft;

      container.addEventListener("mousedown", (e) => {
        isDragging = true;
        startX = e.pageX - container.offsetLeft;
        scrollLeft = container.scrollLeft;
        container.style.scrollBehavior = "auto";
      });

      container.addEventListener("mouseup", () => {
        isDragging = false;
        container.style.scrollBehavior = "smooth";
      });

      container.addEventListener("mouseleave", () => {
        isDragging = false;
        container.style.scrollBehavior = "smooth";
      });

      container.addEventListener("mousemove", (e) => {
        if (!isDragging) return;
        e.preventDefault();
        currentX = e.pageX - container.offsetLeft;
        const scrollX = currentX - startX;
        container.scrollLeft = scrollLeft - scrollX;
      });
    </script>

    <div id="con-3">
      <div class="semua-act">
        <div class="all-activity">Semua</div>
        <div class="all-activity1">Lapangan</div>
      </div>

      <div class="fourth-sep">
        <div class="tipe-lap">
          <button id="indoorButton" onclick="showCards('Indoor')">Indoor<img src="/ArenaFinder/img_asset/bulu tangkis.jpg"
              alt="" /></button>
          <button id="outdoorButton" onclick="showCards('Outdoor')">Outdoor<img src="/ArenaFinder/img_asset/outdoor.jpg"
              alt="" /></button>

          <?php
          // Ambil level pengguna dari sesi
          if (isset($_SESSION['email']) && $_SESSION['email'] != null) {
            $userEmail = $_SESSION['email'];

            // Tampilkan tombol "Tambah Referensi" hanya jika level pengguna adalah "superadmin"
            if ($userEmail == 'tengkufarkhan3@gmail.com') {
              echo '<a id="ref-btn" href="add_referensi.php">Tambah Referensi +</a>';
            } else {
              echo '<a id="ref-btn" href="add_referensi.php" style="display: none;">Tambah Referensi +</a>';
            }

            // Pesan peringatan saat logout
            if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
              echo '<p style="color: red;">Anda telah berhasil logout. Terima kasih!</p>';
            }
          } // No else block here, which means no error message will be displayed for non-logged in users
          ?>

        </div>
      </div>
    </div>
  </div>

  <div class="cards-container">
    <div class="card-container"
      style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px; margin-top: 50px;">
      <?php
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['sport'])) {
          $selectedSport = $_POST['sport'];
          $sqlFilter = "SELECT * FROM venues WHERE sport = '$selectedSport' ORDER BY id_venue DESC";
        } else {
          $sqlFilter = "SELECT * FROM venues ORDER BY id_venue DESC";
        }
      } else {
        $sqlFilter = "SELECT * FROM venues ORDER BY id_venue DESC";
      }

      $q3 = mysqli_query($koneksi, $sqlFilter);
      $count = 0;

      while ($row = mysqli_fetch_array($q3)) {
        // Membuka baris baru setiap kali 4 kartu telah ditampilkan
        if ($count % 4 == 0) {
          echo '</div><div class="card-container" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px;">';
        }

        // Card untuk data
        echo '<div class="card" data-tipe-lap="' . $row['sport_status'] . '">';
        echo '<div class="card-body">';

        $namaGambar = $row['venue_photo'];
        $gambarURL = "http://localhost/ArenaFinder/public/img/venue/" . $namaGambar;

        echo '<img src="' . $gambarURL . '" alt="Gambar" >';
        echo '<h5 class="card-title mt-3" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">' . $row['venue_name'] . '</h5>';
        echo '<p class="card-text"><i class="fa-solid fa-location-dot"></i><span style="word-wrap: break-word; max-width: 300px; display: block;">' . $row['location'] . '</span></p>';
        echo '<p class="card-text" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">' . $row['total_lapangan'] . ' Lapangan</p>';
        echo '<p class="card-text" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">Harga : Rp ' . $row['price'] . '</p>';
        echo '<p class="card-text" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;" hidden>' . $row['sport'] . '</p>';

        echo '</div></div>';

        $count++;
      }
      ?>
    </div>
  </div>


  <section id="section1">
    <div class="cards-container">
      <div class="card-container"
        style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px; margin-top: 50px;">
        <?php
        $tipe_lapangan = 'Indoor';
        $sql3 = "SELECT * FROM venues WHERE sport_status = '$tipe_lapangan'";
        $q3 = mysqli_query($koneksi, $sql3);
        $count = 0; // Untuk menghitung jumlah kartu pada setiap baris
        
        while ($row = mysqli_fetch_array($q3)) {
          // Membuka baris baru setiap kali 4 kartu telah ditampilkan
          if ($count % 4 == 0) {
            echo '</div><div class="card-container" style="display: flex; justify-content: center; gap: 10px;">';
          }

          // Card untuk data
          echo '<div class="card" data-tipe-lap="' . $row['sport_status'] . '">';
          echo '<div class="card-body">';

          $namaGambar = $row['venue_photo'];
          $gambarURL = "http://localhost/ArenaFinder/public/img/venue/" . $namaGambar;

          echo '<img src="' . $gambarURL . '" alt="Gambar" >';
          echo '<h5 class="card-title mt-3" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">' . $row['venue_name'] . '</h5>';
          echo '<p class="card-text"><i class="fa-solid fa-location-dot"></i><span style="word-wrap: break-word; max-width: 300px; display: block;">' . $row['location'] . '</span></p>';
          echo '<p class="card-text" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">' . $row['total_lapangan'] . ' Lapangan</p>';
          echo '<p class="card-text" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">Harga : Rp ' . $row['price'] . '</p>';
          echo '<p class="card-text" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;" hidden>' . $row['sport_status'] . '</p>';

          echo '</div></div>';

          $count++;
        }
        ?>
      </div>
    </div>
  </section>

  <section id="section2">
    <div class="cards-container">
      <div class="card-container"
        style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px; margin-top: 50px;">
        <?php
        $tipe_lapangan = 'Outdoor';
        $sql3 = "SELECT * FROM venues WHERE sport_status = '$tipe_lapangan'";
        $q3 = mysqli_query($koneksi, $sql3);
        $count = 0; // Untuk menghitung jumlah kartu pada setiap baris
        
        while ($row = mysqli_fetch_array($q3)) {
          // Membuka baris baru setiap kali 4 kartu telah ditampilkan
          if ($count % 4 == 0) {
            echo '</div><div class="card-container" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px;">';
          }

          // Card untuk data
          echo '<div class="card" data-tipe-lap="' . $row['sport_status'] . '">';
          echo '<div class="card-body">';

          $namaGambar = $row['venue_photo'];
          $gambarURL = "http://localhost/ArenaFinder/public/img/venue/" . $namaGambar;

          echo '<img src="' . $gambarURL . '" alt="Gambar" >';
          echo '<h5 class="card-title mt-3" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">' . $row['venue_name'] . '</h5>';
          echo '<p class="card-text"><i class="fa-solid fa-location-dot"></i><span style="word-wrap: break-word; max-width: 300px; display: block;">' . $row['location'] . '</span></p>';
          echo '<p class="card-text" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">' . $row['total_lapangan'] . ' Lapangan</p>';
          echo '<p class="card-text" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">Harga : Rp ' . $row['price'] . '</p>';
          echo '<p class="card-text" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;" hidden>' . $row['sport_status'] . '</p>';

          echo '</div></div>';

          $count++;
        }
        ?>
      </div>
    </div>
  </section>


  <script>
    function showCards(category) {
      var cards = document.querySelectorAll('.card');

      cards.forEach(function (card) {
        var tipeLap = card.getAttribute('data-tipe-lap');

        // Tampilkan hanya kartu dengan kategori yang sesuai
        if (tipeLap === category || category === 'All') {
          card.style.display = 'flex';
        } else {
          card.style.display = 'none';
        }
      });
    }
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

  <script>
    $(document).ready(function () {
      $("#ref-btn").popover({
        content: "Menu ini hanya bisa diakses oleh super admin/developer",
        trigger: "hover",
        placement: "top",
      });
    });
  </script>
  <script>
    // Simulasikan status login admin (ganti dengan kode sesuai aplikasi Anda)
    const isAdmin = false; // Ganti menjadi true jika pengguna adalah admin

    document.addEventListener("DOMContentLoaded", function () {
      const refBtn = document.getElementById("ref-btn");

      refBtn.addEventListener("click", function (event) {
        if (isAdmin) {
          event.preventDefault(); // Mencegah tautan dari diikuti
          alert("Anda adalah admin. Anda tidak dapat mengakses tautan ini.");
        }
      });
    });
  </script>
</body>

</html>