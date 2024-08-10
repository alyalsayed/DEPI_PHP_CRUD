<?php
require_once __DIR__ . '/includes/connection.php';
session_start();

if (isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}
if (isset($_POST['request_reset'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $stmt = $connection->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Generate a unique token
        $token = bin2hex(random_bytes(16));
        $userId = $user['id'];

        // Store the token in the database with an expiration date
        $stmt = $connection->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, DATE_ADD(NOW(), INTERVAL 1 HOUR))");
        $stmt->execute(['user_id' => $userId, 'token' => $token]);

        // Send email with the reset link
        $resetLink = "http://localhost/day5/reset_password.php?token=" . urlencode($token); // Adjust URL for local
        $to = $email;
        $subject = "Password Reset Request";
        $message = "<p>Please click the following link to reset your password: <a href=\"$resetLink\">$resetLink</a></p>";
        $headers = "From: no-reply@yourdomain.com\r\n";
        $headers .= "Content-type: text/html\r\n";

        if (mail($to, $subject, $message, $headers)) {
            $success = "A password reset link has been sent to your email.";
        } else {
            $error = "Failed to send the reset link. Please try again.";
        }
    } else {
        $error = "No account found with that email address.";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Forgot Password</h2>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($error); ?></div>
        <?php } ?>
        <?php if (isset($success)) { ?>
            <div class="alert alert-success mt-3"><?php echo htmlspecialchars($success); ?></div>
        <?php } ?>
        <form action="" method="post" class="mt-4">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary" name="request_reset">Request Reset Link</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
