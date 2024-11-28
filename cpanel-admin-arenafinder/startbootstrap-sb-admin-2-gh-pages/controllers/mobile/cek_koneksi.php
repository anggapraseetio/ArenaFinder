<?php
header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // koneksi ke database
        // $conn = new mysqli("103.247.11.134", "tifz1761_root", "tifnganjuk321", "tifz1761_arenafinder");
        $conn = new mysqli("localhost", "root", "", "arenafinder");

        $data = [
            "server_status" => true,
            "have_update" => true,
            "force_update" => false,
            "min_version_code" => 2,
            "new_version_name" => "1.1",
            "update_link" => "https://drive.google.com/drive/folders/1NzhzlVMZNfOdnnYuJHRgl8ivFdWt_Y1Q?usp=sharing",
            "desc_update" => " - remove payment gateway"
        ];

        // jika koneksi gagal
        if ($conn->connect_error) {
            $response = array('status' => 'error', 'message' => 'Koneksi gagal');
        }else{
            $response = array('status' => 'success', 'message' => 'Koneksi success', 'data'=>$data);
        }

        // close koneksi
        $conn->close();
        
    }else{
        $response = array('status'=>'error', 'message'=>'unexpected error');
    }
    echo json_encode($response);
?>