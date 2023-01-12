<?php
require_once 'bootstrap.php';

//base params
$templateParams['title'] = 'Reddint - Follow List';
$templateParams['fileName'] = 'follow-list-page.php';
$templateParams['scriptFileName'] = 'fetch-follow.js';

//follow-list-page params
$templateParams['userUsername'] = $_GET['username'];
$templateParams['type'] = $_GET['type'];

require_once 'template/base.php';
?>