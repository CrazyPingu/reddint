<div class="signupForm">
    <div class="signupContainer">
        <h2>Sign up</h2>
        <form method='POST' id='signup-form'>
            <div class="txtField">
                <label for='email'>Email:</label>
                <input type='email' id='email' name='email' required />
            </div>
            <div class="txtField">
                <label for='username'>Username:</label>
                <input type='text' id='username' name='username' required />
            </div>
            <div class="txtField">
                <label for='password'>Password:</label>
                <input type='password' id='password' name='password' required />
            </div>
            <input type='submit' name='submit' value='Sign up' />
            <p class='response' id='response'></p>
            <p>Do you already have an account? <a href='./login.php'>Login now!</a></p>
        </form>
    </div>
</div>