<?php

$currentPath = $_SERVER['PHP_SELF'];
// var_dump($currentPath);
// die();
session_start();

// Check if user is not logged in
if (!isset($_SESSION['id'])) {
    if ($currentPath == "/day5/index.php" || $currentPath == "/day5/") {
        // Redirect to login page
        header("location: ./login.php");
        exit();
    } else {
        // Redirect to login page
        header("location: ../login.php");
        exit();
    }
}
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard</title>
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
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js" defer></script>
    <?php if ($currentPath == "/day5/index.php" || $currentPath == "/day5/"): ?>
        <link rel="stylesheet" href="./assets/css/cs-skin-elastic.css">
        <link rel="stylesheet" href="./assets/css/style.css">
        <script src="./assets/js/main.js"></script>

    <?php else: ?>
        <link rel="stylesheet" href="../assets/css/cs-skin-elastic.css" defer>
        <link rel="stylesheet" href="../assets/css/style.css" defer>
        <script src="../assets/js/main.js" defer></script>

    <?php endif; ?>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->

</head>

<body>
    <!-- Left Panel -->

    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="index.php"><i class="menu-icon fa fa-laptop"></i> Dashboard </a>
                    </li>

                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-cogs"></i>employees</a>
                        <ul class="sub-menu children dropdown-menu">
                            <?php if ($currentPath == "/day5/index.php" || $currentPath == "/day5/"): ?>
                                <li><i class="fa fa-puzzle-piece"></i><a href="./employees">All employees</a></li>
                                <li><i class="fa fa-id-badge"></i><a href="./employees/add.php">Add employee</a></li>
                            <?php else: ?>
                                <li><i class="fa fa-puzzle-piece"></i><a href="index.php">All employees</a></li>
                                <li><i class="fa fa-id-badge"></i><a href="add.php">Add employee</a></li>
                            <?php endif; ?>

                        </ul>
                    </li>
                    <li>
                        <?php if ($currentPath == "/day5/index.php" || $currentPath == "/day5/"): ?>
                            <a href="./employees/users.php"><i class="menu-icon fa fa-laptop"></i> Users </a>

                        <?php else: ?>
                            <a href="users.php"><i class="menu-icon fa fa-laptop"></i> Users </a>

                        <?php endif; ?>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->

    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">

        <!-- Header-->
        <header id="header" class="header">
            <div class="top-left">
                <div class="navbar-header">
                    <?php if ($currentPath == "/day5/index.php" || $currentPath == "/day5/"): ?>
                        <a class="navbar-brand" href="./"><img src="./images/logo.png" alt="Logo"></a>
                        <a class="navbar-brand hidden" href="./"><img src="./images/logo2.png" alt="Logo"></a>
                    <?php else: ?>
                        <a class="navbar-brand" href="./"><img src="../images/logo.png" alt="Logo"></a>
                        <a class="navbar-brand hidden" href="./"><img src="../images/logo2.png" alt="Logo"></a>
                    <?php endif; ?>

                    <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
                </div>
            </div>
            <div class="top-right">
                <div class="header-menu">
                    <div class="header-left">
                        <button class="search-trigger"><i class="fa fa-search"></i></button>
                        <div class="form-inline">
                            <form class="search-form">
                                <input class="form-control mr-sm-2" type="text" placeholder="Search ..." aria-label="Search">
                                <button class="search-close" type="submit"><i class="fa fa-close"></i></button>
                            </form>
                        </div>

                        <div class="dropdown for-notification">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bell"></i>
                                <span class="count bg-danger">3</span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="notification">
                                <p class="red">You have 3 Notification</p>
                                <a class="dropdown-item media" href="#">
                                    <i class="fa fa-check"></i>
                                    <p>Server #1 overloaded.</p>
                                </a>
                                <a class="dropdown-item media" href="#">
                                    <i class="fa fa-info"></i>
                                    <p>Server #2 overloaded.</p>
                                </a>
                                <a class="dropdown-item media" href="#">
                                    <i class="fa fa-warning"></i>
                                    <p>Server #3 overloaded.</p>
                                </a>
                            </div>
                        </div>

                        <div class="dropdown for-message">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="message" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-envelope"></i>
                                <span class="count bg-primary">4</span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="message">
                                <p class="red">You have 4 Mails</p>
                                <a class="dropdown-item media" href="#">
                                    <span class="photo media-left"><img alt="avatar" src="../images/avatar/1.jpg"></span>
                                    <div class="message media-body">
                                        <span class="name float-left">Jonathan Smith</span>
                                        <span class="time float-right">Just now</span>
                                        <p>Hello, this is an example msg</p>
                                    </div>
                                </a>
                                <a class="dropdown-item media" href="#">
                                    <span class="photo media-left"><img alt="avatar" src="../images/avatar/2.jpg"></span>
                                    <div class="message media-body">
                                        <span class="name float-left">Jack Sanders</span>
                                        <span class="time float-right">5 minutes ago</span>
                                        <p>Lorem ipsum dolor sit amet, consectetur</p>
                                    </div>
                                </a>
                                <a class="dropdown-item media" href="#">
                                    <span class="photo media-left"><img alt="avatar" src="../images/avatar/3.jpg"></span>
                                    <div class="message media-body">
                                        <span class="name float-left">Cheryl Wheeler</span>
                                        <span class="time float-right">10 minutes ago</span>
                                        <p>Hello, this is an example msg</p>
                                    </div>
                                </a>
                                <a class="dropdown-item media" href="#">
                                    <span class="photo media-left"><img alt="avatar" src="../images/avatar/4.jpg"></span>
                                    <div class="message media-body">
                                        <span class="name float-left">Rachel Santos</span>
                                        <span class="time float-right">15 minutes ago</span>
                                        <p>Lorem ipsum dolor sit amet, consectetur</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php if ($currentPath == "/day5/index.php" || $currentPath == "/day5/"): ?>
                                <img class="user-avatar rounded-circle" src="./images/admin.jpg" alt="User Avatar">

                            <?php else: ?>
                                <img class="user-avatar rounded-circle" src="../images/admin.jpg" alt="User Avatar">

                            <?php endif; ?>

                        </a>

                        <div class="user-menu dropdown-menu">
                            <a class="nav-link" href="#"><i class="fa fa-user"></i>My Profile</a>
                            <a class="nav-link" href="#"><i class="fa fa-bell-o"></i>Notifications <span class="count">13</span></a>
                            <a class="nav-link" href="#"><i class="fa fa-cog"></i>Settings</a>
                            <?php if ($currentPath == "/day5/index.php" || $currentPath == "/day5/"): ?>
                                <a class="nav-link" href="./logout.php"><i class="fa fa-power-off"></i>Logout</a>

                            <?php else: ?>
                                <a class="nav-link" href="../logout.php"><i class="fa fa-power-off"></i>Logout</a>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- /header -->