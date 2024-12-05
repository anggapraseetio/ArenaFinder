<?php
include('database.php');

if (isset($_GET['jenis_lap']) && isset($_GET['keanggotaan'])) {
    $jenisLap = $_GET['jenis_lap'];
    $keanggotaan = $_GET['keanggotaan'];

    $keanggotaan = $keanggotaan === "member" ? 1 : 0;

    $sql = "SELECT price, price_membership FROM venue_price WHERE sport = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $jenisLap);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $price = $keanggotaan === 1 ? $row['price_membership'] : $row['price'];
        echo json_encode(['price' => $price]);
    } else {
        echo json_encode(['error' => 'Harga tidak ditemukan']);
    }
    $stmt->close();
} else {
    echo json_encode(['error' => 'Parameter tidak lengkap']);
}
