<?php
require_once '../bootstrap.php';
include_once 'generate-json.php';

$communitiesFollowed = $dbh->getParticipatingCommunities($_SESSION['userId']);
$args = json_decode($_POST["args"], false);
$posts = $dbh->getPostsByCommunities(array_column($communitiesFollowed, 'id'), 10, $args->offset);

echo generatePosts($posts, $dbh);
?>
