<?php

require_once 'bootstrap.php';

if(!(isset($_SESSION['username']) && $_SESSION['username'] == $_GET['username'] || isset($_GET['username']))) {
    header('Location: login.php');
    exit();
}

require_once 'pre-checks.php';

$templateParams['title'] = 'Reddint - Profile';
$templateParams['fileName'] = 'profile-page.php';

$user = $dbh->getUser($_GET['username']);

$templateParams['userUsername'] = $user['username'];
$templateParams['userBio'] = $user['bio'];
$templateParams['userCreationDate'] = $user['creation_date'];

$templateParams['followersCount'] = $user['followers'];
$templateParams['followingCount'] = $user['following'];

if ($isUserLogged) {
    $templateParams['isFollowing'] = $dbh->isFollowing($_SESSION['userId'], $user['id']);
}

$templateParams['scriptFileName'] = 'fetch-profile.js';

require_once 'template/base.php';

?>

