<?php
/**
 * Digunakan untuk mengupdate status verifikasi dari akun
 */

 require "../../koneksi.php";

header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // post request
        $email = $_POST['email'];

        // get data user
        $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $result = $conn->query($sql);

        // jika email exist
        if ($result->num_rows == 1) {
            // eksekusi query
            $sql = "UPDATE users SET is_verified = 1 WHERE email = '$email'";
            if($conn->query($sql)){
                // jika data berhasil terupdate
                $response = array("status"=>"success", "message"=>"Akun berhasil terverifikasi");
            }else{
                $response = array("status"=>"error", "message"=>"Akun gagal terverifikasi");
            }
        } else {
            $response = array('status' => 'error', 'message' => 'Email tidak ditemukan');
        }

        // close koneksi
        $conn->close();
    }else{
        $response = array("status"=>"error", "message"=>"not post method");
    }

    // show response
    echo json_encode($response);
?>