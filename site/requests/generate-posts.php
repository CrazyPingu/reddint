<?php

function generateJson($posts, $dbh){
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



?>
