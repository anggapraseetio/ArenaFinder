<?php


use function PHPSTORM_META\type;

require '../../../vendor/autoload.php'; 
require "../../koneksi.php"; 
require 'EmailSender.php';

    // random kode otp
    function generateOTP($length = 6)
    {
        $otp = '';
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $otp .= $characters[rand(0, $charactersLength - 1)];
        }
        return $otp;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
        $email = $_POST['email'];
        $type = $_POST['type'];
        
        $otp = generateOTP();
    
        $mail = new PHPMailer(true);
    
        try {
            // konfigurasi smpt gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'arenafinder.app@gmail.com';
            $mail->Password = 'hftf uheb ztey nokf';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
        
            $mail->setFrom('arenafinder.app@gmail.com', 'ArenaFinder Dev');
            $mail->addAddress($email);
            $mail->Subject = "$otp adalah Kode OTP Anda";
        
            if($type === "SignUp"){
                $mail->Body = 'Gunakan kode otp berikut untuk memverifikasi akun anda: ' . $otp;
            }else if($type === "forgotpass"){
                $mail->Body = 'Gunakan kode otp berikut untuk menganti password anda: ' . $otp;
            }
        
            // Kirim email
            // $mail->send();
            $test = new EmailSender();
            $test->sendEmail("hakiahmad756@gmail.com", "SignUp", "423423");

            $startMillis = round(microtime(true) * 1000);
            $endMillis = $startMillis + 90000;
            $resendMillis = $startMillis + (60000 * 60);

            // $sql = "INSERT INTO verification VALUES (null, '$email', '$otp', '$type', $startMillis, $endMillis, '$device', 1, $resendMillis";
            // $sql = "INSERT INTO verification VALUES (null, '$email', '$otp', '$type', '$startMillis', '$endMillis', 'Mobile', 1, '$resendMillis')";
            $sql = "INSERT INTO verification (`email`, `otp`, `type`, `start_millis`, `end_millis`, `device`, `resend`, `resend_millis`) VALUES ('$email', '$otp', '$type', $startMillis, $endMillis, 'Mobile', 1, $resendMillis)";

            $result = $conn->query($sql);

            if($result === true){
                // temp data
                $data = array(
                    "otp" => $otp,
                    "start_millis" => $startMillis,
                    "end_millis" => $endMillis,
                    "type" => $type,
                    "device" => "mobile"
                );

                $response = array("status"=>"success", "message"=>"Kode otp berhasil terkirim", "data"=>$data);
            }else{
                $response = array("status"=>"error", "message"=>"Kode otp gagal tersimpan");
            }

        } catch (Exception $e) {
            $response = array("status" => "error", "message" => "Email tidak dapat dikirim. Error: " . $mail->ErrorInfo);
        }
    } else {
        $response = array("status" => "error", "message" => "method bukan post");
    }
    
    echo json_encode($response);
?>
