import asyncRequest from './default-ajax.js';
import obtainData from './form-data.js';
import addConfirmButton from './add-buttons.js';

const changeUsernameForm = document.getElementById('changeUsername');
const messageUsername = document.getElementById('messageUsername');
const changeBioForm = document.getElementById('changeBio');
const messageBio = document.getElementById('messageBio');
const changePasswordForm = document.getElementById('changePassword');
const messagePassword = document.getElementById('messagePassword');
const logoutForm = document.getElementById('logout');
const deleteButtonDiv = document.getElementsByClassName('deleteButton')[0];


//Username change
changeUsernameForm.addEventListener('submit', (e) => {
    e.preventDefault();
    let args = obtainData(new FormData(changeUsernameForm));
    args.type = 'changeUsername';
    asyncRequest('request-settings.php', (response) => {
        messageUsername.innerHTML = response ? 'Username changed successfully!' : 'Username change failed!';
        if (response) {
            window.location.href = 'profile.php?username=' + args.newUsername;
        }
    }, args);
});

//Bio change
changeBioForm.addEventListener('submit', (e) => {
    e.preventDefault();
    let args = obtainData(new FormData(changeBioForm));
    args.type = 'changeBio';
    asyncRequest('request-settings.php', (response) => {
        messageBio.innerHTML = response ? 'Bio changed successfully!' : 'Bio change failed!';
    }, args);
});

//Password change
changePasswordForm.addEventListener('submit', (e) => {
    e.preventDefault();
    let args = obtainData(new FormData(changePasswordForm));
    if (args.newPassword !== args.confirmNewPassword) {
        messagePassword.innerHTML = 'Passwords do not match!';
        return;
    }
    args.type = 'changePassword';
    asyncRequest('request-settings.php', (response) => {
        messagePassword.innerHTML = response ? 'Password changed successfully!' : 'Password change failed!';
    }, args);
});

//Logout
logoutForm.addEventListener('submit', (e) => {
    e.preventDefault();
    asyncRequest('request-settings.php', () => {
        window.location.href = 'index.php';
    }, { type: 'logout' });
});

//Delete account
document.getElementById('deleteProfile').addEventListener('click', () => {
    addConfirmButton(deleteButtonDiv, 'request-settings.php', { type: 'delete' });
});
