<?php
$serverKey = 'AIzaSyD6AQ0vB2-BHhPdvoqmTNKTCTufC27fLJ4'; // Ganti dengan Server Key dari Firebase
$fcmUrl = 'https://fcm.googleapis.com/fcm/send';

$headers = array(
    'Authorization: key=' . $serverKey,
    'Content-Type: application/json'
);

$notification = array(
    'title' => 'Judul Notifikasi',
    'body' => 'Isi Notifikasi'
);

$data = array(
    'key_1' => 'value_1',
    'key_2' => 'value_2'
);

$message = array(
    'to' => 'DEVICE_TOKEN', // Ganti dengan token perangkat penerima
    'notification' => $notification,
    'data' => $data
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $fcmUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

$result = curl_exec($ch);
if ($result === false) {
    die('Curl failed: ' . curl_error($ch));
}

curl_close($ch);

// Handle the result (success or error) here
echo $result;
?>
