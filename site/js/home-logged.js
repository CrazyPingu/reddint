import asyncCall from "./default-ajax.js";

window.onload = () => {
    buttonUsers.click();
}

//the buttons
const buttonUsers = document.querySelector('#buttonPostUsers');
const buttonCommunities = document.querySelector('#buttonPostCommunities');


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

