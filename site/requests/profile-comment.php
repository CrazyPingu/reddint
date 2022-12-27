<?php
require_once '../bootstrap.php';

include_once 'generate-comments.php';

$comments = $dbh->getCommentsByUser($_SESSION['userId'], 10, $_POST['offset']);

echo generateJson($comments, $dbh);

?>

