<?php
require_once 'bootstrap.php';
require_once 'pre-checks.php';

$templateParams['title'] = 'Reddint - Settings';
$templateParams['fileName'] = 'settings-page.php';
$templateParams['scriptFileName'] = 'fetch-settings.js';

$user = $dbh->getUser($_SESSION['username']);
$templateParams['userBio'] = $user['bio'];

require_once 'template/base.php';
?>