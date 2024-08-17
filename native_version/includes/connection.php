<?php


try {
    // Create a new PDO connection
    $connection = new PDO('mysql:host=localhost;dbname=company', 'root', '');
} catch (Exception $e) {
    // Display error message and stop execution
    echo $e->getMessage();
    exit();
}
?>
