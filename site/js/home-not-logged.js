import asyncCall from "./default-ajax.js";

//the buttons need to redirect to the login page
const buttons = document.querySelectorAll('.buttons');
buttons.forEach((button) => button.addEventListener('click', () => window.location.href = './login.php'));
//show initial posts
window.onload = function() {
    asyncCall('random-posts.php', (response) => {
        spacePosts.innerHTML = response;
    });
}
