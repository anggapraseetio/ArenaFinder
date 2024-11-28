<?php
// Cek apakah permintaan adalah metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Menerima token perangkat dari permintaan POST
    $deviceToken = $_POST['device_token'];

    // Server Key Firebase Cloud Messaging (FCM)
    $serverKey = 'AAAAdR8F-d8:APA91bGTphcXqEJ8mtJNP1vSKR4SExyuc5MRpLlFvbTXPKcWjV-jtNVVwpPT7ibpBJgaVQH3hN3oIQWRaKVdToZdBHkGVWdp3ltAsJ_zxuPje5DePTaF1WRAvXefl4ch2JhMaspRX3Xt'; // Ganti dengan Server Key Anda

    // Firebase Cloud Messaging API URL
    $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

    // Data Notifikasi
    $notification = [
        'title' => 'Hello World',
        'body' => 'Isi Notifikasi'
    ];

    // Data Tambahan (Opsional)
    $data = [
        'key_1' => 'value_1',
        'key_2' => 'value_2'
    ];

    // Pesan
    $message = [
        'to' => $deviceToken, // Gunakan token perangkat yang diterima dari permintaan POST
        'notification' => $notification,
        'data' => $data
    ];

    // Header Permintaan
    $headers = [
        'Authorization: key=' . $serverKey,
        'Content-Type: application/json'
    ];

    // Inisialisasi cURL
    $ch = curl_init();

    // Set opsi cURL
    curl_setopt($ch, CURLOPT_URL, $fcmUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

    // Eksekusi cURL dan dapatkan respons
    $result = curl_exec($ch);
    if ($result === false) {
        die('Curl failed: ' . curl_error($ch));
    }

    curl_close($ch);

    // Handle respons (keberhasilan atau kesalahan) di sini
    echo $result;
} else {
    // Jika bukan permintaan POST, tampilkan pesan kesalahan
    echo 'Permintaan harus menggunakan metode POST.';
}
?>
