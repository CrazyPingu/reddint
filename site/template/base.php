<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' type='text/css' href='css/style.css'>
    <link rel='icon' href='res/favicon.ico' type='image/x-icon' sizes='16x16'>
    <title><?php echo $templateParams['title']; ?></title>
</head>

<body>
    <header>
        <nav>
            <h1><a href='./index.php' class='logo'>Reddint</a></h1>
            <input type='checkbox' id='toggler'>
            <label for='toggler'><i class='ri-menu-line'></i></label>
            <div class='menu'>
                <ul class='list'>
                    <li>
                        <input type='text' id='search' placeholder='Search'>
                        <div id='searchSpace'></div>
                    </li>
                    <?php if (!$isUserLogged) : ?>
                        <li><a href='login.php'>Login</a></li>
                    <?php endif; ?>
                    <li><a href=<?php echo 'profile.php' . ($isUserLogged ? '?username=' . $_SESSION['username'] : ''); ?>>Profile</a></li>
                    <li><a href='notifications.php'>Notifications <?php echo ($isUserLogged ? $templateParams['numNotifications'] : ''); ?></a></li>
                    <li><a href='settings.php'>Settings</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <?php
        if (isset($templateParams['fileName'])) {
            require_once $templateParams['fileName'];
        }
        ?>
    </main>
    <script src='<?php echo 'js/' . $templateParams['scriptFileName'] ?? ''; ?>' type='module'></script>
    <script src='./js/search-bar.js' type='module'></script>
</body>

</html>