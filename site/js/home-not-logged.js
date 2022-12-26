import asyncCall from "./default-ajax.js";

//the buttons need to redirect to the login page
const buttons = document.querySelectorAll('.buttons');
buttons.forEach((button) => button.addEventListener('click', () => window.location.href = './login.php'));

//the div where the posts will be shown
const spacePosts = document.querySelector('.space');

//show initial posts
window.onload = function() {
    asyncCall('random-posts.php', (response) => {
        spacePosts.innerHTML = response;
    });
}

//added listener to show new posts when reached the bottom
spacePosts.addEventListener('scroll', () => {
    if(spacePosts.scrollTop >= (spacePosts.scrollHeight - spacePosts.offsetHeight)) {
        asyncCall('random-posts.php', (response) => {
            spacePosts.innerHTML += response;
        });
    }
});
