<?php
require_once '../bootstrap.php';
require_once '../pre-checks.php';

$args = json_decode($_POST['args'], false);

$type = $args->type;

$result = false;

switch($type) {
    case 'follow':
        if ($isUserLogged) {    
                $result = $dbh->followUser($_SESSION['userId'], $args->usernameProfile);
            if (!$result) {
                $result = $dbh->unfollowUser($_SESSION['userId'], $args->usernameProfile);
            }
        }
        break;
    case 'followersList':
        $result = $dbh->getFollowers($args->usernameProfile, $args->limit, $args->offset);
        break;
    case 'followingList':
        $result = $dbh->getFollowed($args->usernameProfile, $args->limit, $args->offset);
        break;
}

echo json_encode($result);
?>