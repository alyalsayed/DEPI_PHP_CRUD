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
$res = $connection->query("select image from employee where ssn=$ssn");
$image = $res->fetch(PDO::FETCH_ASSOC);
$image_name = $image["image"];
$result = $connection->query("delete from employee where ssn=$ssn");
unlink("../uploads/images/$image_name");
header("location: index.php");
?>