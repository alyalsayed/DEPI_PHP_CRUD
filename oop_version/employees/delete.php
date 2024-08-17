<?php
require_once './classes/Employee.php';

session_start();

// Check if user is not logged in
if (!isset($_SESSION['id'])) {
    // Redirect to login page which is one level up
    header("location: ../login.php");
    exit();
}

if (isset($_GET['ssn'])) {
    $ssn = $_GET['ssn'];

    // Initialize Employee class
    $employee = new Employee();

    // Delete the employee
    if ($employee->delete($ssn)) {
        // Redirect to the index page
        header("location: index.php");
        exit();
    } else {
        echo "<h1 align='center'>Failed to delete employee. Please try again.</h1>";
    }
} else {
    echo "<h1 align='center'>Wrong page !!!!</h1>";
}
?>
