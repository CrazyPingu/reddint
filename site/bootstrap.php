<?php
session_start();
define("RES_SVG", "./res/svg/");
require_once 'db/database.php';
$dbh = new DatabaseHelper('detu.ddns.net', 'reddint', 'Chridbpd31122022', 'reddint', 3306);
$isUserLogged = isset($_SESSION['userId']) && isset($_SESSION['username']);
$templateParams['numNotifications'] = $dbh->getUnreadNotificationsCount($_SESSION['userId'] ?? -1);
?>