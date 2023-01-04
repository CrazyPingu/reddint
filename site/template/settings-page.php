<section>
    <form method='POST' id='changeUsername'>
        <h2>Change Username</h2>
        <input type='text' name='newUsername' placeholder='New Username' value="<?php echo $_SESSION['username']; ?>"
            required /><br />
        <input type='submit' name='submitUsername' value='Change username' /><br />
    </form>
    <div id='messageUsername'></div>
</section>
<section>
    <form method='POST' id='changeBio'>
        <h2>Change Bio</h2>
        <textarea maxlength='2048' name='newBio' placeholder='Enter bio'
            required><?php echo $templateParams['userBio']; ?></textarea><br />
        <input type='submit' name='submitBio' value='Change bio' /><br />
    </form>
    <div id='messageBio'></div>
</section>
<section>
    <form method='POST' id='changePassword'>
        <h2>Change Password</h2>
        <input type='password' name='newPassword' placeholder='New Password' required /><br />
        <input type='password' name='confirmNewPassword' placeholder='Confirm New Password' required /><br />
        <input type='submit' name='submitPassword' value='Change password' /><br />
    </form>
    <div id='messagePassword'></div>
</section>
<section>
    <form method='POST' id='logout'>
        <h2>Logout</h2>
        <input type='submit' name='submitLogout' value='Logout' /><br />
    </form>
</section>