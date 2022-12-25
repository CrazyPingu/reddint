<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' type='text/css' href='../css/style.css'>
    <link rel='icon' href='../res/favicon.png' type='image/png' sizes='16x16'>
    <title><?php echo $templateParams['title']; ?></title>
</head>
<body>
    <header>
        <div class='navbar'>
            <h1>Reddint</h1>
            <ul>
                <li><a href='profile.php'>Profile</a></li>
                <li><a href='notification.php'>Notifications</a></li>
                <li><a href='settings.php'>Settings</a></li>
            </ul>
            <form action='#' method='POST'>
                <input type='text' name='search' placeholder='Search'>
                <button type='submit'>Search</button>
            </form>
        </div>
    </header>
    <main>
        <?php
        if (isset($templateParams['fileName'])) {
            require_once $templateParams['fileName'];
        }
        ?>
    </main>
    <script src='<?php $templateParams['scriptFileName'] ?? ''; ?>'></script>
</body>
</html>