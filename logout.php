<?php
session_start();

if (isset($_SESSION['id'])) {
    require_once "includes/connection.php";
    $stmt = $connection->prepare("UPDATE users_activity SET last_activity = NOW() WHERE user_id = ?");
    $stmt->execute([$_SESSION['id']]);
}

// Regenerate session ID, unset, and destroy the session
session_regenerate_id();
session_unset();
session_destroy();

header("location: login.php");
exit();
?>
