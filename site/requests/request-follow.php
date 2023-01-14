<?php
require_once '../bootstrap.php';

$args = json_decode($_POST['args'], false);

$type = $args->type;

$result = false;

switch ($type) {
    // Toggle the following of a user
    case 'follow':
        if ($isUserLogged && $_SESSION['username'] != $args->usernameProfile) {
            $result = $dbh->followUser($_SESSION['userId'], $args->usernameProfile);
            if (!$result) {
                $result = $dbh->unfollowUser($_SESSION['userId'], $args->usernameProfile);
            }
        }
        break;
    // Return the followers of a user
    case 'followersList':
        $result = $dbh->getFollowers($args->usernameProfile, $args->limit, $args->offset);
        for ($i = 0; $i < count($result); $i++) {
            $result[$i]['following'] = $dbh->isFollowing($_SESSION['userId'] ?? -1, $result[$i]['id']);
            $result[$i]['usernameLogged'] = $_SESSION['username'] ?? null;
        }
        break;
    // Return the users followed by a user
    case 'followingList':
        $result = $dbh->getFollowed($args->usernameProfile, $args->limit, $args->offset);
        for ($i = 0; $i < count($result); $i++) {
            $result[$i]['following'] = $dbh->isFollowing($_SESSION['userId'] ?? -1, $result[$i]['id']);
            $result[$i]['usernameLogged'] = $_SESSION['username'] ?? null;
        }
        break;
}

echo json_encode($result);
?>