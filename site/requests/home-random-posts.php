<?php
require_once '../bootstrap.php';
include_once 'generate-posts.php';

$posts = $dbh->getRandomPosts();
$postHtml = generatePosts($posts, $dbh);
echo $postHtml;
?>
