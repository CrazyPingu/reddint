<?php
// Get the current page that requested the pre-checks
$page = basename($_SERVER['PHP_SELF']);

// Redirect from all the forbidden pages to login.php if the user is not logged in
$forbiddenPages = ['notifications.php', 'settings.php'];
if (!$isUserLogged && in_array($page, $forbiddenPages)) {
    header('Location: login.php');
    exit();
}
?>