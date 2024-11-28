<?php

require "../../../../koneksi.php";

header("Content-Type: application/json");

function isCanComment($conn, $idVenue, $idUser)
{
    $sql = "SELECT status FROM venues WHERE id_venue = $idVenue";

    $result = $conn->query($sql);

    if ($result) {
        $data = $result->fetch_assoc();

        if ($data['status'] == 'Disewakan') {
            $sql = "SELECT u.id_users
            FROM users AS u 
            JOIN venue_booking AS vb 
            ON vb.email = u.email
            WHERE u.id_users = $idUser 
            AND vb.id_venue = $idVenue
            AND vb.payment_status != 'Pending'
            ";

            $result = $conn->query($sql);

            if ($result) {
                if ($result->num_rows > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    } else {
        return false;
    }
}

function fetchMyComment($conn, $idVenue, $idUser)
{
    $sql = "SELECT r.id_review, u.username, u.full_name, u.user_photo, 
        r.rating, DATE(r.date) as date, r.comment
        FROM venue_review AS r
        JOIN users AS u 
        ON r.id_users = u.id_users
        WHERE u.id_users = $idUser AND r.id_venue = $idVenue
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
    $sql = "SELECT r.id_review, u.username, u.full_name, r.rating, u.user_photo,
    DATE(r.date) AS date, r.comment
    FROM venue_review AS r 
    LEFT JOIN users AS u 
    ON r.id_users = u.id_users 
    WHERE id_venue = $idVenue AND r.rating IN (1, 2, 3, 4, 5)
    ORDER BY r.date DESC
    LIMIT 50";

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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $idUser = $_GET['id_user'];
    $idVenue = $_GET['id_venue'];

    $sql = "SELECT id_venue FROM venues WHERE id_venue = $idVenue";

    if ($conn->query($sql)->num_rows != 1) {
        $response = array("status" => "error", "message" => "Id $idVenue venue tidak ditemukan!");
    } else {

        $isCanComment = isCanComment($conn, $idVenue, $idUser);
        $myComment = fetchMyComment($conn, $idVenue, $idUser);
        $venRating = fetchVenueRatting($conn, $idVenue);
        $venComment = fetchVenueComment($conn, $idVenue);

        if ($myComment["status"] == "error") {
            $myComment = array("data" => ["id_review" => "0"]);
        }

        if ($venRating["status"] == "error") {
            $venRating = array("data" => []);
        }

        if ($venComment["status"] == "error") {
            $venComment = array("data" => []);
        }

        $data = array(
            "is_can_comment" => $isCanComment,
            "my_comment" => $myComment["data"],
            "venue_review" => $venRating["data"],
            "venue_comment" => $venComment["data"]
        );

        // response sukses
        $response = array("status" => "success", "message" => "Data venue sukses didapatkan", "data" => $data);
    }
} else {
    $response = array("status" => "error", "message" => "not put method");
}

echo json_encode($response);
