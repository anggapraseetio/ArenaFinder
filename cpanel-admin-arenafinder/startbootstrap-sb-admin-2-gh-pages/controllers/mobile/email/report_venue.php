<?php
/**
 * Digunakan untuk mengirimkan kode otp ke email user
 */

require '../../../vendor/autoload.php'; 
require "../../koneksi.php";
require 'EmailSender.php';

    // random kode otp
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $email = $_POST['email'];
        $reason = $_POST['reason'];
        $venueId = $_POST['venue_id'];
        $venueName = $_POST['venue_name'];

        $mail = new EmailSender();
        $mail->sendReportedVenue($email, $reason, $venueId, $venueName);

        $response = array("status" => "success", "message" => "laporan diterima");
    } else {
        $response = array("status" => "error", "message" => "method bukan post");
    }
    
    echo json_encode($response);
?>
