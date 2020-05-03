<?php
require_once 'includes/config.php';
require_once 'includes/classes/User.php';
require_once 'includes/classes/ButtonProvider.php';
require_once 'includes/classes/Video.php';
require_once 'includes/classes/VideoGrid.php';
require_once 'includes/classes/VideoGridItem.php';

$usernameLoggedIn = User::isLoggedIn() ? $_SESSION['userLoggedIn'] : 'Not Set';
$userLoggedInObj  = new User($con, $usernameLoggedIn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Youtube</title>
    <link rel="stylesheet" href="assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/video-player/plyr.css">
     <script src="assets/js/jquery/jquery-3.5.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap/bootstrap.min.js"></script>
    <script src="assets/video-player/plyr.min.js"></script>
    <script src="assets/js/commonActions.js"></script>
    <script src="assets/js/userActions.js"></script>

</head>
<body>

    <main id="pageContainer">
        <div id="mastHeadContainer">
            <button class='navShowHide'>
                <img src="assets/images/icons/menu.png" alt="Menu">
            </button>
            <a href="index.php" class='logoContainer'>
                <img src="assets/images/icons/VideoTubeLogo.png" alt="Site Logo">
            </a>
            <div class="searchBarContainer">
                <form action="search.php" method='GET'>
                    <input type="text" id='term' name='term' class='searchBar' placeholder='Search...'>
                    <button type='submit' class='searchButton'>
                        <img src="assets/images/icons/search.png" alt="Search">
                    </button>
                </form>
            </div>
            <div class="rightIcons">
                <a href="upload.php">
                    <img class='upload' src="assets/images/icons/upload.png" alt="Upload">
                </a>
                <a href="#">
                    <img class='upload' src="assets/images/profilePictures/default.png" alt="Profile Picture">
                </a>
            </div>
        </div>
        <div id="sideNavContainer"></div>
        <div id="mainSectionContainer">
            <div id="mainContentContainer">