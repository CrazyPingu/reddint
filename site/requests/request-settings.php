<?php
require_once '../bootstrap.php';

$args = json_decode($_POST['args'], false);

switch ($args->type) {
    case 'changeUsername':
        $newUsername = $args->newUsername;
        break;
    case 'changeBio':
        // $newBio = $args->newBio;
        // $user->changeBio($newBio);
        // break;
    case 'changePassword':
        // $oldPassword = $args->oldPassword;
        // $newPassword = $args->newPassword;
        // $confirmNewPassword = $args->confirmNewPassword;
        // $user->changePassword($oldPassword, $newPassword, $confirmNewPassword);
        // break;
    // case 'logout':
    //     session_destroy();
    //     break;
}
?>