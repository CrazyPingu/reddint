import asyncRequest from "./default-ajax.js";
import generatePostHTML from './base-posts.js';

//the buttons
const buttonUsers = document.querySelector('#buttonPostUsers');
const buttonCommunities = document.querySelector('#buttonPostCommunities');

//the div where the posts will be shown
const space = document.querySelector('.space');

//event listeners on click and scroll
let lastSelected = '';
const baseOffset = 10;
let offset = 0;
let page = '';

buttonUsers.addEventListener('click', () => {
    if (lastSelected === 'users') return;
    asyncRequest('home-users-posts.php', (response) => {
        space.innerHTML = generatePostHTML(JSON.parse(response));
    }, {offset: 0});
    offset = baseOffset;
    lastSelected = 'users';
});

buttonCommunities.addEventListener('click', () => {
    if (lastSelected === 'communities') return;
    asyncRequest('home-communities-posts.php', (response) => {
        space.innerHTML = generatePostHTML(JSON.parse(response));
    }, {offset: 0});
    offset = baseOffset;
    lastSelected = 'communities';
});

space.addEventListener('scroll', () => {
    if (space.scrollTop >= (space.scrollHeight - space.offsetHeight)) {
        lastSelected === 'users' ? page='home-users-posts.php' : page='home-communities-posts.php';
        asyncRequest(page, (response) => {
            space.innerHTML += generatePostHTML(JSON.parse(response));
        }, {offset: offset});
    }
    offset += baseOffset;
});

window.onload = () => {
    buttonUsers.click();
}
