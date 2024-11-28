<?php

require "../../../../koneksi.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the request
    $idBooking = $_POST['id_booking'];
    $date = $_POST['date'];
    $idPrice = $_POST['id_price'];

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("INSERT INTO venue_booking_detail (id_booking, date, id_price) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $idBooking, $date, $idPrice);

    if ($stmt->execute()) {
        // Data inserted successfully
        $response = ["status" => "success", "message" => "Data inserted successfully"];
        echo json_encode($response);
    } else {
        // Failed to insert data
        $response = ["status" => "error", "message" => "Failed to insert data"];
        echo json_encode($response);
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
} else {
    // Invalid request method
    $response = ["status" => "error", "message" => "Invalid request method"];
    echo json_encode($response);
}
?>
