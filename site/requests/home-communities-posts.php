<?php
require_once '../bootstrap.php';
include_once 'generate-posts.php';

$communitiesFollowed = getParticipatingCommunities($_SESSION['userId']);
$posts = $dbh->getPostsByCommunities(array_column($communitiesFollowed, 'id'), 10, $_POST['offset'] ?? 0);

echo generateJson($posts, $dbh);
?>
