<section>
    <h2>Change Username</h2>
    <form method='POST' class='settingsForm' id='changeUsername'>
        <input type='text' name='newUsername' placeholder='New Username' value="<?php echo $_SESSION['username']; ?>"
            pattern="^\S+$" required /><br />
        <input type='submit' name='submitUsername' value='Change username' /><br />
        <p class='warningPattern'>The username mustn't contain whitespaces</p>
    </form>
    <div class='outputMessageSettings' id='messageUsername'></div>
</section>
<section>
    <h2>Change Bio</h2>
    <form method='POST' class='settingsForm' id='changeBio'>
        <textarea maxlength='2048' name='newBio' placeholder='Enter bio'
            required><?php echo $templateParams['userBio']; ?></textarea><br />
        <input type='submit' name='submitBio' value='Change bio' /><br />
    </form>
    <div class='outputMessageSettings' id='messageBio'></div>
</section>
<section>
    <h2>Change Password</h2>
    <form method='POST' class='settingsForm' id='changePassword'>
        <input type='password' name='newPassword' placeholder='New Password' required /><br />
        <input type='password' name='confirmNewPassword' placeholder='Confirm New Password' required /><br />
        <input type='submit' name='submitPassword' value='Change password' /><br />
    </form>
    <div class='outputMessageSettings' id='messagePassword'></div>
</section>
<section>
    <h2>Logout</h2>
    <form method='POST' class='settingsForm' id='logout'>
        <input type='submit' name='submitLogout' value='Logout' /><br />
    </form>
</section>
<section>
    <h2>Delete account</h2>
    <div class='deleteButton'>
        <button type='button' id='deleteProfile'>Delete</button>
    </div>
</section>