<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "online_exam";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Get form data
$title = $_POST['title'];
$category = $_POST['category'];
$description = $_POST['description'];

$image_url = !empty($_POST['image_url']) ? $_POST['image_url'] : null;

// Image upload handling
$uploaded_image_path = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    // Absolute path on server
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/blogs/';

    // Create folder if not exists
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    // File extension
    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

    // Rename file based on title
    $cleanTitle = preg_replace("/[^a-zA-Z0-9]+/", "-", strtolower($title));
    $newFileName = $cleanTitle . "-" . time() . "." . $extension;

    // Full local server path
    $targetFilePath = $uploadDir . $newFileName;

    // Move file
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {

        // Save relative path to DB
       $uploaded_image_path = 'uploads/blogs/' . $newFileName;
    }
}

// Decide which image to store (uploaded > url)
$imageToStore = $uploaded_image_path ? $uploaded_image_path : $image_url;

// Insert into database
$stmt = $conn->prepare("
    INSERT INTO blogs (title, category, image, description) 
    VALUES (?, ?, ?, ?)
");

$stmt->bind_param("ssss", $title, $category, $imageToStore, $description);

if ($stmt->execute()) {
    header("Location: add-blog.php?success=1");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
