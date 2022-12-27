<?php
require_once '../bootstrap.php';
include_once 'generate-posts.php';

$usersFollowed = $dbh->getFollowed($_SESSION['userId']);
$posts = $dbh->getPostsByUsers($usersFollowed/*da cambiare*/, 10, $_POST['offset']);

echo generateJson($posts, $dbh);
?>
