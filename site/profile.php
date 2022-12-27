<?php

require_once 'bootstrap.php';

require_once 'pre-checks.php';

$templateParams['title'] = 'Reddint - Profile';
$templateParams['fileName'] = 'profile-page.php';

$user = $dbh->getUser($_GET['username']);

$templateParams['userUsername'] = $user['username'];
$templateParams['userBio'] = $user['bio'];
$templateParams['userCreationDate'] = $user['creation_date'];

$templateParams['followersCount'] = $dbh->getFollowersCount($user['id']);
$templateParams['followingCount'] = $dbh->getFollowedCount($user['id']);

$templateParams['scriptFileName'] = 'fetch-profile-content.js';

require_once 'template/base.php';

?>

