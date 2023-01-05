<?php
require_once 'bootstrap.php';

$templateParams['title'] = 'Reddint - Post';
$templateParams['fileName'] = 'post-page.php';
$templateParams['scriptFileName'] = 'fetch-post.js';
$templateParams['postId'] = $_GET['postId'];
$post = $dbh->getPost($_GET['postId']);
if ($post) {
    $templateParams['postAuthor'] = $post['author'];
} else {
    header('location: index.php');
    exit();
}

require_once 'template/base.php';
?>
