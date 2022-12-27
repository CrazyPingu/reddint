import asyncCall from "./default-ajax.js";
import generatePostHTML from './base-posts.js';

//the div where the posts will be shown
const spacePosts = document.querySelector('.space');

//show initial posts
window.onload = function() {
    asyncCall('home-random-posts.php', (response) => {
        spacePosts.innerHTML = generatePostHTML(JSON.parse(response));
    });
}

//added listener to show new posts when reached the bottom
spacePosts.addEventListener('scroll', () => {
    if(spacePosts.scrollTop >= (spacePosts.scrollHeight - spacePosts.offsetHeight)) {
        asyncCall('home-random-posts.php', (response) => {
            spacePosts.innerHTML += generatePostHTML(JSON.parse(response));
        });
    }
});
