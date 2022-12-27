<?php
require_once '../bootstrap.php';
include_once 'generate-posts.php';

$communitiesFollowed; //= metodo da implementare
$posts = $dbh->getPostsByCommunities(/*da cambiare*/$communitiesFollowed, 10, $_POST['offset']);

echo generateJson($posts, $dbh);
?>
