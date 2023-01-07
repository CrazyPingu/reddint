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
        $result = $dbh->addNotification($args->receiver, $_SESSION['userId'], $args->content, $args->type ?? null, $args->id ?? null);
        break;
}

echo json_encode($result);