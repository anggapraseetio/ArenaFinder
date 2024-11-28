<?php

/**
 * Digunakan untuk register manual.
 */

require "../../koneksi.php";
require "Validator.php";

header("Content-Type: application/json");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {



    // Ambil data dari request POST

    $username = $_POST['username'];

    $email = $_POST['email'];

    $full_name = $_POST['full_name'];

    $password = $_POST['password'];



    // Hash password menggunakan PASSWORD_BCRYPT

    $epassword = password_hash($password, PASSWORD_BCRYPT);



    // Validasi data

    $validator = new Validator();

    $validUsername = $validator->isValidUsername($username);

    $validEmail = $validator->isValidEmail($email);

    $validName = $validator->isValidName($full_name);

    $validPassword = $validator->isValidPassword($password);



    // Cek hasil validasi

    if ($validUsername["status"] === "error") {

        $response = $validUsername;
    } else if ($validEmail["status"] === "error") {

        $response = $validEmail;
    } else if ($validName["status"] === "error") {

        $response = $validName;
    } else if ($validPassword["status"] === "error") {

        $response = $validPassword;
    } else {

        // Cek apakah username atau email sudah terdaftar

        $sql = "SELECT COUNT(*) as count FROM users WHERE username = ? OR email = ?";

        $stmt = $conn->prepare($sql);

        if ($stmt === false) {

            $response = array("status" => "error", "message" => "SQL Prepare failed: " . $conn->error);

            echo json_encode($response);

            exit;
        }



        $stmt->bind_param("ss", $username, $email);

        $stmt->execute();

        $stmt->bind_result($count);

        $stmt->fetch();

        $stmt->close();



        if ($count > 0) {

            $response = array("status" => "error", "message" => "Username atau Email sudah terdaftar");
        } else {

            // Eksekusi query untuk insert user baru

            $sqlInsert = "INSERT INTO users (username, email, full_name, password, level, user_photo, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";

            $stmtInsert = $conn->prepare($sqlInsert);

            if ($stmtInsert === false) {

                // Detail error jika SQL Prepare gagal

                $response = array("status" => "error", "message" => "SQL Prepare failed saat insert: " . $conn->error);

                echo json_encode($response);

                exit;
            }



            $role = 'END USER';  // Role default

            $profile_picture = 'default.png'; // Profile picture default



            $stmtInsert->bind_param("ssssss", $username, $email, $full_name, $epassword, $role, $profile_picture);

            if ($stmtInsert->execute()) {

                $response = array("status" => "success", "message" => "Register Success");
            } else {

                // Detail error jika eksekusi insert gagal

                $response = array("status" => "error", "message" => "Register Gagal saat insert: " . $stmtInsert->error);
            }

            $stmtInsert->close();
        }
    }



    // Tutup koneksi database

    $conn->close();
} else {

    $response = array("status" => "error", "message" => "Request bukan metode POST");
}



// Kirimkan response dalam format JSON

echo json_encode($response);
