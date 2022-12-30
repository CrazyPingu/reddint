<?php
require_once  '../bootstrap.php';

if (!isset($_SESSION['userId'])) {
    echo json_encode(array('vote'=>'no-login', 'score'=>'no-login'));
    exit();
}

$args = json_decode($_POST['args'], false);

if ($args->type == 'post') {
    $dbh->votePost($_SESSION['userId'], $args->id, $args->vote);
    $vote = $dbh->getUserPostVote($_SESSION['userId'], $args->id);
    $score = $dbh->getPostVote($args->id);
} else if ($args->type == 'comment'){
    $dbh->voteComment($_SESSION['userId'], $args->id, $args->vote);
    $vote = $dbh->getUserCommentVote($_SESSION['userId'], $args->id);
    $score = $dbh->getCommentVote($args->id);
}

echo json_encode(array('vote'=>$vote ?? 'error', 'score'=>$score ?? 'error'));
?>