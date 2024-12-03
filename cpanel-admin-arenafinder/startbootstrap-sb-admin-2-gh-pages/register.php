<?php session_start(); ?>
<?php
include('database.php');

if (isset($_POST["register"])) {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $phone = $_POST["phone"];
  $password = $_POST["password"];
  $level = $_POST["level"];

  // Pengecekan format nomor telepon
  if (strlen($phone) < 12 || strlen($phone) > 13 || !ctype_digit($phone)) {
    $message = "Nomor telepon harus terdiri dari 12 atau 13 digit dan hanya mengandung angka.";
  } else {
    // Pengecekkan email yang sudah terdaftar dan tersimpan di tabel users
    $check_query = mysqli_query($conn, "SELECT * FROM users where email ='$email' AND username = '$username'");
    $rowCount = mysqli_num_rows($check_query);

    // Cek apakah salah satu atau semua input form tidak diisi
    if (empty($username) && empty($email) && empty($password) && empty($phone)) {
      $message = "Harap isi semua kolom pada formulir pendaftaran.";
    } elseif (empty($username) && empty($email) && empty($phone)) {
      $message = "Mohon isi nama pengguna, email, dan nomor telepon.";
    } elseif (empty($username) && empty($password)) {
      $message = "Mohon isi nama pengguna dan sandi.";
    } elseif (empty($email) && empty($password)) {
      $message = "Mohon isi email dan sandi.";
    } elseif (empty($phone) && empty($email)) {
      $message = "Mohon isi email dan nomor telepon.";
    } elseif (empty($username)) {
      $message = "Mohon isi nama pengguna.";
    } elseif (empty($email)) {
      $message = "Mohon isi email.";
    } elseif (empty($phone)) {
      $message = "Mohon isi nomor telepon.";
    } elseif (empty($password)) {
      $message = "Mohon isi sandi.";
    } else {
      // Cek validasi email
      $rowCount = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'"));

      if ($rowCount > 0) {
?>
        <script>
          alert("Pengguna dengan email ini sudah terdaftar!");
          window.location.replace('register.php');
        </script>
      <?php
      } elseif (usernameExists($conn, $username)) {
      ?>
        <script>
          alert("Nama pengguna sudah terdaftar. Mohon pilih nama pengguna lain.");
          window.location.replace('register.php');
        </script>
      <?php
      } else {
        // Cek validasi sandi akun
        if (!isValidPassword($password)) {
        // Notifikasi peringatan jika sandi salah
        ?>
          <script>
            alert("Password harus memiliki 8 sampai 12 karakter, mengandung angka, huruf besar, huruf kecil, dan karakter khusus.");
            window.location.replace('register.php');
          </script>
        <?php
        } else {
          $password_hash = password_hash($password, PASSWORD_BCRYPT);

          $result = mysqli_query($conn, "INSERT INTO users (username, email, no_hp, password, is_verified, level) VALUES ('$username', '$email', '$phone', '$password_hash', 0, '$level')");

          // Eksekusi kode OTP jika data akun telah ditambahkan kedalam database
          if ($result) {
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_expiration'] = time() + (5 * 60); // Set expiration time to 5 minutes
            $_SESSION['mail'] = $email;

            require "Mail/phpmailer/PHPMailerAutoload.php";
            $mail = new PHPMailer; 

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';

            $mail->Username = 'tengkufarkhan3@gmail.com';
            $mail->Password = 'bynv cdfj izrp wiho';

            $mail->setFrom('arenafinder.app@gmail.com', 'OTP Verification');
            $mail->addAddress($_POST["email"]);

            $mail->isHTML(true);
            $mail->Subject = "Kode verifikasi akun anda";
            $mail->Body = "<p>Kepada admin, </p> <h3>Kode OTP anda adalah $otp <br></h3>
                <br><br>
                <p>Berikan pesan anda lewat email ini,</p>
                <b>arenafinder.app@gmail.com</b>";

            if (!$mail->send()) {
          ?>
              <script>
                alert("<?php echo "Daftar akun gagal, email tidak valid" ?>");
              </script>
            <?php
            } else {
            ?>
              <script>
                alert("<?php echo "Daftar akun sukses, kode OTP dikirim ke " . $email ?>");
                window.location.replace('verification.php');
              </script>
    <?php
            }
          }
        }
      }
    }
  }
}

// Jika ada pesan kesalahan, tampilkan alert dan redirect
if (isset($message)) {
  ?>
  <script>
    alert("<?php echo $message; ?>");
    window.location.replace('register.php');
  </script>
<?php
  exit();
}

function usernameExists($conn, $username)
{
  $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
  return mysqli_num_rows($result) > 0;
}

// Function untuk mengecek kompleksitas sandi
function isValidPassword($password)
{
  // Sandi sekurang-kurangnya harus mengandung 8 karakter, huruf besar, huruf kecil, dan karakter khusus
  $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,12}$/";
  return preg_match($pattern, $password);
}
?>

<!-- HTML remains the same -->

<script>
  // JavaScript validation for phone number (client-side)
  document.forms['register'].addEventListener('submit', function (e) {
    var phoneInput = document.getElementById('phone-input').value;
    
    if (phoneInput.length < 12 || phoneInput.length > 13 || !/^\d+$/.test(phoneInput)) {
      alert("Nomor telepon harus terdiri dari 12 atau 13 digit dan hanya mengandung angka.");
      e.preventDefault(); // Prevent form submission
    }
  });
</script>



 
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin 2 - Register</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link rel="icon" href="/ArenaFinder/img_asset/login.png">
  <style>
    body {
      font-family: "Kanit", sans-serif;
    }

    #btn-login {
      background-color: #e7f5ff;
      color: #02406d;
    }

    #btn-login:hover {
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

    #card-email {
      background-color: white;
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
                    <h1 class="h2 text-gray-900 mb-2 ">Daftar Akun</h1>
                    <img src="/ArenaFinder/img_asset/login.png" alt="" style="width: 200px; height: auto; margin-bottom: 20px" />
                  </div>
                  <form class="user" method="POST" action="#" autocomplete="off" name="register">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" id="exampleFirstName"
                        placeholder="Masukkan Nama Pengguna" name="username" autofocus require>
                    </div>
                    <div class="form-group">
                      <input type="email" class="form-control form-control-user" id="email-input" name="email"
                        aria-describedby="emailHelp" placeholder="Masukkan Alamat Email" />
                    </div>

                    <div class="form-group">
                      <input type="phone" class="form-control form-control-user" id="phone-input" name="phone"
                        placeholder="Masukkan No HP" />
                    </div>


                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" id="passwordInput"
                        placeholder="Masukkan Sandi" name="password" />
                      <small>
                        <input type="checkbox" id="togglePassword" style="margin-top: 10px;"> Tampilkan Sandi
                      </small>
                      <input type="hidden" name="level" value="ADMIN" id="level" />
                    </div>

                    <div class="form-group">
                      <button class="btn btn-user btn-block" id="btn-login" name="register">Daftar</button>
                    </div>

                  </form>
                  <hr />
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

  <!-- Show and Hidden Password-->
  <script>
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordInput = document.getElementById('passwordInput');
      if (this.checked) {
        passwordInput.type = 'text';
      } else {
        passwordInput.type = 'password';
      }
    });
  </script>

</body>

</html>