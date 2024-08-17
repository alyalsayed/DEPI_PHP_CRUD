<?php
require_once __DIR__ . '/includes/connection.php';
session_start();

if (isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid and not expired
    $query = "SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()";
    $stmt = $connection->prepare($query);
    $stmt->execute([$token]);
    $resetRequest = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resetRequest) {
        if (isset($_POST['reset_password'])) {
            $newPassword = sha1($_POST['password']);

            // Update the user's password
            $userId = $resetRequest['user_id'];
            $query = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $connection->prepare($query);
            $stmt->execute([$newPassword, $userId]);

            // Delete the reset token
            $query = "DELETE FROM password_resets WHERE token = ?";
            $stmt = $connection->prepare($query);
            $stmt->execute([$token]);

            $success = "Your password has been updated. You can now <a href='login.php'>login</a>.";
        }
    } else {
        $error = "Invalid or expired token.";
    }
} else {
    header("Location: forgot_password.php");
    exit();
}
?>

<!doctype html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reset Password</title>
    <meta name="description" content="Reset Password Page">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="https://i.imgur.com/QRAUqs9.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Reset Password</h2>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo ($error); ?></div>
        <?php } ?>
        <?php if (isset($success)) { ?>
            <div class="alert alert-success"><?php echo ($success); ?></div>
        <?php } ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" class="form-control" id="password" placeholder="New Password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="reset_password">Reset Password</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>