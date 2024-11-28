<?php

require 'Notification.php';

// Cek apakah permintaan adalah metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $deviceToken = $_POST['device_token'];
    $title = $_POST['title'];
    $body = $_POST['body'];

    $data = [
        'key_1' => 'data 1',
        'key_2' => 'data 2'
    ];

    $notif = new Notification();
    $status = $notif->sendNotif($deviceToken, $title, $body, $data);

    echo $status;


}else{

}