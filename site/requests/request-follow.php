<?php
require_once '../bootstrap.php';

$args = json_decode($_POST['args'], false);

$type = $args->type;

$result = false;

switch($type) {
    // Toggle the following of a user
    case 'follow':
        if ($isUserLogged) {
                $result = $dbh->followUser($_SESSION['userId'], $args->usernameProfile);
            if (!$result) {
                $result = $dbh->unfollowUser($_SESSION['userId'], $args->usernameProfile);
            }
        }
        break;
    // Return the followers of a user
    case 'followersList':
        $result = $dbh->getFollowers($args->usernameProfile, $args->limit, $args->offset);
        break;
    // Return the users followed by a user
    case 'followingList':
        $result = $dbh->getFollowed($args->usernameProfile, $args->limit, $args->offset);
        break;
}

echo json_encode($result);
?>