<?php
require_once '../bootstrap.php';
include_once 'generate-json.php';

$posts = $dbh->getRandomPosts();
echo generatePosts($posts, $dbh);
?>
