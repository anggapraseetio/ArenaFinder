<?php
require "../../koneksi.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $idVenue = $_GET['id_venue'];

    $totalSlot = 0;
    $slotTerpakai = 0;

    $sql = "SELECT COUNT(vp.id_price) AS slot 
        FROM venue_price AS vp 
        WHERE vp.id_venue = $idVenue AND vp.date = DATE(NOW());
    ";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $totalSlot = $row['slot'];

        $sql = "SELECT COUNT(vd.id_price) AS slot 
            FROM venue_booking AS vb 
            JOIN venue_booking_detail AS vd 
            ON vb.id_booking = vd.id_booking 
            WHERE vb.id_venue = $idVenue AND vd.date = DATE(NOW())
        ";

        if ($result) {
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $slotTerpakai = $row['slot'];
            $jumlahSlot = $totalSlot - $slotTerpakai;

            if($jumlahSlot < 0){
                $jumlahSlot = 0;
            }

            $data = [
                'total_slot' => $totalSlot,
                'slot_terpakai' => $slotTerpakai,
                'slot' => $jumlahSlot
            ];

            $response = array("status" => "success", "message" => "data slot berhasil diambil", "data" => $data);
        }else{
            $response = array("status" => "error", "message" => "data slot gagal diambil", "data" => ["slot" => 0]);
        }
    } else {
        $response = array("status" => "error", "message" => "data slot gagal diambil", "data" => ["slot" => 0]);
    }
}
echo json_encode($response);
