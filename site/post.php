<?php
require_once 'bootstrap.php';

$templateParams['title'] = 'Reddint - Post';
$templateParams['fileName'] = 'post-page.php';
$templateParams['scriptFileName'] = 'post.js';
$templateParams['postId'] = $_GET['postId'];

require_once 'template/base.php';
?>
