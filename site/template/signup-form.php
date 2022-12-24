<form action="#" method="POST">
    <h2>Sign up</h2>
    <?php if(isset($templateParams["errorSignUp"])): ?>
    <p><?php echo $templateParams["errorSignUp"]; ?></p>
    <?php endif; ?>
    <ul>
        <li>
            <label for="email">Email:</label><input type="email" id="email" name="email" />
        </li>
        <li>
            <label for="username">Username:</label><input type="text" id="username" name="username" />
        </li>
        <li>
            <label for="password">Password:</label><input type="password" id="password" name="password" />
        </li>
        <li>
            <input type="submit" name="submit" value="Sign up" />
        </li>
    </ul>
</form>