<?php
require_once 'bootstrap.php';

$templateParams['title'] = 'Reddint - Home';
$templateParams['fileName'] = 'home.php';
$templateParams['scriptFileName'] = isset($_SESSION['userId']) && isset($_SESSION['username']) ? 'home-logged.js' : 'home-not-logged.js';

require_once 'template/base.php';
?>
