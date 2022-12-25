<?php
session_start();
define('RES_DIR', './res/');
require_once 'db/database.php';
$dbh = new DatabaseHelper('detu.ddns.net', 'user', 'password', 'reddint', 3306);
?>