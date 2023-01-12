<?php
require_once '../bootstrap.php';

$args = json_decode($_POST['args'], false);
$result = false;

switch ($args->type) {
    // Update the user's username
    case 'changeUsername':
        $result = $dbh->updateUser($_SESSION['userId'], username: $args->newUsername);
        $_SESSION['username'] = $result ? $args->newUsername : $_SESSION['username'];
        break;
    // Update the user's email
    case 'changeBio':
        $result = $dbh->updateUser($_SESSION['userId'], bio: $args->newBio);
        break;
    // Update the user's password
    case 'changePassword':
        $result = $dbh->updateUser($_SESSION['userId'], password: $args->newPassword);
        break;
    // Logout the user
    case 'logout':
        session_destroy();
        $result = true;
        break;
    // Delete the user
    case 'delete':
        $result = $dbh->deleteUser($_SESSION['userId']);
        session_destroy();
        break;
}

echo json_encode($result);
?>