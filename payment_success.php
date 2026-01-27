<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['payment_id']) || empty($_GET['payment_id'])) {
    header("Location: payment_gateway.php");
    exit();
}

$user_email = $_SESSION['email'];
$payment_id = $_GET['payment_id'];

// DB connection
$conn = new mysqli("localhost", "root", "", "online_exam");
if ($conn->connect_error) {
    die("Database connection failed");
}

/* 🔒 1. Prevent duplicate payment insert */
$checkPayment = $conn->prepare(
    "SELECT id FROM payments WHERE payment_id = ? LIMIT 1"
);
$checkPayment->bind_param("s", $payment_id);
$checkPayment->execute();
$checkPayment->store_result();

if ($checkPayment->num_rows > 0) {
    // Already processed
    header("Location: test-intructions.php");
    exit();
}
$checkPayment->close();

/* 🔒 2. Check if user already paid */
$checkUser = $conn->prepare(
    "SELECT payment_status FROM users WHERE email=? LIMIT 1"
);
$checkUser->bind_param("s", $user_email);
$checkUser->execute();
$checkUser->bind_result($payment_status);
$checkUser->fetch();
$checkUser->close();

if ($payment_status === 'Paid') {
    header("Location: test-intructions.php");
    exit();
}

/* 💾 3. Save payment */
$insert = $conn->prepare(
    "INSERT INTO payments (user_email, payment_id, status, created_at)
     VALUES (?, ?, 'Success', NOW())"
);
$insert->bind_param("ss", $user_email, $payment_id);
$insert->execute();
$insert->close();

/* 🟢 4. Update user status */
$update = $conn->prepare(
    "UPDATE users SET payment_status='Paid' WHERE email=?"
);
$update->bind_param("s", $user_email);
$update->execute();
$update->close();

$conn->close();

/* 🔁 5. Redirect (no refresh loop) */
header("Location: test-intructions.php");
exit();
