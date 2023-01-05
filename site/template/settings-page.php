<section>
    <h1>Change Username</h1>
    <form method='POST' id='changeUsername'>
        <input type='text' name='newUsername' placeholder='New Username' value="<?php echo $_SESSION['username']; ?>"
            required /><br />
        <input type='submit' name='submitUsername' value='Change username' /><br />
    </form>
    <div id='messageUsername'></div>
</section>
<section>
    <h1>Change Bio</h1>
    <form method='POST' id='changeBio'>
        <textarea maxlength='2048' name='newBio' placeholder='Enter bio'
            required><?php echo $templateParams['userBio']; ?></textarea><br />
        <input type='submit' name='submitBio' value='Change bio' /><br />
    </form>
    <div id='messageBio'></div>
</section>
<section>
    <h1>Change Password</h1>
    <form method='POST' id='changePassword'>
        <input type='password' name='newPassword' placeholder='New Password' required /><br />
        <input type='password' name='confirmNewPassword' placeholder='Confirm New Password' required /><br />
        <input type='submit' name='submitPassword' value='Change password' /><br />
    </form>
    <div id='messagePassword'></div>
</section>
<section>
    <h1>Logout</h1>
    <form method='POST' id='logout'>
        <input type='submit' name='submitLogout' value='Logout' /><br />
    </form>
</section>
<section>
    <h1>Delete account</h1>
    <div class='deleteButton'>
        <button type='button' id='deleteProfile'>Delete</button>
    </div>
</section>