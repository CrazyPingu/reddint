<div class="loginForm">
    <div class='loginContainer'>
        <h2>Login</h2>
        <form method='POST' id='login-form'>
            <div class='txtField'>
                <label for='email'>Email:</label>
                <input type='email' id='email' name='email' required />
            </div>
            <div class='txtField'>
                <label for='password'>Password:</label>
                <input type='password' id='password' name='password' required />
            </div>
            <input type='submit' name='submit' value='Login' />
            <p class='outputLogin' id='response'></p>
            <p>Do you need an account?&nbsp;<a href='./signup.php'>Sign up now!</a></p>
        </form>
    </div>
</div>