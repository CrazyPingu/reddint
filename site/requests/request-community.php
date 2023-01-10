<?php
require_once '../bootstrap.php';

$args = json_decode($_POST['args'], false);
$type = $args->type;

$result = false;
switch ($type) {
    case 'participation':
        if ($isUserLogged) {
            $result = $dbh->joinCommunity($_SESSION['userId'], $args->communityName);
            if (!$result) {
                $result = $dbh->leaveCommunity($_SESSION['userId'], $args->communityName);
            }
        }
        break;
    case 'post':
        $result = $dbh->getPostsByCommunity($args->communityName, $args->limit, $args->offset);
        break;
    case 'community':
        if ($isUserLogged) {
            $result = $dbh->getParticipatingCommunities($_SESSION['userId'], $args->limit, $args->offset);
        }
        else {
            $result = $dbh->getRandomCommunities($args->limit, $args->offset);
        }
        break;
    case 'create':
        if ($isUserLogged) {
            $result = $dbh->addCommunity($_SESSION['userId'], $args->nameCommunity, $args->description);
            if($result) {
                $dbh->joinCommunity($_SESSION['userId'], $args->nameCommunity);
            }
        }
        break;
    case 'edit':
        $result = $dbh->updateCommunity($args->nameCommunity, $args->description);
        break;
    case 'delete':
        $result = $dbh->deleteCommunity($args->nameCommunity);
        break;
}

echo json_encode($result);
?>