<?php
require_once 'bootstrap.php';

// Redirect from signup.php to index.php if the user is already logged in
if ($isUserLogged) {
    header('Location: index.php');
    exit();
}

// base params
$templateParams['title'] = 'Reddint - Sign up';
$templateParams['fileName'] = 'signup-form.php';
$templateParams['scriptFileName'] = 'fetch-signup.js';

require_once 'template/base.php';
?>