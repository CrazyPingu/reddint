<?php
require_once 'bootstrap.php';
require_once 'pre-checks.php';

$templateParams['title'] = 'Reddint - Modify community';
$templateParams['scriptFileName'] = 'fetch-modify-community.js';

$community = $dbh->getCommunity($_GET['community']);
if ($community) {
    $templateParams['nameCommunity'] = $_GET['community'];
    $templateParams['description'] =  $dbh->getCommunity($_GET['community'])['description'];
    $templateParams['fileName'] = 'modify-community-form.php';
} else {
    header('Location: index.php');
}

require_once 'template/base.php';
?>