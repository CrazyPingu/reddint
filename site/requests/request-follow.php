<?php
require_once '../bootstrap.php';
require_once '../pre-checks.php';

$args = json_decode($_POST['args'], false);

$result = $dbh->followUser($_SESSION['userId'], $args->usernameProfile);
if (!$result) {
    $result = $dbh->unfollowUser($_SESSION['userId'], $args->usernameProfile);
}

echo json_encode($result);
?>