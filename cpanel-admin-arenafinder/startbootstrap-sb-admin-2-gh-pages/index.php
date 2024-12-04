<?php
session_start();
include("database.php");

if (!isset($_SESSION['email'])) {
    // Jika pengguna belum masuk, arahkan mereka kembali ke halaman login
    header("Location: login.php");
    exit();
}

// Pengguna sudah masuk, Anda dapat mengakses data sesi
$email = $_SESSION['email'];
$level = $_SESSION['level'];
$id_venue = $_SESSION['id_venue'];
$userName = $_SESSION['username'];
$sportFromDB = $_SESSION['sport'];

// Query SQL untuk menghitung jumlah jadwal yang sudah dipesan di tabel venue_booking
$sql = "SELECT COUNT(*) as jadwal_dipesan FROM venue_booking WHERE id_venue = '$id_venue'";
$q2 = mysqli_query($conn, $sql);

if ($q2->num_rows > 0) {
    // Ambil hasil query`
    $row = $q2->fetch_assoc();
    $totalPesan = $row["jadwal_dipesan"];
} else {
    echo "Data jadwal dipesan tidak ditemukan.";
}

// Query SQL untuk menghitung jumlah jadwal yang belum dipesan/kosong di tabel venue_price
$sql = "SELECT COUNT(*) as jadwal_kosong FROM venue_price WHERE id_venue = '$id_venue'";
$q2 = mysqli_query($conn, $sql);

if ($q2->num_rows > 0) {
    // Ambil hasil query`
    $row = $q2->fetch_assoc();
    $totalBelumDipesan = $row["jadwal_kosong"];
} else {
    echo "Data jadwal belum dipesan tidak ditemukan.";
}

// Tutup koneksi ke database
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ArenaFinder - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">


    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/924b40cfb7.js" crossorigin="anonymous"></script>
    <link rel="icon" href="/ArenaFinder/img_asset/login.png">
    <style>
        /* custom.css */
        body {
            font-family: "Kanit", sans-serif;
        }

        #kartu {
            transition: transform 0.2s;
        }

        #kartu:hover {
            transform: translateY(-5px);
        }

        #kartu a:hover {
            text-decoration: none;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #02406d;">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon">
                    <i class="fa-solid fa-circle-user mx-3 ml-auto"></i>
                </div>
                <div class="sidebar-brand-text" style="text-transform: none; font-weight: 500; font-size: 20px">Arena
                </div>
                <div class="sidebar-brand-text"
                    style="color: #a1ff9f; text-transform: none; font-weight: 500; font-size: 20px">Finder <span
                        style="color: white;">|</span></div>

            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fa-solid fa-house-user"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Nav Item - Web -->
            <li class="nav-item">
                <a class="nav-link" href="/ArenaFinder/php/index.php">
                    <i class="fa-brands fa-edge"></i>
                    <span>Lihat Website</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Pengelolaan Data
            </div>

            <!-- Nav Item - Jadwal Menu -->
            <li class="nav-item ">
                <a class="nav-link" href="jadwal.php">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Jadwal Lapangan</span></a>
            </li>

            <!-- Nav Item - Aktivitas Menu -->
            <li class="nav-item ">
                <a class="nav-link" href="aktivitas.php">
                    <i class="fa-solid fa-fire"></i>
                    <span>Aktivitas</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Notifikasi
            </div>

            <!-- Nav Item - Pesanan -->
            <li class="nav-item">
                <a class="nav-link" href="pesanan.php">
                    <i class="fa-solid fa-cart-shopping">
                        <span class="badge badge-danger badge-counter"
                            style="background-color: #a1ff9f; color: #02406d; font-size: 15px;"
                            id="pesanan-link"></span>
                    </i>
                    <span>Pesanan</span></a>
            </li>

            <!-- Include jQuery -->
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

            <!-- Your Badge Script with AJAX -->
            <script>
                setInterval(function() {
                    function loadDoc() {
                        var xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                document.getElementById("pesanan-link").innerHTML = this.responseText;
                            }
                        };
                        xhttp.open("GET", "check_badge.php", true);
                        xhttp.send();
                    }
                    loadDoc();
                }, 1000);
            </script>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column" style="background-color: white;">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"
                        style="color: #02406d;">
                        <i class="fa fa-bars"></i>
                    </button>

                    <div class="d-sm-flex align-items-center justify-content-between mb-3">
                        <i class="fa-solid fa-house-user mt-3 mr-3" style="color: #02406d;"></i>
                        <h1 class="h3 mr-2 mt-4" style="color: #02406d; font-size: 20px; font-weight: bold;">Dashboard
                        </h1>
                    </div>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Halo,
                                    <?php echo $userName; ?>
                                </span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>

                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profil.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Schedule On Card -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card shadow h-100 py-2" id="kartu"
                                style="border-left-color: #a1ff9f; border-left-width: 5px; background-color: #02406d;">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <a href="jadwal.php">
                                                <div class="text-xs font-weight-bold text-uppercase mb-1"
                                                    style="color: white; font-size: 19px;">
                                                    Jadwal <span style="color: #a1ff9f;">Dipesan</span></div>
                                                <div class="h2 mb-0 font-weight-bold text-white">
                                                    <?php echo $totalPesan; ?>
                                                </div>
                                            </a>

                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-regular fa-calendar-xmark fa-5x text-white"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Off Card -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card shadow h-100 py-2" id="kartu"
                                style="border-left-color: #a1ff9f; border-left-width: 5px; background-color: #02406d;">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <a href="jadwal.php">
                                                <div class="text-xs font-weight-bold text-uppercase mb-1"
                                                    style="color: white; font-size: 19px;">
                                                    Jadwal <span style="color: #a1ff9f;">Kosong</span></div>
                                                <div class="h2 mb-0 font-weight-bold text-white">
                                                    <?php echo $totalBelumDipesan; ?>
                                                </div>
                                            </a>

                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-solid fa-calendar-check fa-5x text-white"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card shadow h-100 py-2" id="kartu"
                                style="border-left-color: #a1ff9f; border-left-width: 5px; background-color: #02406d;">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <a href="pesanan.php">
                                                <div class="text-xs font-weight-bold text-uppercase mb-1 text-white" style="color: white; font-size: 19px;">
                                                    Kuota
                                                    <span style="color: #a1ff9f;">Pesanan</span>
                                                </div>
                                            </a>
                                            <div class="row no-gutters align-items-center">
                                                <?php
                                                // Query SQL untuk menghitung total jadwal berdasarkan status pembayaran
                                                // "Pending" atau "Rejected"
                                                include("database.php");

                                                // Query SQL untuk menghitung jumlah jadwal yang belum dipesan/kosong di tabel venue_price
                                                $sqlJadwalKosong = "SELECT COUNT(*) as jadwal_kosong FROM venue_price WHERE id_venue = '$id_venue'";
                                                $qJadwalKosong = mysqli_query($conn, $sqlJadwalKosong);

                                                if ($qJadwalKosong->num_rows > 0) {
                                                    // Ambil hasil query
                                                    $rowJadwalKosong = $qJadwalKosong->fetch_assoc();
                                                    $totalJadwalKosong = $rowJadwalKosong["jadwal_kosong"];

                                                    // Query SQL untuk menghitung total jadwal dengan payment_status = 'Pending'
                                                    $sqlPending = "SELECT COUNT(*) as total_pending FROM venue_booking WHERE id_venue = '$id_venue' AND payment_status = 'Pending'";
                                                    $qPending = mysqli_query($conn, $sqlPending);

                                                    // Query SQL untuk menghitung total jadwal dengan payment_status = 'Accepted'
                                                    $sqlAccepted = "SELECT COUNT(*) as total_accepted FROM venue_booking WHERE id_venue = '$id_venue' AND payment_status = 'Accepted'";
                                                    $qAccepted = mysqli_query($conn, $sqlAccepted);

                                                    if ($qPending->num_rows > 0 && $qAccepted->num_rows > 0) {
                                                        $rowPending = $qPending->fetch_assoc();
                                                        $totalPending = $rowPending["total_pending"];

                                                        $rowAccepted = $qAccepted->fetch_assoc();
                                                        $totalAccepted = $rowAccepted["total_accepted"];

                                                        // Hitung persentase jadwal yang masih pending
                                                        // $persentasePending = ($totalPending / ($totalPending + $totalAccepted)) * 100;
                                                        if ($totalPending + $totalAccepted > 0) {
                                                            $persentasePending = ($totalPending / ($totalPending + $totalAccepted)) * 100;
                                                        } else {
                                                            $persentasePending = 0; // Atau nilai default lainnya jika totalPending + totalAccepted = 0
                                                        }


                                                        // Menampilkan hasil persentase ke dalam elemen sebelumnya
                                                        echo '<div class="col-auto">';
                                                        echo '    <div class="h2 mb-0 mr-3 font-weight-bold text-white">' . number_format($persentasePending, 2) . '%</div>';
                                                        echo '</div>';
                                                        echo '<div class="col">';
                                                        echo '    <div class="progress progress-sm mr-2">';
                                                        echo '        <div class="progress-bar" role="progressbar" style="width: ' . number_format($persentasePending, 2) . '%; background-color: #a1ff9f;" aria-valuenow="' . number_format($persentasePending, 2) . '" aria-valuemin="0" aria-valuemax="100">';
                                                        echo '        </div>';
                                                        echo '    </div>';
                                                        echo '</div>';
                                                    } else {
                                                        echo "Gagal mengambil total jadwal.";
                                                    }
                                                } else {
                                                    echo "Data jadwal kosong tidak ditemukan.";
                                                }

                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-5x text-white"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">


                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; ArenaFinder 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Logout</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Akhiri aktivitas?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap CSS and JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>



</body>

</html>