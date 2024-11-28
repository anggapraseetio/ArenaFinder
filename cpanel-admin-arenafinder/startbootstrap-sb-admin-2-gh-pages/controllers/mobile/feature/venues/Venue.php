<?php

require "../../../../koneksi.php";

class Venue
{

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

    function fetchVenueSearch($conn, $limit, $name)
    {
        $sql = $this->createQueryForVenue(
            true,
            true,
            "WHERE v.venue_name LIKE '%$name%' ",
            "ORDER BY v.id_venue DESC",
            $limit
        );

        return $this->checkResult($conn->query($sql));
    }

    function fetchVenueBaru($conn, $limit)
    {
        $sql = $this->createQueryForVenue(
            true,
            true,
            "",
            "ORDER BY v.id_venue DESC",
            $limit
        );

        return $this->checkResult($conn->query($sql));
    }

    function fetchVenueRekomendasi($conn, $limit)
    {
        $sql = $this->createQueryForVenue(
            true,
            true,
            "",
            "",
            $limit
        );

        return $this->checkResult($conn->query($sql));
    }

    /**
     * get venue by top ratting
     */
    function fetchVenueRatting($conn, $limit)
    {
        $sql = $this->createQueryForVenue(
            false,
            false,
            "",
            "ORDER BY rating DESC, total_review DESC",
            $limit
        );

        return $this->checkResult($conn->query($sql));
    }

    /**
     * get venue by location
     */
    function fetchVenueLokasi($conn, $limit)
    {
        $sql = $this->createQueryForVenue(
            false,
            false,
            "",
            "ORDER BY rating DESC, total_review DESC",
            $limit
        );

        return $this->checkResult($conn->query($sql));
    }

    /**
     * get venue by venue kosong
     */
    function fetchVenueKosong($conn, $limit)
    {
        $sql = $this->createQueryForVenue(
            true,
            true,
            "WHERE v.status = 'Disewakan'",
            "ORDER BY rating DESC, total_review DESC",
            $limit
        );

        return $this->checkResult($conn->query($sql));
    }


    /**
     * get venue by venue 'gratis'
     */
    function fetchVenueGratis($conn, $limit)
    {

        $sql = $this->createQueryForVenue(
            true,
            false,
            "WHERE v.status = 'Gratis'",
            "ORDER BY rating DESC, total_review DESC ",
            $limit
        );

        return $this->checkResult($conn->query($sql));
    }

    /**
     * get venue by top status 'berbayar'
     */
    function fetchVenueBerbayar($conn, $limit)
    {

        $sql = $this->createQueryForVenue(
            true,
            false,
            "WHERE v.status = 'Berbayar'",
            "ORDER BY rating DESC, total_review DESC, v.price ASC",
            $limit
        );

        return $this->checkResult($conn->query($sql));
    }

    /**
     * get venue by venue 'disewakan'
     */
    function fetchVenueDisewakan($conn, $limit)
    {

        $sql = $this->createQueryForVenue(
            true,
            true,
            "WHERE v.status = 'Disewakan'",
            "ORDER BY rating DESC, total_review DESC, v.price ASC",
            $limit
        );

        return $this->checkResult($conn->query($sql));
    }

    function checkResult($result)
    {
        if ($result) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
            if (!empty($data)) {
                return array("status" => "success", "message" => "Data berhasil didapatkan", "data" => $data);
            } else {
                return array("status" => "error", "message" => "Data tidak ditemukan");
            }
        } else {
            return array("status" => "error", "message" => "Perintah gagal dijalankan");
        }
    }


    function fetchVenueSport($conn, $sport, $limit)
    {

        // create query
        $sql = $this->createQueryForVenue(
            true,
            true,
            "WHERE v.sport = '$sport'",
            "",
            $limit
        );

        // return result
        return $this->checkResult($conn->query($sql));
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
            WHERE vb.id_venue = $idVenue AND vd.date = DATE(NOW()) AND payment_status != 'Rejected'
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
}
