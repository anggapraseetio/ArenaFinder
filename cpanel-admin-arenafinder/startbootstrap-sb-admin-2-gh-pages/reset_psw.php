<?php
session_start();
include('database.php');



?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Reset Password</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="icon" href="/ArenaFinder/img_asset/login.png">
    <!------ Include the above in your HEAD tag ---------->
    <style>
        body {
            font-family: "Kanit", sans-serif;
        }

        #btn-reset {
            background-color: #e7f5ff;
            color: #02406d;
        }

        #btn-reset:hover {
            background-color: #02406d;
            color: #e7f5ff;
            border: 1px solid #e7f5ff;
        }

        .small {
            color: #02406d;
        }

        .small:hover {
            color: #02406d;
            text-decoration: underline;
        }

        .message {
            background-color: #ffdddd;
            color: #8b0000;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>

</head>

<body class="bg-gradient" style="background-color: #e7f5ff">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-5 ">
                <div class="card o-hidden border-0 shadow-lg my-4" style="height: 700px;">
                    <div class="card-body p-10" id="card-email">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-10 mx-auto p-2">
                                <div class="p-3">
                                    <div class="text-center">
                                        <h1 class="h2 text-gray-900 mb-2 ">Ganti Sandi</h1>
                                        <p class="mb-4">Silahkan masukkan sandi akun Anda yang baru!</p>


                                        <?php
                                        // Validasi token dari URL
                                        if (!isset($_GET['token']) || empty($_GET['token'])) {
                                            die("Token tidak ditemukan.");
                                        }

                                        // Escape dan trim token
                                        $tokenFromUrl = mysqli_real_escape_string($conn, trim($_GET['token']));

                                        // echo "Token from URL: ";
                                        // var_dump($tokenFromUrl);
                                        // echo "<br>";

                                        // $query = "SELECT reset_token,token_expiration FROM users WHERE reset_token='$tokenFromUrl'";
                                        // $result = mysqli_query($conn, $query);

                                        // if ($result && mysqli_num_rows($result) > 0) {
                                        //     while ($row = mysqli_fetch_assoc($result)) {
                                        //         echo "Token in DB: ";
                                        //         var_dump($row['reset_token']);
                                        //         echo "<br>";
                                        //     }
                                        // } else {
                                        //     echo "Token tidak ditemukan di database.<br>";
                                        // }


                                        // Cari token dan waktu kedaluwarsa di database
                                        $query = "SELECT reset_token, token_expiration, email FROM users WHERE reset_token='$tokenFromUrl'";
                                        $result = mysqli_query($conn, $query);

                                        // // Debugging untuk memeriksa hasil query
                                        // if (!$result) {
                                        //     die("Query Error: " . mysqli_error($conn));
                                        // }

                                        if (mysqli_num_rows($result) == 0) {
                                            die("Token tidak valid.");
                                        }

                                        $user = mysqli_fetch_assoc($result);
                                        $dbToken = $user['reset_token'];
                                        $expirationTime = $user['token_expiration'];
                                        $email = $user['email'];

                                        // // Debugging untuk memeriksa token dan waktu kedaluwarsa dari database
                                        // echo "Token in DB: $dbToken<br>";
                                        // echo "Expiration Time in DB: $expirationTime<br>";
                                        // echo "Current Time: " . time() . "<br>";

                                        // Periksa apakah token valid dan belum kedaluwarsa
                                        if ($dbToken !== $tokenFromUrl) {
                                            die("Token tidak cocok.");
                                        }

                                        if ($expirationTime < time()) {
                                            die("Token sudah kedaluwarsa. Silakan minta tautan reset baru.");
                                        }

                                        // Proses reset password
                                        if (isset($_POST['reset'])) {
                                            $password = trim($_POST['password']);

                                            if (empty($password)) {
                                                echo "<div class='alert alert-danger'>Password tidak boleh kosong.</div>";
                                            } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,12}$/", $password)) {
                                                echo "<div class='alert alert-danger'>Password harus memiliki 8-12 karakter, termasuk angka, huruf besar, huruf kecil, dan karakter khusus.</div>";
                                            } else {
                                                // Ambil password lama dari database
                                                $currentPasswordQuery = "SELECT password FROM users WHERE email='$email'";
                                                $currentPasswordResult = mysqli_query($conn, $currentPasswordQuery);

                                                if ($currentPasswordResult && mysqli_num_rows($currentPasswordResult) > 0) {
                                                    $currentPasswordData = mysqli_fetch_assoc($currentPasswordResult);
                                                    $currentPassword = $currentPasswordData['password'];

                                                    // Periksa apakah password baru sama dengan password lama
                                                    if (password_verify($password, $currentPassword)) {
                                                        echo "<div class='alert alert-danger'>Password baru tidak boleh sama dengan password lama.</div>";
                                                    } else {
                                                        // Hash password baru
                                                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                                                        // Update password di database
                                                        $updateQuery = "UPDATE users SET password='$hashedPassword', reset_token=NULL, token_expiration=NULL WHERE email='$email'";
                                                        if (mysqli_query($conn, $updateQuery)) {
                                                            echo "<div class='alert alert-success'>Password berhasil diperbarui. Silakan login.</div>";
                                                            header("Location: login.php");
                                                            exit();
                                                        } else {
                                                            echo "<div class='alert alert-danger'>Gagal memperbarui data: " . mysqli_error($conn) . "</div>";
                                                        }
                                                    }
                                                } else {
                                                    echo "<div class='alert alert-danger'>Gagal mengambil password lama.</div>";
                                                }
                                            }
                                        }
                                        ?>


                                        <img src="/ArenaFinder/img_asset/login.png" alt=""
                                            style="width: 200px; height: auto; margin-bottom: 20px" />
                                    </div>


                                    <form class="user" method="POST" action="#" autocomplete="off" name="login">
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputEmail" placeholder="Sandi Baru" name="password">
                                            <small>
                                                <input type="checkbox" id="togglePassword" style="margin-top: 10px;"> Tampilkan Password
                                            </small>
                                        </div>
                                        <input class="btn btn-primary btn-user btn-block" type="submit"
                                            value="Ganti Sandi" name="reset" id="btn-reset">
                                    </form>

                                    <hr />
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.php">Lupa Sandi?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Buat Akun Anda!</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="login.php">Sudah Memiliki Akun? Masuk Sekarang!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

</body>

</html>

<script>
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('exampleInputEmail');

    toggle.addEventListener('click', function() {
        if (password.type === "password") {
            password.type = 'text';
        } else {
            password.type = 'password';
        }
        this.classList.toggle('bi-eye');
    });
</script>

<script>
    // Fungsi untuk mengontrol visibilitas password
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('passwordInput');
        if (this.checked) {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    });
</script>