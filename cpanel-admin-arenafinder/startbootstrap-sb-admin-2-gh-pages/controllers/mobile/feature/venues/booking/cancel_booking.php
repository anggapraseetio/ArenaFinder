<?php

require "../../../../koneksi.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the request
    $idBooking = $_POST['id_booking'];

    $sql = "DELETE FROM venue_booking WHERE id_booking = $idBooking";
    
    if($conn->query($sql) == true){
        $response = array("status"=>"success", "message"=>"booking dibatalkan");
    }else{
        $response = array("status"=>"error", "message"=>"booking gagal dibatalkan");
    }

} else {
    // Invalid request method
    $response = ["status" => "error", "message" => "Invalid request method"];
    echo json_encode($response);
}
?>
