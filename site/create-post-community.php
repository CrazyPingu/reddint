<?php
require_once 'bootstrap.php';
require_once 'pre-checks.php';

$templateParams['title'] = 'Reddint - Create Post or Community';
$templateParams['fileName'] = 'create-form.php';
$templateParams['scriptFileName'] = 'fetch-create-post-community.js';

$templateParams['communities'] = $dbh->getParticipatingCommunities($_SESSION['userId']);

require_once 'template/base.php';
?>