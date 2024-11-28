<?php

/**
 * Digunakan untuk mengupdate password dari users.
 */

require "../../koneksi.php";
require "Validator.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // post request
    $email = $_POST['email'];
    $password = $_POST['password_now'];
    $newPass = $_POST['password_new'];
    $epassword = password_hash($newPass, PASSWORD_BCRYPT);

    // get data user
    $sql = "SELECT * FROM users WHERE email = '$email' OR username = '$email' LIMIT 1";
    $result = $conn->query($sql);

    // jika username atau email exist
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc(); // get user data
        // jika password match
        if (password_verify($password, $user['password'])) {
            // validasi data
            $validator = new Validator();
            $validPass = $validator->isValidPassword($newPass);

            if ($validPass["status"] === "error") {
                $response = $validPass;
            } else {
                // get data user
                $sql = "UPDATE users SET password = '$epassword' WHERE email = '$email'";
                $result = $conn->query($sql);

                // jika password berhasil terupdate
                if ($result === true) {
                    $response = array("status" => "success", "message" => "Password berhasil diupdate");
                } else {
                    $response = array("status" => "error", "message" => "Password gagal diupdate");
                }
            }
        }else{
            $response = array("status" => "error", "message" => "Password saat ini tidak cocok");
        }
    } else {
        $response = array("status" => "error", "message" => "gagal memvalidasi data");
    }


    // close koneksi
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "method is not post");
}

// show response
echo json_encode($response);
