<?php
require "../../../koneksi.php";

header("Content-Type: application/json");

function fetchVenueData($conn, $idVenue)
{
    $sql = "SELECT 
        v.id_venue, v.venue_name, v.status, v.sport, v.price, 
        IFNULL(
                MIN(p.price), 0
        ) AS harga_sewa ,
        v.coordinate, v.location, v.views, v.shared, v.desc_venue, v.desc_facility
        FROM venues AS v 
        LEFT JOIN venue_price AS p
        ON v.id_venue = p.id_venue 
        WHERE v.id_venue = $idVenue";

    // echo $sql;

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

function fetchVenueOperasional($conn, $idVenue)
{
    $sql = "SELECT 
            o.day_name, TIME_FORMAT(o.opened, '%H:%i') AS opened, TIME_FORMAT(o.closed, '%H:%i') AS closed 
            FROM venue_operasional AS o 
            WHERE id_venue = $idVenue
            GROUP BY day_name
    ;";

    $result = $conn->query($sql);

    if ($result) {
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $days = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];

        $data_baru = array();

        foreach ($days as $day) {
            $found = false;
            foreach ($data as $row) {
                if ($row["day_name"] === $day) {
                    $found = true;
                    $opened = $row["opened"];
                    $closed = $row["closed"];
                    $data_baru[$day] = array(
                        "opened" => $opened,
                        "closed" => $closed
                    );
                }
            }

            if (!$found) {
                // Jika day tidak ditemukan dalam data, atur opened dan closed menjadi 'tutup'
                $data_baru[$day] = array(
                    "opened" => "tutup",
                    "closed" => "tutup"
                );
            }
        }

        // Sekarang $data_baru berisi seluruh data dengan penanganan jika day_name tidak ada dalam $days


        if (!empty($data)) {
            return array("status" => "success", "message" => "Data berhasil didapatkan", "data" => $data_baru);
        } else {
            return array("status" => "error", "message" => "Data tidak ditemukan");
        }
    } else {
        return array("status" => "error", "message" => "Perintah gagal dijalankan" . $conn->error);
    }
}
function fetchVenueFasilitas($conn, $idVenue)
{

    $sql = "SELECT 
        f.id_fasilitas, f.nama_fasilitas, f.fasilitas_photo 
        FROM venue_fasilitas AS f 
        WHERE id_venue = $idVenue";

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
function fetchVenueRatting($conn, $idVenue)
{
    $sql = "SELECT 
        IFNULL(COUNT(r.id_review), 0) AS total_review,
        IFNULL(ROUND(SUM(r.rating) / COUNT(r.id_review), 1), 0) AS rating,
        IFNULL(SUM(CASE WHEN r.rating = 1 THEN 1 ELSE 0 END), 0) AS rating_1,
        IFNULL(SUM(CASE WHEN r.rating = 2 THEN 1 ELSE 0 END), 0) AS rating_2,
        IFNULL(SUM(CASE WHEN r.rating = 3 THEN 1 ELSE 0 END), 0) AS rating_3,
        IFNULL(SUM(CASE WHEN r.rating = 4 THEN 1 ELSE 0 END), 0) AS rating_4,
        IFNULL(SUM(CASE WHEN r.rating = 5 THEN 1 ELSE 0 END), 0) AS rating_5
        FROM venue_review AS r
        WHERE r.id_venue = $idVenue
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

function fetchVenueComment($conn, $idVenue)
{
    $sql = "SELECT
    r.id_review,
    u.username,
    u.full_name,
    r.rating,
    u.user_photo,
    DATE(r.date) AS date,
    r.comment
FROM
    venue_review AS r
LEFT JOIN
    users AS u ON r.id_users = u.id_users
WHERE
    id_venue = $idVenue AND r.rating IN (1, 2, 3, 4, 5)
ORDER BY
    r.date DESC
LIMIT 3;
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

function fetchVenueContact($conn, $idVenue)
{
    $sql = "SELECT u.id_users, u.full_name, u.user_photo, u.no_hp FROM venues AS v 
    LEFT JOIN users AS u 
    ON u.email = v.email
    WHERE id_venue = $idVenue
    GROUP BY no_hp";

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

function fetchVenuePhoto($conn, $idVenue)
{
    $sql = "SELECT CONCAT('venue/', venue_photo) AS photo FROM venues WHERE id_venue = $idVenue
        UNION 
            SELECT CONCAT('venue/', photo) AS photo  FROM venue_aktivitas WHERE id_venue = $idVenue
        UNION 
            SELECT CONCAT('venue/', fasilitas_photo)  AS photo FROM venue_fasilitas WHERE id_venue = $idVenue
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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $idVenue = $_GET['id_venue'];

    $sql = "SELECT id_venue FROM venues WHERE id_venue = $idVenue";

    if ($conn->query($sql)->num_rows != 1) {
        $response = array("status" => "error", "message" => "Id $idVenue venue tidak ditemukan!");
    } else {

        // update views
        $sql = "UPDATE venues SET views = (views + 1) WHERE id_venue = $idVenue;";
        $conn->query($sql);

        $venueData = fetchVenueData($conn, $idVenue);
        $venOperasional = fetchVenueOperasional($conn, $idVenue);
        $venFasilitas = fetchVenueFasilitas($conn, $idVenue);
        $venRating = fetchVenueRatting($conn, $idVenue);
        $venComment = fetchVenueComment($conn, $idVenue);
        $venContact = fetchVenueContact($conn, $idVenue);
        $venPhoto = fetchVenuePhoto($conn, $idVenue);

        if ($venueData["status"] == "error") {
            $venueData = array("data" => []);
        }

        if ($venOperasional["status"] == "error") {
            $tutup = ["opened" => "tutup", "closed" => "tutup"];
            $jam = array(
                "Senin" => $tutup, "Selasa" => $tutup, "Rabu" => $tutup,
                "Kamis" => $tutup, "Jumat" => $tutup, "Sabtu" => $tutup,
                "Minggu" => $tutup
            );
            $venOperasional = array("data" => $jam);
        }

        if ($venContact["status"] == "error") {
            $venContact = array("data" => []);
        }

        if ($venFasilitas["status"] == "error") {
            $venFasilitas = array("data" => []);
        }

        if ($venRating["status"] == "error") {
            $venRating = array("data" => []);
        }

        if ($venComment["status"] == "error") {
            $venComment = array("data" => []);
        }

        if ($venPhoto["status"] == "error") {
            $venPhoto = array("data" => []);
        }

        $data = array(
            "venue_data" => $venueData["data"],
            "operasional" => $venOperasional["data"],
            "contact" => $venContact["data"],
            "fasilitas" => $venFasilitas["data"],
            "rating" => $venRating["data"],
            "comment" => $venComment["data"],
            "photos" => $venPhoto["data"],
        );

        // response sukses
        $response = array("status" => "success", "message" => "Data venue sukses didapatkan", "data" => $data);
    }
} else {
    $response = array("status" => "error", "message" => "not put method");
}

echo json_encode($response);
