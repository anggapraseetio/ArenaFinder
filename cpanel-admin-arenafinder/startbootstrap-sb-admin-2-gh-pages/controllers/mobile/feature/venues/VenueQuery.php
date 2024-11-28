<?php

require "../../../koneksi.php";

header("Content-Type: application/json");

class VenueQuery{

    function createQueryForVenue($operasional, $sewa, $where, $order, $limit){

        // query dengan jam operasional
        $qOperasionalData = '';
        $qOperasionalJoin = '';
        if($operasional === true){
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
        if($sewa === true){
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
            false, false,
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
            false, false, 
            "", 
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
     * get venue by venue kosong
     */
    function fetchVenueKosong($conn, $limit)
    {
        $sql = createQueryForVenue(
            true, true, 
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
    function fetchVenueGratis($conn, $limit){
    
        $sql = createQueryForVenue(
            true, false, 
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
    function fetchVenueBerbayar($conn, $limit){
    
        $sql = createQueryForVenue(
            true, false, 
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
    
    /**
     * get venue by venue 'disewakan'
     */
    function fetchVenueDisewakan($conn, $limit){
    
        $sql = createQueryForVenue(
            true, true, 
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
    

}

?>