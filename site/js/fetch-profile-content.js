import loadInteractions from "./default-ajax.js";

const buttonPost = document.getElementById('buttonPost');
const buttonComment = document.getElementById('buttonComment');
const space = document.getElementsByClassName('spacePostComment')[0];

buttonPost.addEventListener('click', () => {
    space.innerHTML += loadInteractions('profile-post.php');
});

buttonComment.addEventListener('click', () => {
    space.innerHTML += loadInteractions('profile-comment.php');
});