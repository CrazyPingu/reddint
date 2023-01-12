<?php
require_once 'bootstrap.php';

// base params
$templateParams['title'] = 'Reddint - Post';
$templateParams['fileName'] = 'post-page.php';
$templateParams['scriptFileName'] = 'fetch-post.js';

// post-page params
$templateParams['postId'] = $_GET['postId'];
$post = $dbh->getPost($_GET['postId']);
// if the post is set get the author of the post, otherwise if the post has been deleted redirects to index
if ($post) {
    $templateParams['postAuthor'] = $post['author'];
} else {
    header('location: index.php');
    exit();
}

require_once 'template/base.php';
?>
