<?php

require "../../../../koneksi.php";
require '../../../notification/Notification.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the request
    $idVenue = $_POST['id_booking'];
    $email = $_POST['email'];

    $sql = "UPDATE venue_booking SET payment_status = 'Accepted' WHERE id_booking = $idVenue";

    $result = mysqli_query($conn, $sql);

    if ($result === true) {
        $response = array("status" => "success","message"=> "data berhasil diupdate");

        $sql = "SELECT device_token FROM session WHERE email = '$email' ORDER BY id_session DESC LIMIT 1";
        $result = mysqli_query($conn, $sql)->fetch_assoc();
    
        $data = [
            'key_1' => 'data 1',
            'key_2' => 'data 2'
        ];

        $notif = new Notification();
        $notif->sendNotif($result['device_token'], "Pesanan Diterima", "Pesanan Anda telah diterima, Silahkan lakukan pembayaran secara offline kepada admin pengelola lapangan", $data);

        $response = array("status" => "error","sucess"=> "data berhasil diupdate");

    }else{
        $response = array("status" => "error","message"=> "data gagal diupdate");
    }

    $conn->close();
} else {
    // Invalid request method
    $response = ["status" => "error", "message" => "Invalid request method"];
    echo json_encode($response);
}
?>