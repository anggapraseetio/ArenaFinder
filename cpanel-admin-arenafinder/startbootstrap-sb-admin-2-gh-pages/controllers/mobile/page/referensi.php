<?php
require "../../koneksi.php";

header("Content-Type: application/json");

function createQueryForVenue($operasional, $sewa, $where, $order, $limit)
{

    // query dengan jam operasional
    $qOperasionalData = '';
    $qOperasionalJoin = '';
    if ($operasional === true) {
        $qOperasionalData = "
            IFNULL(o.opened, '-') AS jam_buka, 
            IFNULL(o.closed, '-') AS jam_tutup,
        ";
        $qOperasionalJoin = "
            LEFT JOIN venue_operasional AS o 
            ON v.id_venue = o.id_venue";
    }

    // query dengan harga sewa
    $qHargaSewaData = '';
    $qHargaSewaJoin = '';
    if ($sewa === true) {
        $qHargaSewaData = "
            , IFNULL(
                MIN(p.price), 0
            ) AS harga_sewa
        ";
        $qHargaSewaJoin = "
            LEFT JOIN venue_price AS p
            ON v.id_venue = p.id_venue
        ";
    }

    // buat query
    return "SELECT 
        v.id_venue, v.venue_name, v.venue_photo, v.sport, v.status, v.coordinate,
        $qOperasionalData
        IFNULL(
            COUNT(r.id_review), 0
        ) AS total_review,
        IFNULL(
            ROUND(SUM(r.rating) / COUNT(r.id_review), 1), 0
        ) AS rating,
        v.price AS harga 
        $qHargaSewaData
        FROM venues AS v 
        LEFT JOIN venue_review AS r 
        ON v.id_venue = r.id_venue 
        $qHargaSewaJoin 
        $qOperasionalJoin
        $where 
        GROUP BY v.id_venue
        $order
        LIMIT $limit
    ";
}

/**
 * get venue by top ratting
 */
function fetchVenueRatting($conn, $limit)
{
    $sql = createQueryForVenue(
        false,
        false,
        "",
        "ORDER BY rating DESC, total_review DESC",
        $limit
    );

    // echo $sql;

    $result = $conn->query($sql);

    if ($result) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        if (!empty($data)) {
            return array("status" => "success", "message" => "Data berhasil didapatkan", "data" => $data);
        } else {
            return array("status" => "error", "message" => "Data tidak ditemukan");
        }
    } else {
        return array("status" => "error", "message" => "Perintah gagal dijalankan" . $conn->error);
    }
}

/**
 * get venue by location
 */
function fetchVenueLokasi($conn, $limit)
{
    $sql = createQueryForVenue(
        false,
        false,
        "",
        "ORDER BY rating DESC, total_review DESC",
        30
    );

    // echo $sql;

    $result = $conn->query($sql);

    if ($result) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        if (!empty($data)) {
            return array("status" => "success", "message" => "Data berhasil didapatkan", "data" => $data);
        } else {
            return array("status" => "error", "message" => "Data tidak ditemukan");
        }
    } else {
        return array("status" => "error", "message" => "Perintah gagal dijalankan" . $conn->error);
    }
}

/**
 * get venue by venue kosong
 */
function fetchVenueKosong($conn, $limit)
{
    $sql = createQueryForVenue(
        true,
        true,
        "WHERE v.status = 'Disewakan'",
        "ORDER BY rating DESC, total_review DESC",
        $limit
    );

    $result = $conn->query($sql);

    if ($result) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        if (!empty($data)) {
            return array("status" => "success", "message" => "Data berhasil didapatkan", "data" => $data);
        } else {
            return array("status" => "error", "message" => "Data tidak ditemukan");
        }
    } else {
        return array("status" => "error", "message" => "Perintah gagal dijalankan" . $conn->error);
    }
}

/**
 * get venue by venue 'gratis'
 */
function fetchVenueGratis($conn, $limit)
{

    $sql = createQueryForVenue(
        true,
        false,
        "WHERE v.status = 'Gratis'",
        "ORDER BY rating DESC, total_review DESC ",
        $limit
    );

    $result = $conn->query($sql);

    if ($result) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        if (!empty($data)) {
            return array("status" => "success", "message" => "Data berhasil didapatkan", "data" => $data);
        } else {
            return array("status" => "error", "message" => "Data tidak ditemukan");
        }
    } else {
        return array("status" => "error", "message" => "Perintah gagal dijalankan" . $conn->error);
    }
}

/**
 * get venue by top status 'berbayar'
 */
function fetchVenueBerbayar($conn, $limit)
{

    $sql = createQueryForVenue(
        true,
        false,
        "WHERE v.status = 'Berbayar'",
        "ORDER BY rating DESC, total_review DESC, v.price ASC",
        $limit
    );

    $result = $conn->query($sql);

    if ($result) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        if (!empty($data)) {
            return array("status" => "success", "message" => "Data berhasil didapatkan", "data" => $data);
        } else {
            return array("status" => "error", "message" => "Data tidak ditemukan");
        }
    } else {
        return array("status" => "error", "message" => "Perintah gagal dijalankan" . $conn->error);
    }
}

function fetchCoordinate($conn, $limit)
{
    $sql = "SELECT id_venue, venue_name, coordinate, location, sport
        FROM venues 
        LIMIT $limit
    ";

    $result = $conn->query($sql);

    if ($result) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        if (!empty($data)) {
            return array("status" => "success", "message" => "Data berhasil didapatkan", "data" => $data);
        } else {
            return array("status" => "error", "message" => "Data tidak ditemukan");
        }
    } else {
        return array("status" => "error", "message" => "Perintah gagal dijalankan" . $conn->error);
    }
}


/**
 * get venue by venue 'disewakan'
 */
function fetchVenueDisewakan($conn, $limit)
{

    $sql = createQueryForVenue(
        true,
        true,
        "WHERE v.status = 'Disewakan'",
        "ORDER BY rating DESC, total_review DESC, v.price ASC",
        $limit
    );

    $result = $conn->query($sql);

    if ($result) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        if (!empty($data)) {
            return array("status" => "success", "message" => "Data berhasil didapatkan", "data" => $data);
        } else {
            return array("status" => "error", "message" => "Data tidak ditemukan");
        }
    } else {
        return array("status" => "error", "message" => "Perintah gagal dijalankan" . $conn->error);
    }
}

function searchByName($conn, $limit)
{

    $sql = "SELECT ";;
}

function fetchTotalSlot($conn, $idVenue)
{
    $totalSlot = 0;
    $slotTerpakai = 0;

    $sql = "SELECT COUNT(vp.id_price) AS slot 
        FROM venue_price AS vp 
        WHERE vp.id_venue = $idVenue AND vp.date = DATE(NOW())
    ";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $totalSlot = $row['slot'];

        $sql = "SELECT COUNT(vd.id_price) AS slot 
            FROM venue_booking AS vb 
            JOIN venue_booking_detail AS vd 
            ON vb.id_booking = vd.id_booking 
            WHERE vb.id_venue = $idVenue AND vd.date = DATE(NOW()) AND vb.payment_status != 'Rejected'
        ";

        if ($result) {
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $slotTerpakai = $row['slot'];
            $jumlahSlot = $totalSlot - $slotTerpakai;

            if ($jumlahSlot < 0) {
                $jumlahSlot = 0;
            }

            $data = [
                'total_slot' => $totalSlot,
                'slot_terpakai' => $slotTerpakai,
                'slot' => $jumlahSlot
            ];

            return array("status" => "success", "message" => "data slot berhasil diambil", "data" => $data);
        } else {
            return array("status" => "error", "message" => "data slot gagal diambil", "data" => ["slot" => -1]);
        }
    } else {
        return array("status" => "error", "message" => "data slot gagal diambil", "data" => ["slot" => -2]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $limit = 5;

    // get data dashboard
    $venueRatting = fetchVenueRatting($conn, $limit);
    $venueLokasi = fetchVenueLokasi($conn, $limit);
    $venueKosong = fetchVenueKosong($conn, $limit);
    $venueGratis = fetchVenueGratis($conn, $limit);
    $venueBerbayar = fetchVenueBerbayar($conn, $limit);
    $venueDisewakan = fetchVenueDisewakan($conn, $limit);
    $coordinate = fetchCoordinate($conn, 30);

    // cek apakah data status venue error atau tidak
    if ($venueRatting['status'] == 'error') {
        $venueRatting = array("data" => []);
    }

    if ($venueLokasi['status'] == 'error') {
        $venueLokasi = array("data" => []);
    }

    if ($venueKosong['status'] == 'error') {
        $venueKosong = array("data" => []);
    } else {
        // get total slot and filter venues with available slots
        $data = $venueKosong["data"];
        $venueKosongFiltered = [];

        foreach ($data as $value) {
            $venueId = $value["id_venue"];
            $fetchTotalSlot = fetchTotalSlot($conn, $venueId);

            if ($fetchTotalSlot["data"]["slot"] > 0) {
                $value["total_slot"] = $fetchTotalSlot["data"]["slot"];
                $venueKosongFiltered[] = $value;
            }
        }
    }

    if ($venueGratis["status"] == "error") {
        $venueGratis = array("data" => []);
    }

    if ($venueBerbayar["status"] == "error") {
        $venueBerbayar = array("data" => []);
    }

    if ($venueDisewakan["status"] == "error") {
        $venueDisewakan = array("data" => []);
    }

    // cek apakah data venue coordinate berhasil didapatkan
    if ($coordinate['status'] == 'error') {
        $coordinate = array("data" => []);
    }

    // menyimpan data halaman referensi mobile
    $data = array(
        "top_ratting" => $venueRatting['data'],
        "venue_lokasi" => $venueLokasi["data"],
        "venue_kosong" => $venueKosongFiltered,
        "venue_gratis" => $venueGratis['data'],
        "venue_berbayar" => $venueBerbayar["data"],
        "venue_disewakan" => $venueDisewakan["data"],
        "coordinate" => $coordinate["data"],
    );

    // response sukses
    $response = array("status" => "success", "message" => "Data beranda sukses didapatkan", "data" => $data);
} else {
    $response = array("status" => "error", "message" => "not get method");
}

echo json_encode($response);
