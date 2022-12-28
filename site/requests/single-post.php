<?php
require_once '../bootstrap.php';
include_once 'generate-json.php';

$args = json_decode($_POST["args"], false);

$post = $dbh->getPost($args->postId);
echo generatePosts(array($post), $dbh);
?>
