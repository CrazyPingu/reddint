<?php
require_once '../bootstrap.php';

include_once 'generate-posts.php';

$posts = $dbh->getPostsByUser($_SESSION['userId'], 10, $_POST['offset']);

echo generatePosts($posts);
