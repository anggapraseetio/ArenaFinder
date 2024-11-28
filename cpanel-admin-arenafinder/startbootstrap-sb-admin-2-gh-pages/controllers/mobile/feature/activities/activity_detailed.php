<?php
require "../../../koneksi.php";

header("Content-Type: application/json");


function fetchActivityData($conn, $idAktivitas)
{
    $sql = "SELECT v.nama_aktivitas, v.desc_aktivitas, ve.venue_name, v.sport, v.date, v.jam_main,
        v.membership, v.price, ve.location, ve.coordinate, v.photo, v.id_venue
        FROM venue_aktivitas AS v 
        JOIN venues AS ve
        ON v.id_venue = ve.id_venue 
        JOIN users AS u 
        ON ve.email = u.email 
        WHERE v.id_aktivitas = $idAktivitas 
    ";

    $result = $conn->query($sql);

    if ($result) {
        $data = $result->fetch_assoc();
        if (!empty($data)) {
            return array("status" => "success", "message" => "Data berhasil didapatkan", "data" => $data);
        } else {
            return array("status" => "error", "message" => "Data tidak ditemukan");
        }
    } else {
        return array("status" => "error", "message" => "Perintah gagal dijalankan" . $conn->error);
    }
}

function fetchAktivitasContact($conn, $idAktivitas)
{
    $sql = "SELECT u.id_users, u.full_name, u.user_photo, u.no_hp FROM venues AS v 
    LEFT JOIN users AS u 
    ON u.email = v.email
    WHERE id_venue = $idAktivitas
    GROUP BY u.no_hp";

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

function fetchMemberAktivitas($conn, $idAktivitas)
{
    $sql = "SELECT u.user_photo, u.username, u.full_name FROM venue_aktivitas_member AS v 
        JOIN users AS u 
        ON v.email = u.email 
        WHERE v.id_aktivitas = $idAktivitas
        ;
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

function fetchStatusJoined($conn, $idAktivitas, $email)
{
    $sql = "SELECT CASE WHEN COUNT(*) > 0 THEN 'true' ELSE 'false' END AS is_joined
        FROM venue_aktivitas_member
        WHERE id_aktivitas = $idAktivitas AND email = '$email';
    ";

    $result = $conn->query($sql);

    if ($result) {
        $data = $result->fetch_assoc();
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

    $idAktivitas = $_GET['id_aktivitas'];
    $email = $_GET['email'];

    $sql = "SELECT id_aktivitas FROM venue_aktivitas WHERE id_aktivitas= $idAktivitas";

    if ($conn->query($sql)->num_rows != 1) {
        $response = array("status" => "error", "message" => "Id $idAktivitas aktivitas tidak ditemukan!");
    } else {

        $aktivitasData = fetchActivityData($conn, $idAktivitas);
        $aktivitasContact = array("data" => []);
        $aktivitasMember = fetchMemberAktivitas($conn, $idAktivitas);
        $aktivitasStatus = fetchStatusJoined($conn, $idAktivitas, $email);

        if ($aktivitasData["status"] == "error") {
            $aktivitasData["data"] = [];
        } else {
            $aktivitasContact = fetchAktivitasContact($conn, $aktivitasData["data"]["id_venue"]);

            if ($aktivitasContact["status"] == "error") {
                $aktivitasContact["data"] = [];
            }
        }

        if ($aktivitasMember["status"] == "error") {
            $aktivitasMember["data"] = [];
        }

        if ($aktivitasStatus["status"] == "error") {
            $aktivitasStatus["data"] = "false";
        }

        $data = array(
            "aktivitas_data" => $aktivitasData["data"],
            "aktivitas_contact" => $aktivitasContact["data"],
            "aktivitas_member" => $aktivitasMember["data"],
            "joined" => $aktivitasStatus["data"]["is_joined"],
        );

        // response sukses
        $response = array("status" => "success", "message" => "Data venue sukses didapatkan", "data" => $data);
    }
} else {
    $response = array("status" => "error", "message" => "not put method");
}

echo json_encode($response);
