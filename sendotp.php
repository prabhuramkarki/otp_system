<?php
session_start();
include 'db_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function generateOTP($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $otp = '';
    
    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[rand(0, $charactersLength - 1)];
    }
    
    return $otp;
}

function sendOTP($email) {
    global $db_connection;

    $email_check_sql = "SELECT id FROM tbl_users WHERE email='$email'";
    $result = $db_connection->query($email_check_sql);

    if ($result->num_rows == 0) {
        echo "<script>alert('This email is not registered. Please sign up first.'); window.location.href='login.php';</script>";
        exit();
    }

    $otp = generateOTP();
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiry'] = time() + 120; //opt timer

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; 
        $mail->isSMTP(); 
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true; 
        $mail->Username   = 'iamprabhuramkarki@gmail.com'; 
        $mail->Password   = 'smix opxe metm zern'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port       = 587; 

        $mail->setFrom('iamprabhuramkarki@gmail.com', 'Prabhuram Karki');
        $mail->addAddress($email); 


        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "<p>Your OTP code is: <strong>$otp</strong></p><p>It is valid for 2 minutes.</p>";

        $mail->send();
    } catch (Exception $e) {
        echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}'); window.location.href='verifyotp.php';</script>";
        exit();
    }
}

if (isset($_POST['otp'])) {
    $input_otp = $_POST['otp'];
    
    $stored_otp = $_SESSION['otp'];
    $otp_expiry = $_SESSION['otp_expiry'];

    if ($input_otp === $stored_otp && time() < $otp_expiry) {
        header('Location: password.php');
        exit();
    } else {

        
        echo "<script>alert('Invalid or expired OTP. Please try again.'); window.location.href='verifyotp.php';</script>";
        exit();
    }
} elseif (isset($_GET['resend']) && $_GET['resend'] == 'true') {
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        sendOTP($email);
        echo "<script>alert('A new OTP has been sent to your email.'); window.location.href='verifyotp.php';</script>";
    } else {
        echo "<script>alert('No email address found. Please enter your email.'); window.location.href='login.php';</script>";
    }
    exit();
} elseif (isset($_POST['email'])) {
    $email = $_POST['email'];
    $_SESSION['email'] = $email; // Store the email in session for later use
    sendOTP($email);
    echo "<script>alert('OTP has been sent to your email.'); window.location.href='verifyotp.php';</script>";
    exit();
} else {
    echo "<script>alert('Invalid request.'); window.location.href='login.php';</script>";
    exit();
}
?>
