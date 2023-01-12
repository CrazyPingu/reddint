<?php
include_once '../bootstrap.php';

$args = json_decode($_POST['args'], false);

if (!isset($args->value)) {
    echo json_encode(false);
    exit();
}

echo json_encode($dbh->searchUsersAndCommunities($args->value));
?>