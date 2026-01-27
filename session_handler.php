<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $_SESSION['email'] = $_POST['email'];
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>