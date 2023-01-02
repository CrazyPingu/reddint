<?php
require_once '../bootstrap.php';
require_once '../pre-checks.php';

$args = json_decode($_POST['args'], false);
$mode = isset($args->vote) ? 'set' : 'get';
$result = false;

switch ($args->type) {
    case 'post':
        if ($mode == 'get')
            $result = $dbh->getUserPostVote($_SESSION['userId'], $args->id);
        else
            $result = $dbh->votePost($_SESSION['userId'], $args->id, $args->vote);
        break;
    case 'comment':
        if ($mode == 'get')
            $result = $dbh->getUserCommentVote($_SESSION['userId'], $args->id);
        else
            $result = $dbh->voteComment($_SESSION['userId'], $args->id, $args->vote);
        break;
}

echo json_encode($result);
?>