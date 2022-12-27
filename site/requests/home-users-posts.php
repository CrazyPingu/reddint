<?php
require_once '../bootstrap.php';
include_once 'generate-posts.php';

$usersFollowed = $dbh->getFollowed($_SESSION['userId']);
$posts = $dbh->getPostsByUsers(array_column($usersFollowed, 'id'), 10, $_POST['offset'] ?? 0);

echo generateJson($posts, $dbh);
?>
