<section>
    <form method='POST' id='changeUsername'>
        <h2>Change Username</h2>
        <input type='text' name='newUsername' placeholder='New Username' required /><br />
        <input type='submit' name='submitUsername' value='Change username' /><br />
    </form>
</section>
<section>
    <form method='POST' id='changeBio'>
        <h2>Change Bio</h2>
        <textarea maxlength='2048' placeholder='Enter bio' required></textarea><br />
        <input type='submit' name='submitBio' value='Change bio' /><br />
    </form>
</section>
<section>
    <form method='POST' id='changePassword'>
        <h2>Change Password</h2>
        <input type='password' name='oldPassword' placeholder='Old Password' required /><br />
        <input type='password' name='newPassword' placeholder='New Password' required /><br />
        <input type='password' name='confirmNewPassword' placeholder='Confirm New Password' required /><br />
        <input type='submit' name='submitPassword' value='Change password' /><br />
    </form>
</section>
<section>
    <form method='POST' id='logout'>
        <h2>Logout</h2>
        <input type='submit' name='submitLogout' value='Logout' /><br />
    </form>
</section>