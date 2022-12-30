<?php
require_once '../bootstrap.php';
require_once '../pre-checks.php';

$args = json_decode($_POST["args"], false);
$type = $args->type ?? 'post';
$offset = $args->offset ?? 0;
$limit = $args->limit ?? 1;

$comments = false;

switch ($type) {
    // Return comments from a post
    case 'post':
        $postId = $args->postId ?? false;
        if ($postId) {
            $comments = $dbh->getCommentsByPost($postId, $limit, $offset);
        }
        break;
    // Return comments from a user
    case 'user':
        $userId = $args->userId ?? false;
        if ($userId) {
            $comments = $dbh->getCommentsByUser($userId, $limit, $offset);
        }
        break;
    // Add a comment
    case 'addComment':
        $postId = $args->postId ?? false;
        $commentContent = $args->commentContent ?? false;
        if ($postId && $commentContent) {
            //TODO: add comment will return the comment
            $comments = $dbh->addComment($postId, $_SESSION['userId'], $commentContent);
        }
        break;
}

echo json_encode($comments);
?>