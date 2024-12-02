<?php

/**
 * Digunakan untuk mengupdate photo profile user.
 */

require "../../koneksi.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // post request
    $email = $_POST['email'];
    $photo = $_POST['photo'];

    // cek email exist atau tidak
    $sql = "SELECT email FROM users WHERE email = '$email' LIMIT 1";
    if ($conn->query($sql)->num_rows == 1) {
        // saving photo
        $photo = str_replace('data:image/png;base64,', '', $photo);
        $photo = str_replace(' ', '+', $photo);
        $data = base64_decode($photo);

        // Tentukan direktori upload menggunakan DOCUMENT_ROOT
        $upload_folder = $_SERVER['DOCUMENT_ROOT'] . '/ArenaFinder/public/img/venue/';
        if (!is_dir($upload_folder)) {
            mkdir($upload_folder, 0777, true); // Buat folder jika belum ada
        }

        $filename = uniqid() . '.png';
        $file = $upload_folder . $filename;

        if (file_put_contents($file, $data) === false) {
            $response = array("status" => "error", "message" => "Gagal menyimpan file ke server.");
            echo json_encode($response);
            exit;
        }

        // update data user di database
        $sql = "UPDATE users SET user_photo = '$filename' WHERE email = '$email'";
        $result = $conn->query($sql);

        // jika foto profile berhasil diupdate
        if ($result === true) {
            $sql = "SELECT user_photo FROM users WHERE email = '$email' LIMIT 1";
            $result = $conn->query($sql);
            $photo = $result->fetch_assoc();

            if ($result->num_rows == 1) {
                $response = array("status" => "success", "message" => "Photo profile berhasil diupdate", "data" => $photo);
            } else {
                $response = array("status" => "success", "message" => "Photo profile berhasil diupdate");
            }
        } else {
            $response = array("status" => "error", "message" => "Photo profile gagal diupdate");
        }
    } else {
        $response = array("status"=> "error", "message"=> "Email tidak ditemukan");
    }

    // close koneksi
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "Method is not POST");
}

// show response
echo json_encode($response);
