<?php
require_once 'bootstrap.php';

// Redirect from login.php to index.php if the user is already logged in
if ($isUserLogged) {
    header('Location: index.php');
    exit();
}

// Base params
$templateParams['title'] = 'Reddint - Login';
$templateParams['fileName'] = 'login-form.php';
$templateParams['scriptFileName'] = 'fetch-login.js';

require_once 'template/base.php';

?>