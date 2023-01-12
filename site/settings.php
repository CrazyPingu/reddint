<?php
require_once 'bootstrap.php';
require_once 'pre-checks.php';

// Base params
$templateParams['title'] = 'Reddint - Settings';
$templateParams['fileName'] = 'settings-page.php';
$templateParams['scriptFileName'] = 'fetch-settings.js';

// Settings-page params
$user = $dbh->getUser($_SESSION['username']);
$templateParams['userBio'] = $user['bio'];

require_once 'template/base.php';
?>