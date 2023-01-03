<?php
require_once '../bootstrap.php';

$args = json_decode($_POST['args'], false);
$mode = isset($args->vote) ? 'set' : 'get';
$result = false;

if(!$isUserLogged) {
    echo json_encode(false);
    exit;
}

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