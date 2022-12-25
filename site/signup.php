<?php
require_once 'bootstrap.php';
require_once 'pre-checks.php';

if(isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password'])) {
    $signUpResult = $dbh->addUser($_POST['email'], $_POST['password'], $_POST['username']);
    if(is_null($signUpResult)) {
        $templateParams['errorSignUp'] = 'Error during registration!';
    }
    else {
        header('Location: login.php');
    }
}

$templateParams['title'] = 'Reddint - Sign up';
$templateParams['fileName'] = 'signup-form.php';
require_once 'template/base.php';
?>