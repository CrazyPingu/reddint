<?php
require_once '../bootstrap.php';


$args = json_decode($_POST['args'], false);

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
        $username = $args->username ?? false;
        if ($username) {
            $comments = $dbh->getCommentsByUser($username, $limit, $offset);
        }
        break;
    // Add a comment
    case 'addComment':
        $postId = $args->postId ?? false;
        $commentContent = $args->commentContent ?? false;
        if ($postId && $commentContent) {
            $comments = $dbh->addComment($postId, $_SESSION['userId'], $commentContent);
        }
        break;
}

echo json_encode($comments);
?>