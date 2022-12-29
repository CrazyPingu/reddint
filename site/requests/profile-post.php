<?php
require_once '../bootstrap.php';

include_once 'generate-json.php';

$args = json_decode($_POST["args"], false);

$posts = $dbh->getPostsByUser($args->username, 10, $args->offset);

echo generatePosts($posts, $dbh);