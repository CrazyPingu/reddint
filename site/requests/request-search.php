<?php
include_once '../bootstrap.php';

$args = json_decode($_POST['args'], false);

if (!isset($args->value)) {
    echo json_encode("error");
    exit();
}
$user = $dbh->searchUser($args->value);
$communities = $dbh->searchCommunities($args->value);
echo json_encode(array('user'=>$user, 'communities'=>$communities));

?>