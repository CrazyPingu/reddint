<?php

// Redirect to index.php if the session is not set
if (!isset($_SESSION)) {
    header('Location: index.php');
    exit();
}

// Get the current page that requested the pre-checks
$page = basename($_SERVER['PHP_SELF']);
// Check the session variables to see if the user is logged in
$isUserLogged = isset($_SESSION['userId']) && isset($_SESSION['username']);

// Redirect from login.php to index.php if the user is already logged in
if ($isUserLogged && $page === 'login.php') {
    header('Location: index.php');
    exit();
}

?>