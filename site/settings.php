<?php
require_once 'bootstrap.php';
require_once 'pre-checks.php';

$templateParams['title'] = 'Reddint - Settings';
$templateParams['fileName'] = 'settings-page.php';
$templateParams['scriptFileName'] = 'fetch-settings.js';

if (isset($_POST['submitLogout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

require_once 'template/base.php';
?>