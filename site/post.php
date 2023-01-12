<?php
require_once 'bootstrap.php';

// Base params
$templateParams['title'] = 'Reddint - Post';
$templateParams['fileName'] = 'post-page.php';
$templateParams['scriptFileName'] = 'fetch-post.js';

// Post-page params
$templateParams['postId'] = $_GET['postId'];
$post = $dbh->getPost($_GET['postId']);

// If the post is set get the author of the post, otherwise if the post has been deleted redirect to index
if ($post) {
    $templateParams['postAuthor'] = $post['author'];
} else {
    header('location: index.php');
    exit();
}

require_once 'template/base.php';
?>
