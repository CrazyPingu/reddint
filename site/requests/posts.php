<?php
require_once '../bootstrap.php';
require_once '../pre-checks.php';

$args = json_decode($_POST["args"], false);
$type = $args->type ?? 'communities';
$offset = $args->offset ?? 0;
$limit = $args->limit ?? 1;

$posts = false;

// Return posts from users followed by the user logged in
if ($type == 'users' && $isUserLogged){
    $usersFollowed = $dbh->getFollowed($_SESSION['userId']);
    $posts = $dbh->getPostsByUsers(array_column($usersFollowed, 'id'), $limit, $offset);
}

// Return posts from a user
if ($type == 'user'){
    $userId = $args->userId ?? false;
    if ($userId){
        $posts = $dbh->getPostsByUsers([$userId], $limit, $offset);
    }
}

// Return posts from communities followed by the user, or random posts if the user is not logged in
if ($type == 'communities'){
    if ($isUserLogged){
        $communitiesFollowed = $dbh->getParticipatingCommunities($_SESSION['userId']);
        $posts = $dbh->getPostsByCommunities(array_column($communitiesFollowed, 'id'), $limit, $offset);
    } else {
        $posts = $dbh->getRandomPosts($limit, $offset);
    }
}

// Return posts from a community
if ($type == 'community'){
    $communityId = $args->communityId ?? false;
    if ($communityId){
        $posts = $dbh->getPostsByCommunities([$communityId], $limit, $offset);
    }
}

// Return a single post by id
if ($type == 'single'){
    $postId = $args->postId ?? false;
    if ($postId){
        $posts = $dbh->getPost($postId);
    }
}

echo json_encode($posts);
?>
