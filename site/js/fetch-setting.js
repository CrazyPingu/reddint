import asyncRequest from './default-ajax.js';
import obtainData from './form-data.js';

const changeUsernameForm = document.getElementById('changeUsername');
const changePasswordForm = document.getElementById('changePassword');
const changeBioForm = document.getElementById('changeBio');

changeUsernameForm.addEventListener('submit', (e) => {
    e.preventDefault(); // Prevent the form from submitting

    // Format the form data into a JSON object
    let args = obtainData(new FormData(changeUsernameForm));
    args.type = 'changeUsername';

    asyncRequest('request-settings.php', (response) => {}, args);
});