<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' type='text/css' href='css/style.css'>
    <link rel='icon' href='res/ico/favicon.ico' type='image/x-icon' sizes='16x16'>
    <title>
        <?php echo $templateParams['title']; ?>
    </title>
</head>

<body>
    <input type='checkbox' id='toggle'>
    <nav>
        <h1><a href='./index.php' class='logo'>Reddint</a></h1>
        <label class='navbar-toggler' for='toggle'>
            <span class='bar'></span>
            <span class='bar'></span>
            <span class='bar'></span>
        </label>
        <ul class='nav-list'>
            <li class='nav-link nav-item'>
                <input type='text' id='search' placeholder='Search'>
                <div id='searchSpace'></div>
            </li>
            <li class='nav-link nav-item'>
                <a href='community.php'>
                    <img class='svg' alt='communities icon' src='<?php echo RES_SVG;?>community.svg' />
                    Communities
                </a>
            </li>
            <?php if (!$isUserLogged): ?>
                <li class='nav-link nav-item'>
                    <a href='login.php'>
                        <img class='svg' alt='login icon' src='<?php echo RES_SVG;?>login.svg' />
                        Login
                    </a>
                </li>
                <li class='nav-link nav-item'>
                    <a href='signup.php'>
                        <img class='svg' alt='signup icon' src='<?php echo RES_SVG;?>signup.svg' />
                        Signup
                    </a>
                </li>
            <?php else: ?>
                <li class='nav-link nav-item'>
                    <a href='create-post-community.php'>
                        <img class='svg' alt='create icon' src='<?php echo RES_SVG;?>create.svg' />
                        Create
                    </a>
                </li>
                <li class='nav-link nav-item'>
                    <a href=<?php echo 'profile.php?username=' . $_SESSION['username']; ?>>
                        <img class='svg' alt='profile icon' src='<?php echo RES_SVG;?>profile.svg' />
                        Profile
                    </a>
                </li>
                <li class='nav-link nav-item'>
                    <a href='notifications.php'>
                        <img class='svg' alt='notification icon' src='<?php echo RES_SVG;?>notification-bell.svg' />
                        Notifications&nbsp;<?php echo ($isUserLogged ? $templateParams['numNotifications'] : ''); ?>
                    </a>
                </li>
                <li class='nav-link nav-item'>
                    <a href='settings.php'>
                        <img class='svg' alt='settings icon' src='<?php echo RES_SVG;?>settings.svg' />
                        Settings
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <main>
        <?php
        if (isset($templateParams['fileName'])) {
            require_once $templateParams['fileName'];
        }
        ?>
    </main>
    <script src='<?php echo 'js/' . $templateParams['scriptFileName'] ?? ''; ?>' type='module'></script>
    <script src='js/search-bar.js' type='module'></script>
</body>

</html>