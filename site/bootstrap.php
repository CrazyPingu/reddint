<?php
session_start();
define("RES_SVG", "./res/svg/");
require_once 'db/database.php';
$dbh = new DatabaseHelper('hostname', 'username', 'password', 'database', 3306);
$isUserLogged = isset($_SESSION['userId']) && $dbh->getUser($_SESSION['userId']);
$templateParams['numNotifications'] = $dbh->getUnreadNotificationsCount($_SESSION['userId'] ?? -1);
?>