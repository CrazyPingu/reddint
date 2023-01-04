<?php
require_once '../bootstrap.php';

$args = json_decode($_POST["args"], false);
$type = $args->type ?? 'communities';
$offset = $args->offset ?? 0;
$limit = $args->limit ?? 1;

$posts = false;

switch ($type) {
    // Return posts from users followed by the user logged in
    case 'users':
        if ($isUserLogged) {
            $usersFollowed = $dbh->getFollowed($_SESSION['userId']);
            $posts = $dbh->getPostsByUsers(array_column($usersFollowed, 'id'), $limit, $offset);
        }
        break;
    // Return posts from a user
    case 'user':
        $username = $args->username ?? false;
        if ($username) {
            $posts = $dbh->getPostsByUsers([$username], $limit, $offset);
        }
        break;
    // Return posts from communities followed by the user, or random posts if the user is not logged in
    case 'communities':
        if ($isUserLogged) {
            $communitiesFollowed = $dbh->getParticipatingCommunities($_SESSION['userId']);
            $posts = $dbh->getPostsByCommunities(array_column($communitiesFollowed, 'id'), $limit, $offset);
        } else {
            $posts = $dbh->getRandomPosts($limit, $offset);
        }
        break;
    // Return posts from a community
    case 'community':
        $communityId = $args->communityId ?? false;
        if ($communityId) {
            $posts = $dbh->getPostsByCommunities([$communityId], $limit, $offset);
        }
        break;
    // Return a single post by id
    case 'single':
        $postId = $args->postId ?? false;
        if ($postId) {
            $posts = $dbh->getPost($postId);
        }
        break;
    case 'create':
        if ($isUserLogged) {
            $posts = $dbh->addPost($_SESSION['userId'], $args->community, $args->title, $args->content);
        }
}

echo json_encode($posts);
?>