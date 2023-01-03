<?php
require_once '../bootstrap.php';

$args = json_decode($_POST['args'], false);
$result = false;

switch ($args->type) {
    case 'changeUsername':
        $result = $dbh->updateUser($_SESSION['userId'], username: $args->newUsername);
        $_SESSION['username'] = $result ? $args->newUsername : $_SESSION['username'];
        break;
    case 'changeBio':
        $result = $dbh->updateUser($_SESSION['userId'], bio: $args->newBio);
        break;
    case 'changePassword':
        $result = $dbh->updateUser($_SESSION['userId'], password: $args->newPassword);
        break;
    case 'logout':
        session_destroy();
        $result = true;
        break;
}

echo json_encode($result);
?>