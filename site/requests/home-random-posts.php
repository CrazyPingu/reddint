<?php
require_once '../bootstrap.php';
include_once 'generate-posts.php';

$posts = $dbh->getRandomPosts();
echo generateJson($posts, $dbh);
?>
