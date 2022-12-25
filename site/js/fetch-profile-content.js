import asyncCall from "./default-ajax.js";

const buttonPost = document.getElementById('buttonPost');
const buttonComment = document.getElementById('buttonComment');
const space = document.getElementsByClassName('spacePostComment')[0];
const username = document.getElementById('username').innerText;
let selected = '';

buttonPost.addEventListener('click', () => {
    if (selected === 'post') return;
    asyncCall('profile-post.php', "username=" + username, (response) => {
        space.innerHTML = response;
    })
    selected = 'post';
});

buttonComment.addEventListener('click', () => {
    if (selected === 'comment') return;
    asyncCall('profile-comment.php', "username=" + username, (response) => {
        space.innerHTML = response;
    })
    selected = 'comment';
});