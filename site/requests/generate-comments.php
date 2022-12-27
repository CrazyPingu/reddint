<?php

function generateJson(array $comments, DatabaseHelper $dbh): string {
    $json = array();
    foreach ($comments as $comment) {
        $jsonComment = array();
        $jsonComment['username'] = $dbh->getUser($comment['author'])['username'];
        $jsonComment['commentUserVote'] = isset($_SESSION['userId']) ? $dbh->getUserCommentVote($_SESSION['userId'], $comment['id']) : 0;
        $jsonComment['commentVote'] =  $dbh->getCommentVote($comment['id']);
        $jsonComment['content'] = $comment['content'];
        $jsonComment['creationDate'] = $comment['creation_date'];
        $jsonComment['commentId'] = $comment['id'];
        array_push($json, $jsonComment);
    }
    return json_encode($json);
}

?>