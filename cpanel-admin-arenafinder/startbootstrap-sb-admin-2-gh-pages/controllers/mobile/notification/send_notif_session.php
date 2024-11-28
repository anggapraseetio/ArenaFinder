<?php

require "../../koneksi.php";
require 'Notification.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_GET['email'];

    $notif = new Notification();

    $sql = "SELECT device_token FROM session";
    $result = mysqli_query($conn, $sql);

    $deviceTokens = array();
    
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            
            // mendapatkan data device token
            while ($row = mysqli_fetch_assoc($result)) {
                $deviceTokens[] = $row['device_token'];
            }
    
            // mengirim notifikasi
            foreach ($deviceTokens as $deviceToken) {

                $title = "Halo, Selamat Pagi";
                $body = "Test Notif";
                $data = [
                    "key1" => "data1",
                    "key2" => "data2"
                ];

                $status = $notif->sendNotif($deviceToken, $title, $body, $data);
                $response = array("test" => $status);
            }
        } else {
            echo "Tidak ada data device_token.";
        }
    } else {
        echo "Perintah gagal dijalankan: " . mysqli_error($conn);
    }

    // close koneksi
    $conn->close();
} else {
    $response = array("status" => "error", "message" => "not post method");
}

// show response
echo json_encode($response);