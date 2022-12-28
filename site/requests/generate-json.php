<?php

function generatePosts(array $posts, DatabaseHelper $dbh): string|false {
    $json = array();
    foreach ($posts as $post) {
        $jsonPosts = array();
        $jsonPosts['username'] = $dbh->getUser($post['author'])['username'];
        $jsonPosts['community'] = $dbh->getCommunity($post['community'])['name'];
        $jsonPosts['creationDate'] = $post['creation_date'];
        $jsonPosts['title'] = $post['title'];
        $jsonPosts['content'] = $post['content'];
        $jsonPosts['postId'] = $post['id'];
        $jsonPosts['postVote'] = isset($_SESSION['userId']) ? $dbh->getUserPostVote($_SESSION['userId'], $post['id']) : 0;
        $jsonPosts['numComments'] = $dbh->getCommentCountByPost($post['id']);
        $jsonPosts['numVotes'] = $dbh->getPostVote($post['id']);

        array_push($json, $jsonPosts);
    }

    return json_encode($json);
}

function generateComments(array $comments, DatabaseHelper $dbh): string|false {
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
