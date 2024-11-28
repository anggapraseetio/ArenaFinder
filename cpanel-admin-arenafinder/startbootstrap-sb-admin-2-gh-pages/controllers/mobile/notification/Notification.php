<?php


class Notification
{
    private $serverKey = 'AAAAdR8F-d8:APA91bGTphcXqEJ8mtJNP1vSKR4SExyuc5MRpLlFvbTXPKcWjV-jtNVVwpPT7ibpBJgaVQH3hN3oIQWRaKVdToZdBHkGVWdp3ltAsJ_zxuPje5DePTaF1WRAvXefl4ch2JhMaspRX3Xt';
    private $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

    public function sendNotif($deviceToken, $title, $body, $data)
    {

        $notification = [
            'title' => $title,
            'body' => $body,
            'click_action'=> "arenafinder.com://kitten/account/sign-up" 
        ];

        $message = [
            'to' => $deviceToken,
            'notification' => $notification,
            'data' => $data
        ];

        $headers = [
            'Authorization: key=' . $this->serverKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->fcmUrl);
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

        return $result;
    }
}
