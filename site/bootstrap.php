<?php
session_start();
define('RES_DIR', './res/');
require_once 'db/database.php';
$dbh = new DatabaseHelper('detu.ddns.net', 'reddint', 'Chridbpd31122022', 'reddint', 3306);
?>