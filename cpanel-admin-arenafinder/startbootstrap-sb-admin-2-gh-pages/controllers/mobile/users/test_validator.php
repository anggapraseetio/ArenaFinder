<?php
require "Validator.php";

header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // post request
        $username = $_POST['username'];
        $email = $_POST['email'];
        $nama = $_POST['name'];
        $password = $_POST['password'];

        $val = new Validator();

        echo "username";
        echo json_encode($val->isValidUsername($username));
        echo "\n\n";
        echo "email";
        $val->isValidEmail($email);
        echo "\n\n";
        echo "nama";
        $val->isValidName($nama);
        echo "\n\n";
        echo "password";
        $val->isValidPassword($password);
    }else{
        echo "error";
    }

?>