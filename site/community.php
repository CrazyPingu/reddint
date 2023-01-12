<?php
require_once 'bootstrap.php';

$community = $dbh->getCommunity($_GET['community'] ?? -1);
// if the community is set gets the community info, otherwise redirect to index if the community has been deleted
if($community) {
    $templateParams['communityName'] = $community['name'];
    $templateParams['communityDescription'] = $community['description'];
    $templateParams['creationDate'] = $community['creation_date'];
    $templateParams['membersCount'] = $community['participating'];
    $templateParams['communityAuthor'] = $community['author'];
    $templateParams['isParticipating'] = $dbh->isParticipating($_SESSION['userId'] ?? -1, $community['id']);
} else if (isset($_GET['community'])) {
    header('Location: index.php');
    exit();
}

//base params
$templateParams['title'] = 'Reddint - Community';
$templateParams['fileName'] = 'community-page.php';
$templateParams['scriptFileName'] = 'fetch-community.js';

require_once 'template/base.php';
?>