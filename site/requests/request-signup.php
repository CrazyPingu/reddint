<?php
require_once '../bootstrap.php';

// Retrieve the arguments from the POST request
$args = json_decode($_POST["args"], false);

// Make sure arguments aren't empty
if (empty($args->email) || empty($args->password) || empty($args->username)) {
    echo json_encode(false);
    return;
}

// Add the user to the database
$result = $dbh->addUser($args->email, $args->password, $args->username);

// If the user was added successfully, log him in
// and return true, otherwise return false
if ($result) {
    $user = $dbh->getUser($args->email);
    $_SESSION['userId'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    echo json_encode(true);
} else {
    echo json_encode(false);
}

?>