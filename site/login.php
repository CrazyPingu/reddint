<?php

require_once 'bootstrap.php';

if(isset($_SESSION["userId"])) {
    header("Location: index.php");
}

if(isset($_POST['email']) && isset($_POST['password'])) {
    $loginResult = $dbh->checkLogin($_POST['username'], $_POST['password']);
    if(is_null($loginResult)) {
        $templateParams["loginError"] = "Errore! Credentials errate.";
    } else {
        $_SESSION["userId"] = $loginResult["id"];
        $_SESSION["username"] = $loginResult["username"];
    }
}

$templateParams["title"] = "Reddint - Login";
$templateParams["name"] = "login-form.php";

require 'template/base.php';
