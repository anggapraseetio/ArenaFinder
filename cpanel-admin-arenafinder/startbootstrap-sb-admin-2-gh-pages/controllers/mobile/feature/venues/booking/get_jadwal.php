<?php

require "../../../../koneksi.php";

header("Content-Type: application/json");

function fetchPrice($conn, $idVenue, $idLapangan, $date)
{
    $sql = "SELECT id_price, SUBSTRING(start_hour, 1, 5) AS start_hour, SUBSTRING(end_hour, 1, 5) AS end_hour, price
        FROM venue_price 
        WHERE id_venue = $idVenue 
        AND id_lapangan = $idLapangan AND date = '$date' 
    ;";

    $result = $conn->query($sql);

    if ($result) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        if (!empty($data)) {
            return array("status" => "success", "message" => "Data price didapatkan", "data" => $data);
        } else {
            return array("status" => "error", "message" => "Data tidak ditemukan");
        }
    } else {
        return array("status" => "error", "message" => "Perintah gagal dijalankan" . $conn->error);
    }
}

function fetchBooked($conn, $idVenue, $date)
{
    $sql = "SELECT vb.id_price FROM venue_booking AS v 
        JOIN venue_booking_detail AS vb
        ON v.id_booking = vb.id_booking 
        WHERE v.id_venue = $idVenue AND vb.date = '$date' AND v.payment_status != 'Rejected' 
    ";

    $result = $conn->query($sql);

    if ($result) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        if (!empty($data)) {
            return array("status" => "success", "message" => "Data booking didapatkan", "data" => $data);
        } else {
            return array("status" => "error", "message" => "Data tidak ditemukan");
        }
    } else {
        return array("status" => "error", "message" => "Perintah gagal dijalankan" . $conn->error);
    }
}

function fetchLapangan($conn, $idLapangan){
    $sql = "SELECT * FROM venue_lapangan WHERE id_lapangan = $idLapangan";

    $result = $conn->query($sql);

    if ($result) {
        $data = $result->fetch_assoc();
        if (!empty($data)) {
            return array("status" => "success", "message" => "Data lapangan didapatkan", "data" => $data);
        } else {
            return array("status" => "error", "message" => "Data tidak ditemukan");
        }
    } else {
        return array("status" => "error", "message" => "Perintah gagal dijalankan" . $conn->error);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $idVenue = $_GET['id_venue'];
    $idLapangan = $_GET['id_lapangan'];
    $date = $_GET['date'];

    $venueLapangan = fetchLapangan($conn, $idLapangan);
    $venuePrice = fetchPrice($conn, $idVenue, $idLapangan, $date);
    $venueBooked = fetchBooked($conn, $idVenue, $date);

    if ($venueLapangan["status"] == "error") {
        $venueLapangan = array("data" => []);
    }

    if ($venuePrice["status"] == "error") {
        $venuePrice = array("data" => []);
    }

    if ($venueBooked["status"] == "error") {
        $venueBooked = array("data" => []);
    }

    $data = [];

    // Iterasi melalui data harga
    foreach ($venuePrice["data"] as $priceItem) {
        $idPrice = $priceItem["id_price"];
        $harga = $priceItem["price"];
        $starHour = $priceItem["start_hour"];
        $endHour = $priceItem["end_hour"];
        $status = false; // Inisialisasi status sebagai false

        // Iterasi melalui data booking untuk mencocokkan id_price
        foreach ($venueBooked["data"] as $bookedItem) {
            if ($bookedItem["id_price"] === $idPrice) {
                $status = true; // Jika id_price ditemukan, ubah status menjadi true
                break; // Keluar dari loop karena sudah ditemukan
            }
        }

        if ($status == true) {
            $harga = 0;
        }

        // Simpan id_price dan status ke dalam variabel 'data'
        $data[] = [
            "id_price" => $idPrice, 
            "session"=> "$starHour - $endHour",
            "price" => $harga,
            "is_booked" => $status,
            'selected' => 'false'
        ];
    }

    $lapangan = array(
        "id_lapangan" => $idLapangan,
        "photo" => $venueLapangan["data"]["photo"],
        "nama_lapangan" => $venueLapangan["data"]["nama_lapangan"],
        "total_slot" => "12 slot kosong",
        "jadwal" => $data
    );

    $response = array("status" => "success", "message" => "Data booking didapatkan",  "data" => $lapangan);
    // $response = array("status" => "success", "price" => $venuePrice['data'], "booked" => $venueBooked['data']);
} else {
    $response = array("status" => "error", "message" => "not put method");
}

echo json_encode($response);
