<?php
session_start();
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = new mysqli("localhost", "root", "", "online_exam");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function generateOTP() {
    return rand(100000, 999999);
}

if (isset($_POST['action']) && $_POST['action'] == "send_otp") {
    $email = $conn->real_escape_string($_POST['email']);
    $otp = generateOTP();

    $stmt = $conn->prepare("INSERT INTO users_otp (email, otp_code) VALUES (?, ?) ON DUPLICATE KEY UPDATE otp_code=?, created_at=NOW()");
    $stmt->bind_param("sss", $email, $otp, $otp);
    $stmt->execute();
    $stmt->close();

    // Send OTP via PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'mail.esacademy.org.in'; // SMTP Server
        $mail->SMTPAuth = true;
        $mail->Username = 'admissions@esacademy.org.in'; // Your email
        $mail->Password = 'ESA.Admissions@2025'; // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('admissions@esacademy.org.in', 'Online Exam');
        $mail->addAddress($email);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Your OTP code is: " . $otp;

        $mail->send();
        echo json_encode(["status" => "success", "message" => "OTP sent successfully"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Failed to send OTP: " . $mail->ErrorInfo]);
    }
    exit();
}

// Handling OTP verification
if (isset($_POST['action']) && $_POST['action'] == "verify_otp") {
    $email = $conn->real_escape_string($_POST['email']);
    $otp = $conn->real_escape_string($_POST['otp']);

    $stmt = $conn->prepare("SELECT id FROM users_otp WHERE email=? AND otp_code=? AND created_at >= NOW() - INTERVAL 10 MINUTE");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE users_otp SET is_verified=1 WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $_SESSION['email'] = $email;

        // ✅ Redirect path fixed (change folder name if needed)
        echo json_encode([
            "status" => "success",
            "message" => "OTP verified successfully",
            "redirect" => "/online_exam/stage_registration.php?email=$email"
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid or expired OTP"]);
    }
    $stmt->close();
    exit();
}

$conn->close();
?>
