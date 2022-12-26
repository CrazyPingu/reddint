<form action='#' method='POST'>
    <h2>Login</h2>
    <?php if (isset($templateParams['loginError'])): ?>
        <p><?php echo $templateParams['loginError']; ?></p>
    <?php endif; ?>
    <ul>
        <li><label for='email'>Email: </label><input type='email' id='email' name='email' /></li>
        <li><label for='password'>Password: </label><input type='password' id='password' name='password' /></li>
        <li><input type='submit' name='submit' value='Login' /></li>
    </ul>
</form>
<a href='./signup.php'>Do you need an account? Sign up now!</a>