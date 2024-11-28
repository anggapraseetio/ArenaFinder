<?php

function sendPushNotification($fields = array())
{
    $API_ACCESS_KEY = 'AAAAdR8F-d8:APA91bGTphcXqEJ8mtJNP1vSKR4SExyuc5MRpLlFvbTXPKcWjV-jtNVVwpPT7ibpBJgaVQH3hN3oIQWRaKVdToZdBHkGVWdp3ltAsJ_zxuPje5DePTaF1WRAvXefl4ch2JhMaspRX3Xt';
    $headers = array
    (
        'Authorization: key=' . $API_ACCESS_KEY,
        'Content-Type: application/json'
    );
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $result = curl_exec($ch );
    curl_close( $ch );
    echo $result;
}

$title = "Test";
$message = "Hello World";

$fields = array
(
    'to'  => '/topics/Your topic name',
    'priority' => 'high',
    'notification' => array(
        'body' => $message,
        'title' => $title,
        'sound' => 'default',
        'icon' => 'https://github.com/haqiachd/ArenaFinder-Mobile/blob/main/images/logo-c2.png',
       	'image'=> ''
    ),
    'data' => array(
        'message' => $message,
        'title' => $title,
        'sound' => 'default',
        'icon' => 'https://github.com/haqiachd/ArenaFinder-Mobile/blob/main/images/logo-c2.png',
        'image'=> ''
    )


);

sendPushNotification($fields);