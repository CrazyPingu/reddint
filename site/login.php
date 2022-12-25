<?php

require_once 'bootstrap.php';

require_once 'pre-checks.php';

if(isset($_POST['email']) && isset($_POST['password'])) {
    $loginResult = $dbh->logUser($_POST['email'], $_POST['password']);
    if(is_null($loginResult)) {
        $templateParams['loginError'] = 'Error! Wrong credentials.';
    } else {
        $_SESSION['userId'] = $loginResult['id'];
        $_SESSION['username'] = $loginResult['username'];
    }
}

$templateParams['title'] = 'Reddint - Login';
$templateParams['fileName'] = 'login-form.php';

require_once 'template/base.php';

?>