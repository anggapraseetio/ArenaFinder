<?php

/**
 * Digunakan untuk mengupdate data akun seperti username dan full nama.
 */

require "../../koneksi.php";
require "Validator.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // post request
    $email = $_POST['email'];
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    if ($conn->query($sql)->num_rows == 0) {
        $response = array("status" => "error", "message" => "Email tersebut tidak terdaftar");
    } else {
        // validasi data
        $validator = new Validator();
        $validUsername = $validator->isValidUsername($username);
        $validName = $validator->isValidName($full_name);

        // cek validasi data
        if ($validUsername["status"] === "error") {
            $response = $validUsername;
        } else if ($validName["status"] === "error") {
            $response = $validName;
        } else {

            // cek username exist atau tidak
            $sql = "SELECT username FROM users WHERE username = '$username' LIMIT 1";
            $result = $conn->query($sql);
            $update = false;

            // jika sudah ada
            if ($result->num_rows == 1) {
                $userRow = $result->fetch_assoc(); // Mengambil data username dari hasil query
                if ($userRow["username"] != $username) {
                    $response = array("status" => "error", "message" => "Username tersebut sudah terdaftar");
                } else {
                    // update nama saja
                    $sql = "UPDATE users SET full_name = '$full_name' WHERE email = '$email'";
                    $update = $conn->query($sql);
                }
            } else {
                // update nama dan username
                $sql = "UPDATE users SET username = '$username', full_name = '$full_name' WHERE email = '$email'";
                $update = $conn->query($sql);
            }

            // jika data berhasil diupdate
            if ($update === true) {
                // mendapatkan data baru
                $sql = "SELECT * FROM users WHERE email = '$email'";
                $result = $conn->query($sql);

                if ($result->num_rows == 1) {
                    $response = array("status" => "success", "message" => "Edit akun berhasil diupdate", "data" => $result->fetch_assoc());
                } else {
                    $response = array("status" => "error", "message" => "Edit akun berhasil tetapi data gagal didapatkan");
                }
            } else {
                $response = array("status" => "error", "message" => "Edit akun gagal diupdate");
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
