import asyncRequest from "./default-ajax.js";
import generatePostHTML from './base-posts.js';

//the div where the posts will be shown
const space = document.querySelector('.space');

//show initial posts
window.onload = function() {
    asyncRequest('home-random-posts.php', (response) => {
        space.innerHTML = generatePostHTML(response);
    });
}

//added listener to show new posts when reached the bottom
space.addEventListener('scroll', () => {
    if(space.scrollTop >= (space.scrollHeight - space.offsetHeight)) {
        asyncRequest('home-random-posts.php', (response) => {
            space.innerHTML += generatePostHTML(response);
        });
    }
});
