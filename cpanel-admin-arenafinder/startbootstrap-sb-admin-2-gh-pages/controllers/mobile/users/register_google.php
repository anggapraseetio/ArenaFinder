<?php

/**
 * Digunakan untuk register dengan google.
 */

require "../../koneksi.php";
require "Validator.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // post request
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $password = $_POST['password'];
    $deviceToken = $_POST['device_token'];
    // $user_photo = $_POST[''];

    $epassword = password_hash($password, PASSWORD_BCRYPT);

    // cek email sudah terdaftar atau belum
    $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    if ($conn->query($sql)->num_rows == 1) {
        $response = array("status" => "error", "message" => "Email tersebut sudah terdaftar");
    } else {
        // validasi data
        $validator = new Validator();
        $validUsername = $validator->isValidUsername($username);
        $validEmail = $validator->isValidEmail($email);
        $validName = $validator->isValidName($full_name);
        $validPassword = $validator->isValidPassword($password);

        // cek validasi data
        if ($validUsername["status"] === "error") {
            $response = $validUsername;
        } else if ($validEmail["status"] === "error") {
            $response = $validEmail;
        } else if ($validPassword["status"] === "error") {
            $response = $validPassword;
        } else {

            if ($validName["status"] === "error") {
                $full_name = "ArenaFinder User";
            }

            // cek username exist atau tidak
            $sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
            if ($conn->query($sql)->num_rows == 1) {
                $response = array("status" => "error", "message" => "Username tersebut sudah terdatar");
            }
            // saving data to database
            else {
                // get data user
                $sql = "INSERT INTO users VALUES (null, '$username', '$email', '',  '$full_name', '',  '$epassword', 'END USER', 1, 'default.png', NOW())";
                $result = $conn->query($sql);

                // jika data berhasil ditambahkan
                if ($result === true) {

                    // mendapatkan data
                    $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
                    $result = $conn->query($sql);

                    // jika data berhasil didapatkan
                    if ($result->num_rows == 1) {
                        $user = $result->fetch_assoc();
                        // save login data
                        $sql = "INSERT INTO session (`email`, `device`, `device_token`) 
                                    VALUES ('" . $user['email'] . "', 'Mobile', '$deviceToken'
                                );";
                        // cek 
                        if ($conn->query($sql)) {
                            $response = array("status" => "success", "message" => "Register Success", "data" => $user);
                        } else {
                            $response = array('status' => 'error', 'message' => 'Data login gagal disimpan');
                        }
                        
                    } else {
                        $response = array("status" => "error", "message" => "Register berhasil tetapi data akun gagal didapatkan");
                    }
                } else {
                    $response = array("status" => "error", "message" => "Register Gagal");
                }
            }
        }
    }

    // close koneksi
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "not post method");
}

// show response
echo json_encode($response);
