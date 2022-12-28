<?php
require_once '../bootstrap.php';

include_once 'generate-json.php';

$args = json_decode($_POST["args"], false);

$posts = $dbh->getPostsByUser($_SESSION['userId'], 10, $args->offset);

echo generatePosts($posts, $dbh);