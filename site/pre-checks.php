<?php

if(isset($_SESSION["userId"]) && isset($_SESSION["username"])) {
    header("Location: index.php");
}

?>