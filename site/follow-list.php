<?php
require_once 'bootstrap.php';

// Base params
$templateParams['title'] = 'Reddint - Follow List';
$templateParams['fileName'] = 'follow-list-page.php';
$templateParams['scriptFileName'] = 'fetch-follow.js';

// Follow-list-page params
$templateParams['userUsername'] = $_GET['username'];
$templateParams['type'] = $_GET['type'];

require_once 'template/base.php';
?>