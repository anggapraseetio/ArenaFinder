<?php
/**
 * Copy me to create a new request.
 */

// require "../koneksi.php";
require "../../koneksi.php";

header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        // get request
        $request = $_GET['request'];

        // eksekusi query
        $sql = "";

        $response = array("status"=>"success", "message"=>"response success", "data"=>"Lorem ipsum dolor sit amet.");

        // close koneksi
        $conn->close();

    }else{
        $response = array("status"=>"error", "message"=>"not get method");
    }

    echo json_encode($response);
?>