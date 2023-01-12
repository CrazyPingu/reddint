<?php
require_once 'bootstrap.php';
require_once 'pre-checks.php';

// base params
$templateParams['title'] = 'Reddint - Create Post or Community';
$templateParams['fileName'] = 'create-form.php';
$templateParams['scriptFileName'] = 'fetch-create-post-community.js';

// create-form params
$templateParams['communities'] = $dbh->getParticipatingCommunities($_SESSION['userId']);

require_once 'template/base.php';
?>