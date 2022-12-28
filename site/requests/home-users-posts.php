<?php
require_once '../bootstrap.php';
include_once 'generate-json.php';

$usersFollowed = $dbh->getFollowed($_SESSION['userId']);
$args = json_decode($_POST["args"], false);
$posts = $dbh->getPostsByUsers(array_column($usersFollowed, 'id'), 10, $args->offset);

echo generatePosts($posts, $dbh);
?>
