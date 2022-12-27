<?php
require_once '../bootstrap.php';
include_once 'generate-posts.php';

$communitiesFollowed; //= metodo da implementare
$posts = $dbh->getPostsByCommunities($communitiesFollowed, 10, $_POST['offset']);
$postHtml = generatePosts($posts, $dbh);
echo $postHtml;
?>
