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
  <title>Aktivitas</title>
  <link rel="stylesheet" href="/ArenaFinder/css/aktivitas.css" />
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
      margin-top: 130px;
    }

    .semua-act {
      margin-left: 200px;
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

    .card-text {
      text-align: center;
      /* Menengahkan teks */
    }

    .fa-location-dot {
      margin-right: 10px;
      /* Memberikan jarak antara logo dan teks */
    }

    #title-con {
      width: 88.5rem;
      margin-left: 10px;
    }

    .card-container {
      width: 100% !important;
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

    .con-type {
      width: 80%;
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
      margin-top: 45px;
      height: 50px;
      width: fit-content;
      border-radius: 10px;
    }

    .con-main {
      margin-left: 30px;
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

    .semua-act .all-activity {
      padding: 10px;
    }

    .semua-act .all-activity1 {
      padding: 10px;
      margin-left: -10px;
      color: #A1FF9F;
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

    .footer {
      height: 300px;
      width: 88.5rem;
      margin-left: 0px;
      margin-top: 620px;
      background-color: #02406d;
      font-family: "Kanit", sans-serif;
      color: white;
      padding: 20px;
      display: flex;
    }
  </style>
</head>

<body>
  <nav class="navbar fixed-top navbar-expand-lg navbar-light" style="background-color: #02406D;">
    <div class="container-fluid">
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
            <a class="nav-link active" href="">Aktivitas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="referensi.php">Referensi</a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" href="info_mitra.php">Info Mitra</a>
          </li> -->
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
      <div class="title_activity">Aktivitas Komunitas
      </div>
      <div class="sub_title_activity">Berbagai macam akitivitas olahraga yang sedang berlangsung dan
        yang telah usai dilaksanakan, disajikan sesuai dengan
        kategori olahraga yang anda minati
      </div>
    </div>

    <!-- Include jQuery library (you can download and host it locally if needed) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <div class="con-type">
      <form method="post" action="" id="sportForm">
        <div style="display: flex;">
          <button class="semua" type="submit" name="sport" value="semua">All</button>
          <?php
          // Query to get unique sports from the venue_aktivitas table
          $sportsQuery = mysqli_query($koneksi, "SELECT DISTINCT sport FROM venue_aktivitas");
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

    <?php
    $selectedSport = 'Semua';
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Get the selected sport value
      $selectedSport = isset($_POST['sport']) ? $_POST['sport'] : 'Semua';
    }

    // Display the selected sport in the label
    echo '<div id="con-3">';
    echo '<div class="semua-act">';


    // Adjust the label based on the selected sport
    if ($selectedSport == 'Semua') {
      echo '<div class="all-activity">Semua</div>';
      echo '<div class="all-activity1">Aktivitas</div>';
    } else {
      echo '<div class="all-activity">Aktivitas</div>';
      echo '<div class="all-activity1">' . $selectedSport . '</div>';
    }

    echo '</div>';
    echo '</div>';
    echo '</div>';

    // Modify your SQL query to filter by the selected sport
    $sql3 = "SELECT va.*, v.location
              FROM venue_aktivitas va
              JOIN venues v ON va.id_venue = v.id_venue";

    // Append a WHERE clause to filter by sport
    if ($selectedSport != 'Semua') {
      $sql3 .= " WHERE va.sport = '$selectedSport'";
    }

    // Order the results by id_aktivitas in descending order
    $sql3 .= " ORDER BY va.id_aktivitas DESC";

    // Execute the modified query
    $q3 = mysqli_query($koneksi, $sql3);
    $count = 0; // Untuk menghitung jumlah kartu pada setiap baris

    while ($row = mysqli_fetch_array($q3)) {
      // Membuka baris baru setiap kali 4 kartu telah ditampilkan
      if ($count % 4 == 0) {
        echo '</div><div class="card-container" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px;">';
      }

      // Card untuk data
      echo '<div class="card">';
      echo '<div class="card-body">';

      $namaGambar = $row['photo'];
      $gambarURL = "/ArenaFinder/public/img/venue/" . $namaGambar;

      echo '<img src="' . $gambarURL . '" alt="Gambar">';
      echo '<h5 class="card-title mt-3">' . $row['nama_aktivitas'] . '</h5>';
      echo '<p class="card-text"><i class="fa-solid fa-location-dot"></i>' . $row['location'] . '</p>';
      echo '<p class="card-text">' . $row['date'] . '</p>';
      echo '<p class="card-text">' . $row['jam_main'] . ' Jam</p>';
      echo '<p class="card-text" hidden>' . $row['sport'] . '</p>';
      echo '<p class="card-text">Harga : Rp ' . $row['price'] . '</p>';

      echo '</div></div>';

      $count++;
    }
    ?>
    <!-- 
    <script>
      const container = document.querySelector('.con-type');
      let isDragging = false;
      let startX, currentX, scrollLeft;

      container.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.pageX - container.offsetLeft;
        scrollLeft = container.scrollLeft;
        container.style.scrollBehavior = 'auto';
      });

      container.addEventListener('mouseup', () => {
        isDragging = false;
        container.style.scrollBehavior = 'smooth';
      });

      container.addEventListener('mouseleave', () => {
        isDragging = false;
        container.style.scrollBehavior = 'smooth';
      });

      container.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
        currentX = e.pageX - container.offsetLeft;
        const scrollX = currentX - startX;
        container.scrollLeft = scrollLeft - scrollX;
      });

    </script> -->


  </div>


<div class="footer">
<h1 style="font-size: 20px; color: white;">Arena</h1>
    <h1 style="font-size: 20px; color: #A1FF9F;">Finder</h1>
        <div class="hierarki">
            <p style="font-size: 20px; color: white; margin-left: 55px;">Hierarki 
                <a href="index.php" style="margin-top: 10px;">Beranda</a>
                <a href="aktivitas.php">Aktivitas</a>
                <a href="referensi.php">Referensi</a>
                <!-- <a href="info_mitra.php">Info Mitra</a> -->
            </p>
            <p style="font-size: 20px; color: white; margin-left: 150px;">Bantuan
                <a href="bantuan.html" style="margin-top: 10px;">Apa saja layanan yang disediakan?</a>
                <a href="bantuan.html">Siapa target penggunanya?</a>
                <a href="bantuan.html">Bagaimana sistem ini bekerja?</a>
                <a href="bantuan.html">Bagaimana cara daftar lapangan?</a>
                <a href="/ArenaFinder/cpanel-admin-arenafinder/startbootstrap-sb-admin-2-gh-pages/login.php">Masuk aplikasi?</a>
                <a href="/ArenaFinder/cpanel-admin-arenafinder/startbootstrap-sb-admin-2-gh-pages/register.php">Daftar aplikasi?</a>
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

</body>

</html>