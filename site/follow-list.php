<?php
    require_once 'bootstrap.php';

    $templateParams['title'] = 'Reddint - Follow List';
    $templateParams['fileName'] = 'follow-list-page.php';
    $templateParams['userUsername'] = $_GET['username'];
    $templateParams['type'] = $_GET['type'];
    $templateParams['scriptFileName'] = 'fetch-follow.js';

    require_once 'template/base.php';
?>