<?php

$path = __DIR__ . '/includes/connection.php';

if (file_exists($path)) {
    require_once $path;
} else {
    echo 'File does not exist: ' . $path;
}

session_start();

// Check if the user is already logged in
if (isset($_SESSION['id'])) {
    header("location: index.php");
    exit();
}

// Handle login form submission
if (isset($_POST['login'])) {
    global $connection; // Ensure $connection is available here

    $email = $_POST['email'];
    $password = sha1($_POST['password']);
    $remember = isset($_POST['remember']); // Check if Remember Me is checked

    $result = $connection->query("SELECT * FROM users WHERE email='$email' AND password='$password' AND is_admin='1'");
    $data = $result->fetch(PDO::FETCH_ASSOC);
    $count = $result->rowCount();

    if ($count > 0) {
        $_SESSION['id'] = $data['id'];
        $_SESSION['is_admin'] = $data['is_admin'];

        // Update users_activity table with last login time
        $stmt = $connection->prepare("
        INSERT INTO users_activity (user_id, last_login, last_activity)
        VALUES (:id, NOW(), NOW())
        ON DUPLICATE KEY UPDATE last_login = NOW(), last_activity = NOW()
    ");
        $stmt->execute(['id' => $_SESSION['id']]);

        if ($remember) {
            // Set a cookie to remember the user for 30 days
            setcookie('remember_me', $data['id'], time() + (30 * 24 * 60 * 60), '/'); // Expires in 30 days
        }

        header("location: index.php");
        exit();
    } else {
        $error = "Invalid email or password...";
    }
}

// Check if 'remember_me' cookie is set to auto-login
if (isset($_COOKIE['remember_me'])) {
    global $connection; // Ensure $connection is available here

    $userId = $_COOKIE['remember_me'];

    // Validate the cookie value and log in the user
    $stmt = $connection->prepare("SELECT * FROM users WHERE id = :id AND is_admin = '1'");
    $stmt->execute(['id' => $userId]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        $_SESSION['id'] = $data['id'];
        header("location: index.php");
        exit();
    }
}
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ela Admin - HTML5 Admin Template</title>
    <meta name="description" content="Ela Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="https://i.imgur.com/QRAUqs9.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
</head>

<body class="bg-dark">
    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-form">
                    <?php if (isset($error)) { ?>
                        <div class="alert alert-danger"><?php echo $error ?></div>
                    <?php } ?>
                    <form action="" method="post">
                        <div class="form-group">
                            <label>Email address</label>
                            <input type="email" class="form-control" placeholder="Email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" placeholder="Password" name="password" required>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember"> Remember Me
                            </label>
                            <label class="pull-right">
                                <a href="forgot_password.php">Forgotten Password?</a>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30" name="login">Sign in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>