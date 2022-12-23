<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' type="text/css" href='../css/style.css'>
    <link rel="icon" href="../res/favicon.png" type="image/png" sizes="16x16">
    <title><?php echo $templateParams['title']; ?></title>
</head>

<body>
    <header>
        <h1>Reddint</h1>
    </header>
    <div class="navbar">
        <ul>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="notification.php">Notification</a></li>
            <li><a href="settings.php">Settings</a></li>
        </ul>
        <form method="POST">
            <input type="text" name="search" placeholder="Search">
            <button type="submit">Search</button>
        </form>
    </div>
    <main>
        <?php
        if (isset($templateParams['name'])) {
            require($templateParams['name']);
        }
        ?>
    </main>
    <script src="<?php $templateParams['script'] ?? '';?>"></script>
</body>

</html>