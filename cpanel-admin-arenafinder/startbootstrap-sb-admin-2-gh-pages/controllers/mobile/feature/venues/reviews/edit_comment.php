<?php

require "../../../../koneksi.php";

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    
    $rawData = file_get_contents("php://input");
    $response = array();

    $data = json_decode($rawData, true);

    if (isset($data['id_venue'])) {
        $idVenue = $data['id_venue'];
    } else {
       $idVenue = 0;
    }
    
    if (isset($data['id_users'])) {
        $idUser = $data['id_users'];
    } else {
       $idUser = 0;
    }

    if (isset($data['star'])) {
        $star = $data['star'];
        if($star <= 0 && $star > 5) {
            $star = 1;
        }
    } else {
       $star = 1;
    }

    if (isset($data['comment'])) {
        $comment = str_replace("\n", "", $data['comment']);
    } else {
       $comment = '';
    }

    $sql = "SELECT id_users FROM venue_review WHERE id_users = $idUser AND id_venue = $idVenue LIMIT 1";

    if(mysqli_query($conn, $sql)->num_rows > 0) {
        $sql = "UPDATE venue_review 
            SET rating = '$star', comment = '$comment', date = NOW() 
            WHERE id_users = '$idUser' AND id_venue = '$idVenue'
        ";
    }else{
        $sql = "INSERT INTO `venue_review` 
        (`id_review`, `id_venue`, `id_users`, `rating`, `comment`, `date`) 
        VALUES (NULL, '$idVenue', '$idUser', '$star', '$comment', current_timestamp());";
    }

    // echo $sql;
// Pengecekan nilai $star
if (!in_array($star, [1, 2, 3, 4, 5])) {
    $response = array("status" => "success", "message" => "");
} else {
    $sqlCheck = "SELECT id_users FROM venue_review WHERE id_users = $idUser AND id_venue = $idVenue LIMIT 1";

    if (mysqli_query($conn, $sqlCheck)->num_rows > 0) {
        $sql = "UPDATE venue_review 
            SET rating = '$star', comment = '$comment', date = NOW() 
            WHERE id_users = '$idUser' AND id_venue = '$idVenue'
        ";
    } else {
        $sql = "INSERT INTO `venue_review` 
            (`id_review`, `id_venue`, `id_users`, `rating`, `comment`, `date`) 
            VALUES (NULL, '$idVenue', '$idUser', '$star', '$comment', current_timestamp());
        ";
    }

    // Eksekusi query hanya jika rating valid
    $result = mysqli_query($conn, $sql);
    if ($result == true) {
        $response = array("status" => "success", "message" => "Ulasan berhasil diupdate");
    } else {
        $response = array("status" => "error", "message" => "Ulasan gagal diupdate");
    }
}

} else {
    $response = array("status" => "error", "message" => "not put method");
}
echo json_encode($response);
