<?php
require_once '../bootstrap.php';

$args = json_decode($_POST["args"], false);
$type = $args->type;
$limit = $args->limit ?? 1;
$offset = $args->offset ?? 0;

$result = false;

switch ($type) {
    case 'get':
        $result = $dbh->getUserNotifications($_SESSION['userId'], $limit, $offset);
        break;
    case 'read':
        $result = $dbh->readAllNotifications($_SESSION['userId']);
        break;
    case 'add':
        if ($args->receiver == $_SESSION['username']) {
            echo json_encode(false);
            exit();
        }
        $result = $dbh->addNotification($args->receiver, $_SESSION['userId'], $args->content, $args->postId ?? null, $args->commentId ?? null);
        break;
}

echo json_encode($result);