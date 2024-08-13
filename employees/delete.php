<?php
require_once '../includes/connection.php';

session_start();

// Check if user is not logged in
if (!isset($_SESSION['id'])) {
    // Redirect to login page which is one level up
    header("location: login.php");
    exit();
}

if (isset($_GET['ssn'])) {
    $ssn = $_GET['ssn'];
} else {
    echo "<h1 align='center'>wrong page !!!!</h1>";
    exit();
}

// Fetch all images associated with the employee
$image_query = "SELECT image_name FROM employee_images WHERE employee_ssn = :ssn";
$image_stmt = $connection->prepare($image_query);
$image_stmt->execute(['ssn' => $ssn]);
$images = $image_stmt->fetchAll(PDO::FETCH_ASSOC);

// Delete employee images from the database
$delete_images_query = "DELETE FROM employee_images WHERE employee_ssn = :ssn";
$delete_images_stmt = $connection->prepare($delete_images_query);
$delete_images_stmt->execute(['ssn' => $ssn]);

// Unlink each image file from the filesystem
foreach ($images as $image) {
    $image_path = "../uploads/images/" . $image['image_name'];
    if (file_exists($image_path)) {
        unlink($image_path);
    }
}

// Delete the employee record
$delete_employee_query = "DELETE FROM employee WHERE ssn = :ssn";
$delete_employee_stmt = $connection->prepare($delete_employee_query);
$delete_employee_stmt->execute(['ssn' => $ssn]);

// Redirect to the index page
header("location: index.php");
exit();
?>
