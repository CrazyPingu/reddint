import asyncRequest from './default-ajax.js';

const formTag = document.getElementById('login-form');
const responseTag = document.getElementById('response');

formTag.addEventListener('submit', (e) => {
    e.preventDefault(); // Prevent the form from submitting

    // Format the form data into a JSON object
    let formData = new FormData(formTag);
    let args = {};
    for (let [key, value] of formData.entries()) {
        args[key] = value;
    }

    // Send the request to the server, redirect to index.php if successful
    asyncRequest('request-login.php', (response) => {
        if(response) {
            responseTag.innerHTML = "Login successful, redirecting to home page ";

            // Create a link to redirect to index.php
            let redirect = document.createElement('a');
            redirect.href = "index.php";
            redirect.innerHTML = "Click here if you are not redirected";
            responseTag.appendChild(redirect);
            responseTag.appendChild(document.createElement('br'));
            window.location.href = "index.php";
        } else {
            responseTag.innerHTML = "Failed to login, the email or the password may be incorrect";
        }
    }, args);
});