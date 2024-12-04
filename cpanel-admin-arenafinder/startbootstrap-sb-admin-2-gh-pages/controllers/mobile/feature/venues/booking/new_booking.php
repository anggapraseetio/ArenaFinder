<?php
require "../../../../koneksi.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the request
    $idVenue = $_POST['id_venue'];
    $email = $_POST['email'];
    $totalPrice = $_POST['total_price'];

    // Check if email exists in the users table
    $emailCheckStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $emailCheckStmt->bind_param("s", $email);
    $emailCheckStmt->execute();
    $emailCheckStmt->store_result();

    if ($emailCheckStmt->num_rows === 0) {
        // Email not found in users table
        $response = ["status" => "error", "message" => "Email not found in users table"];
        echo json_encode($response);
        exit;
    }
    $emailCheckStmt->close();

    // Prepare and execute the insert query
    $stmt = $conn->prepare("INSERT INTO venue_booking (id_venue, email, total_price, created_at) VALUES (?, ?, ?, NOW())");

    if (!$stmt) {
        $response = ["status" => "error", "message" => "SQL prepare failed: " . $conn->error];
        echo json_encode($response);
        exit;
    }

    // Bind parameters correctly
    $stmt->bind_param("iss", $idVenue, $email, $totalPrice);

    if ($stmt->execute()) {
        // Get the last inserted row
        $sql = "SELECT * FROM venue_booking 
                WHERE id_venue = ? AND email = ? AND total_price = ?
                ORDER BY id_booking DESC 
                LIMIT 1";
        $resultStmt = $conn->prepare($sql);
        $resultStmt->bind_param("iss", $idVenue, $email, $totalPrice);
        $resultStmt->execute();
        $result = $resultStmt->get_result();

        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $response = ["status" => "success", "message" => "Data inserted successfully", "data" => $data];
        } else {
            $response = ["status" => "error", "message" => "No data found after insertion"];
        }

        echo json_encode($response);
    } else {
        // Failed to insert data
        $response = ["status" => "error", "message" => "Error executing query: " . $stmt->error];
        echo json_encode($response);
    }

    // Close the database connections
    $stmt->close();
    $conn->close();
} else {
    // Invalid request method
    $response = ["status" => "error", "message" => "Invalid request method"];
    echo json_encode($response);
}
