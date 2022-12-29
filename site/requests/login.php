<?php
require_once '../bootstrap.php';

$args = json_decode($_POST["args"], false);

if (empty($args->email) || empty($args->password)) {
    echo json_encode(false);
    return;
}

$user = $dbh->logUser($args->email, $args->password);

if (is_null($user)) {
    echo json_encode(false);
    return;
}

$_SESSION['userId'] = $user['id'];
$_SESSION['username'] = $user['username'];
echo json_encode(true);
?>