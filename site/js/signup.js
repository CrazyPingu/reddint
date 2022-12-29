// Form submission handler for signup.php
import asyncRequest from './default-ajax.js';

const formTag = document.getElementById('signup-form');
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
    asyncRequest('signup.php', (response) => {
        if (response) {
            // Print success message
            responseTag.innerHTML = "User created successfully, redirecting to home page...";

            // Create a link to redirect to index.php
            let redirect = document.createElement('a');
            redirect.href = "index.php";
            redirect.innerHTML = "Click here if you are not redirected";
            responseTag.appendChild(redirect);

            // Set a timer to redirect to index.php
            let timer = 5;
            let timerTag = document.createElement('p');
            timerTag.innerHTML = "Redirecting in " + timer + " seconds";
            responseTag.appendChild(timerTag);
            let timerInterval = setInterval(() => {
                timer--;
                timerTag.innerHTML = "Redirecting in " + timer + " seconds";
                if (timer <= 0) {
                    clearInterval(timerInterval);
                    window.location.href = "index.php";
                }
            }, 1000);
        } else {
            // Print error message
            responseTag.innerHTML = "Failed to register user, the username or email may already be in use";
        }
    }, args);
});