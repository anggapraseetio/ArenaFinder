<?php
session_start();
include('database.php'); // Include your database connection code here

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

$userName = $_SESSION['username'];

$sql = mysqli_query($conn, "SELECT username, level FROM users WHERE email = '$email'");
if ($sql) {
    $user_data = mysqli_fetch_assoc($sql);
    $name = $user_data['username'];
    $level = $user_data['level'];
    // Simpan level pengguna dalam sesi
} else {
    // Handle database error
    die("Database error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ArenaFinder - Profil</title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/924b40cfb7.js" crossorigin="anonymous"></script>
    <link rel="icon" href="/ArenaFinder/img_asset/login.png">
    <style>
        body {
            font-family: "Kanit", sans-serif;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #02406d;">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="profil.php">
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
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fa-solid fa-house-user"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Nav Item - Web -->
            <li class="nav-item">
                <a class="nav-link" href="../index.php">
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

            <!-- Nav Item - Keanggotaan -->
            <li class="nav-item ">
                <a class="nav-link" href="keanggotaan.php">
                    <i class="fa-solid fa-users"></i>
                    <span>Keanggotaan</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Notifikasi
            </div>

            <!-- Assuming this is your navigation link HTML -->
            <li class="nav-item">
                <a class="nav-link" href="pesanan.php">
                    <i class="fa-solid fa-cart-shopping">
                        <span class="badge badge-danger badge-counter"
                            style="background-color: #a1ff9f; color: #02406d; font-size: 15px;"
                            id="pesanan-link"></span>
                    </i>
                    <span>Pesanan
                        <?php $_SESSION['id_venue'] ?>
                    </span>
                </a>
            </li>

            <!-- Include jQuery -->
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

            <!-- Your Badge Script with AJAX -->
            <script>
                setInterval(function () {
                    function loadDoc() {
                        var xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function () {
                            if (this.readyState == 4 && this.status == 200) {
                                console.log("Response from check_data.php:", this.responseText); // Log the response
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
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"
                        style="color: #02406d;">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    Halo,
                                    <?php echo $userName ?>
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


                        <!-- Content Row -->
                        <div class="row">

                            <div class="col-xxl-8 col-12">
                                <!-- Approach -->
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3 d-flex"
                                        style="background-color: #02406d; color: white;">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 mt-2"></i>
                                        <h6 class="m-0 font-weight-bold">Profil <span
                                                style="color: #a1ff9f;">Akun</span>
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Username:</strong>
                                            <?php echo $name ?>
                                        </p>
                                        <p><strong>Email:</strong>
                                            <?php echo $email ?>
                                        </p>
                                        <p name="level"><strong>Level:</strong><span id="levelText">
                                                <?php echo $level; ?>
                                            </span></p>

                                        <a href="logout.php">
                                            <button class="btn-danger"
                                                style="border: none; border-radius: 5px; width: 70px; height: auto;">Logout</button></a>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; ArenaFinder 2023</span>
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
                        <a class="btn btn-primary" href="login.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

        <!-- Page level plugins -->
        <script src="vendor/chart.js/Chart.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="js/demo/chart-area-demo.js"></script>
        <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>