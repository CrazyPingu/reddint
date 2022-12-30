<?php
require_once  '../bootstrap.php';
require_once  '../pre-checks.php';

$args = json_decode($_POST['args'], false);

switch($args->type) {
    case 'post':
        $dbh->votePost($_SESSION['userId'], $args->id, $args->vote);
        $vote = $dbh->getUserPostVote($_SESSION['userId'], $args->id);
        $score = $dbh->getPostVote($args->id);
        break;
    case 'comment':
        $dbh->voteComment($_SESSION['userId'], $args->id, $args->vote);
        $vote = $dbh->getUserCommentVote($_SESSION['userId'], $args->id);
        $score = $dbh->getCommentVote($args->id);
        break;
}

echo json_encode(array('vote'=>$vote ?? 'error', 'score'=>$score ?? 'error'));
?>