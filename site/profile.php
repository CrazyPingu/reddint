<?php

require_once 'bootstrap.php';

require_once 'pre-checks.php';

$templateParams['title'] = 'Reddint - Profile';
$templateParams['fileName'] = 'profile-page.php';
$user = $dbh->getUser($_SESSION['userId']);

$templateParams['userUsername'] = $user['username'];
$templateParams['userBio'] = $user['bio'];
$templateParams['userCreationDate'] = $user['creation_date'];

$templateParams['followersCount'] = $dbh->getFollowersCount($_SESSION['userId']);
$templateParams['followingCount'] = $dbh->getFollowedCount($_SESSION['userId']);

$templateParams['scriptFileName'] = 'fetch-profile-content.js';

require_once 'template/base.php';

?>

