<?php
require "../../koneksi.php";

header("Content-Type: application/json");

function createQueryForAktivitas($where, $order, $limit){
    return "SELECT 
            a.id_aktivitas, a.nama_aktivitas, v.venue_name, a.date, a.photo, a.max_member,
            a.jam_main, a.price,
        IFNULL(
            COUNT(am.id_aktivitas), 0
        ) AS jumlah_member 
        FROM venue_aktivitas AS a 
        LEFT JOIN venues AS v 
        ON a.id_venue = v.id_venue
        LEFT JOIN venue_aktivitas_member AS am 
        ON a.id_aktivitas = am.id_aktivitas 
        GROUP BY a.id_aktivitas
        $where
        $order
        LIMIT $limit
    ";
}

function fetchAktivitasBaru($conn, $limit)
{
    $sql = createQueryForAktivitas(
        "HAVING a.date >= NOW() AND jumlah_member < a.max_member", 
        "ORDER BY a.date ASC", 
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

function fetchAktivitasKosong($conn, $limit)
{
    $sql = createQueryForAktivitas(
        "HAVING a.date >= NOW() AND jumlah_member < a.max_member", 
        "ORDER BY a.date ASC", 
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

function fetchAktivitasSemua($conn, $limit)
{
    $sql = createQueryForAktivitas(
        "HAVING a.date >= NOW() AND jumlah_member < a.max_member", 
        "ORDER BY a.date ASC", 
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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $limit = 5;

    $aktivitasBaru = fetchAktivitasBaru($conn, $limit);
    $aktivitasKosong = fetchAktivitasKosong($conn, $limit);
    $aktivitasSemua = fetchAktivitasSemua($conn, 20);

    if ($aktivitasBaru['status'] == 'error'){
        $aktivitasBaru = array("data" => []);
    }

    if ($aktivitasKosong['status'] == 'error'){
        $aktivitasKosong = array("data" => []);
    }

    if ($aktivitasSemua['status'] == 'error'){
        $aktivitasSemua = array("data" => []);
    }

    $data = array(
        "aktivitas_baru" => $aktivitasBaru['data'],
        "aktivitas_kosong" => $aktivitasKosong["data"],
        "aktivitas_semua" => $aktivitasSemua["data"],
    );

    $response = array("status" => "success", "message" => "Data beranda sukses didapatkan", "data"=>$data);
} else {
    $response = array("status" => "error", "message" => "not get method");
}

echo json_encode($response);

?>