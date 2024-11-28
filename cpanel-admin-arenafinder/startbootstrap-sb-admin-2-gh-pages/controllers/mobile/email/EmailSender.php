<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailSender
{
    private $smtpHost = 'smtp.gmail.com';
    private $smtpUsername = 'arenafinder.app@gmail.com';
    // private $smtpPassword = 'hftf uheb ztey nokf';
    private $smtpPassword = 'dnea fcjr dibw iham';
    private $smtpPort = 587;
    private $fromEmail = 'arenafinder.app@gmail.com';
    private $fromName = 'ArenaFinder Dev';

    public function generateOTP($length = 6)
    {
        $otp = '';
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $otp .= $characters[rand(0, $charactersLength - 1)];
        }
        return $otp;
    }

    public function sendReportedComment($username, $usernameReported, $comment, $reason, $venueId, $venueName)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $this->smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtpUsername;
            $mail->Password = $this->smtpPassword;
            $mail->SMTPSecure = 'tls';
            $mail->Port = $this->smtpPort;

            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->addAddress("arenafinder.app@gmail.com");
            $mail->Subject = "$username melaporkan komentar $usernameReported pada venue [$venueId]$venueName";

            $message = "
            PELAPORAN KOMENTAR $usernameReported
        
            Email Pelapor : $username
            Email Dilaporkan : $usernameReported
            Venue ID : $venueId 
            Venue Name : $venueName 
            Komentar : $comment 
            Reason : $reason 
            ";

            $mail->Body = $message;

            // kirim email
            $mail->send();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function sendReportedVenue($email, $reason, $venueId, $venueName)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $this->smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtpUsername;
            $mail->Password = $this->smtpPassword;
            $mail->SMTPSecure = 'tls';
            $mail->Port = $this->smtpPort;

            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->addAddress("arenafinder.app@gmail.com");
            $mail->Subject = "$email melaporkan venue [$venueId]$venueName";

            $message = "
            PELAPORAN VENUE $email
        
            Email Pelapor : $email
            Venue ID : $venueId 
            Venue Name : $venueName
            Reason : $reason 
            ";

            $mail->Body = $message;

            // kirim email
            $mail->send();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function sendStatusPesanan($email, $status)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $this->smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtpUsername;
            $mail->Password = $this->smtpPassword;
            $mail->SMTPSecure = 'tls';
            $mail->Port = $this->smtpPort;

            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->addAddress($email);

            if ($status == 'diterima') {
                $mail->Subject = "Pesanan booking Anda telah diterima";
                $message = "Pesanan booking Anda telah diterima, Silahkan datang ke tempat olahraga pada jadwal yang telah ditentukan dan lakukan pembayaran";
                $mail->Body = $message;
            } else if ($status == 'ditolak') {
                $mail->Subject = "Pesanan booking Anda telah ditolak";
                $message = "Pesanan booking Anda telah ditolak, Mohon maaf atas ketidaknyamanan ini.";
                $mail->Body = $message;
            }

            // kirim email
            $mail->send();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function sendEmail($email, $type, $otp)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $this->smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtpUsername;
            $mail->Password = $this->smtpPassword;
            $mail->SMTPSecure = 'tls';
            $mail->Port = $this->smtpPort;

            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->addAddress($email);
            $mail->Subject = "$otp adalah Kode OTP Anda";

            if ($type === "SignUp") {
                $mail->Body = 'Gunakan kode otp berikut untuk memverifikasi akun anda: ' . $otp;
            } else if ($type === "ForgotPass") {
                $mail->Body = 'Gunakan kode otp berikut untuk mengganti password anda: ' . $otp;
            }

            // kirim email
            $mail->send();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
