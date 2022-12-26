import asyncCall from "./default-ajax.js";

window.onload = () => {
    buttonUsers.click();
}

//the buttons
const buttonUsers = document.querySelector('#buttonPostUsers');
const buttonCommunities = document.querySelector('#buttonPostCommunities');

//the div where the posts will be shown
const spacePosts = document.querySelector('.space');

//event listeners on click and scroll
let lastSelected = '';
const baseOffset = 10;
let offset = 0;
let page = '';

buttonUsers.addEventListener('click', () => {
    if (lastSelected === 'users') return;
    asyncCall('home-users-posts.php', (response) => {
        spacePosts.innerHTML = response;
    }, 'offset=0')
    offset = baseOffset;
    lastSelected = 'users';
});

buttonCommunities.addEventListener('click', () => {
    if (lastSelected === 'communities') return;
        asyncCall('home-communities-posts.php', (response) => {
            spacePosts.innerHTML = response;
        }, 'offset=0')
    offset = baseOffset;
    lastSelected = 'communities';
});

spacePosts.addEventListener('scroll', () => {
    if (spacePosts.scrollTop >= (spacePosts.scrollHeight - spacePosts.offsetHeight)) {
        lastSelected === 'users' ? page='home-users-posts.php' : page='home-communities-posts.php';
        asyncCall(page, (response) => {
            spacePosts.innerHTML += response;
        }, 'offset=' + offset)
    }
    offset += baseOffset;
});

