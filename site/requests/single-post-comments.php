<?php
require_once '../bootstrap.php';
include_once 'generate-json.php';

$args = json_decode($_POST["args"], false);
$comments = $dbh->getCommentsByPost($args->postId, 10, $args->offset);
echo generateComments($comments, $dbh);
?>