<?php

session_start();

$currentPath = $_SERVER['PHP_SELF'];

// Define the folder pattern to match any folder
$folderPattern = '/^\/[^\/]+\/(.+)$/';

// Check if user is not logged in
if (!isset($_SESSION['id'])) {
    // Match the folder pattern
    if (preg_match($folderPattern, $currentPath, $matches)) {
        $relativePath = $matches[1];
        // Redirect to login page based on the folder
        if ($relativePath == "index.php" || $relativePath == "") {
            header("location: ./login.php");
        } else {
            header("location: ../login.php");
        }
        exit();
    } else {
        // Default redirect if the folder pattern doesn't match
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

    <?php
    // Check current path for asset paths
    if (preg_match($folderPattern, $currentPath, $matches)) {
        $relativePath = $matches[1];
        if ($relativePath == "index.php" || $relativePath == "") {
            echo '<link rel="stylesheet" href="./assets/css/cs-skin-elastic.css">';
            echo '<link rel="stylesheet" href="./assets/css/style.css">';
            echo '<script src="./assets/js/main.js"></script>';
        } else {
            echo '<link rel="stylesheet" href="../assets/css/cs-skin-elastic.css" defer>';
            echo '<link rel="stylesheet" href="../assets/css/style.css" defer>';
            echo '<script src="../assets/js/main.js" defer></script>';
        }
    } else {
        // Default asset paths if the folder pattern doesn't match
        echo '<link rel="stylesheet" href="../assets/css/cs-skin-elastic.css" defer>';
        echo '<link rel="stylesheet" href="../assets/css/style.css" defer>';
        echo '<script src="../assets/js/main.js" defer></script>';
    }
    ?>

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
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
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-cogs"></i> Employees</a>
                        <ul class="sub-menu children dropdown-menu">
                            <?php
                            if (preg_match($folderPattern, $currentPath, $matches)) {
                                $relativePath = $matches[1];
                                if ($relativePath == "index.php" || $relativePath == "") {
                                    echo '<li><i class="fa fa-puzzle-piece"></i><a href="./employees">All employees</a></li>';
                                    echo '<li><i class="fa fa-id-badge"></i><a href="./employees/add.php">Add employee</a></li>';
                                } else {
                                    echo '<li><i class="fa fa-puzzle-piece"></i><a href="index.php">All employees</a></li>';
                                    echo '<li><i class="fa fa-id-badge"></i><a href="add.php">Add employee</a></li>';
                                }
                            } else {
                                echo '<li><i class="fa fa-puzzle-piece"></i><a href="index.php">All employees</a></li>';
                                echo '<li><i class="fa fa-id-badge"></i><a href="add.php">Add employee</a></li>';
                            }
                            ?>
                        </ul>
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
                    <?php
                    if (preg_match($folderPattern, $currentPath, $matches)) {
                        $relativePath = $matches[1];
                        if ($relativePath == "index.php" || $relativePath == "") {
                            echo '<a class="navbar-brand" href="./"><img src="./images/logo.png" alt="Logo"></a>';
                            echo '<a class="navbar-brand hidden" href="./"><img src="./images/logo2.png" alt="Logo"></a>';
                        } else {
                            echo '<a class="navbar-brand" href="./"><img src="../images/logo.png" alt="Logo"></a>';
                            echo '<a class="navbar-brand hidden" href="./"><img src="../images/logo2.png" alt="Logo"></a>';
                        }
                    } else {
                        echo '<a class="navbar-brand" href="./"><img src="../images/logo.png" alt="Logo"></a>';
                        echo '<a class="navbar-brand hidden" href="./"><img src="../images/logo2.png" alt="Logo"></a>';
                    }
                    ?>

                    <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
                </div>
            </div>
            <div class="top-right">
                <div class="header-menu">
                    <div class="header-left">




                        <div class="user-area dropdown float-right">
                            <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php
                                if (preg_match($folderPattern, $currentPath, $matches)) {
                                    $relativePath = $matches[1];
                                    if ($relativePath == "index.php" || $relativePath == "") {
                                        echo '<img class="user-avatar rounded-circle" src="./images/admin.jpg" alt="User Avatar">';
                                    } else {
                                        echo '<img class="user-avatar rounded-circle" src="../images/admin.jpg" alt="User Avatar">';
                                    }
                                } else {
                                    echo '<img class="user-avatar rounded-circle" src="../images/admin.jpg" alt="User Avatar">';
                                }
                                ?>
                            </a>

                            <div class="user-menu dropdown-menu">
                                <a class="nav-link" href="#"><i class="fa fa- user"></i>My Profile</a>

                                <a class="nav-link" href="../logout.php"><i class="fa fa-power -off"></i>Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
        </header><!-- /header -->