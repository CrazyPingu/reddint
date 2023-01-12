<?php
require_once '../bootstrap.php';

$args = json_decode($_POST['args'], false);
$type = $args->type;

$result = false;
switch ($type) {
    // Toggle the participation of a user in a community
    case 'participation':
        if ($isUserLogged) {
            $result = $dbh->joinCommunity($_SESSION['userId'], $args->communityName);
            if (!$result) {
                $result = $dbh->leaveCommunity($_SESSION['userId'], $args->communityName);
            }
        }
        break;
    // Return the posts from a community
    case 'post':
        $result = $dbh->getPostsByCommunity($args->communityName, $args->limit, $args->offset);
        break;
    // Return random communities or the communities followed by a user
    case 'community':
        if ($isUserLogged) {
            $result = $dbh->getParticipatingCommunities($_SESSION['userId'], $args->limit, $args->offset);
        }
        else {
            $result = $dbh->getRandomCommunities($args->limit, $args->offset);
        }
        break;
    // Create a community
    case 'create':
        if ($isUserLogged) {
            $result = $dbh->addCommunity($_SESSION['userId'], $args->nameCommunity, $args->description);
            if($result) {
                $dbh->joinCommunity($_SESSION['userId'], $args->nameCommunity);
            }
        }
        break;
    // Edit a community
    case 'edit':
        $result = $dbh->updateCommunity($args->nameCommunity, $args->description);
        break;
    // Delete a community
    case 'delete':
        $result = $dbh->deleteCommunity($args->nameCommunity);
        break;
}

echo json_encode($result);
?>