<?php

require 'vendor/autoload.php'; // Include the Composer autoloader

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FCMNotificationSender {
    private $firebase;

    public function __construct() {
        $factory = (new Factory)
            ->withServiceAccount('../../private/arenafinder-firebase-adminsdk.json')
            ->create();

        $this->firebase = $factory;
    }

    public function sendNotification($deviceToken, $title, $body) {
        $messaging = $this->firebase->getMessaging();

        $notification = Notification::create($title, $body);

        $message = CloudMessage::new()
            ->withTarget('token', $deviceToken) // Set the target device token
            ->withNotification($notification)
            ->withData(['key' => 'value']); // Additional data (optional)

        $messaging->send($message);
    }
}
