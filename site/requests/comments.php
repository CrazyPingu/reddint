<?php
require_once '../bootstrap.php';
require_once '../pre-checks.php';

$args = json_decode($_POST["args"], false);
$type = $args->type ?? 'post';
$offset = $args->offset ?? 0;
$limit = $args->limit ?? 1;

$comments = false;

// Return comments from a post
if ($type == 'post'){
    $postId = $args->postId ?? false;
    if ($postId){
        $comments = $dbh->getCommentsByPost($postId, $limit, $offset);
    }
}

// Return comments from a user
if ($type == 'user'){
    $userId = $args->userId ?? false;
    if ($userId){
        $comments = $dbh->getCommentsByUser($userId, $limit, $offset);
    }
}

echo json_encode($comments);