import asyncCall from './default-ajax.js';
import generateCommentHTML from './base-comments.js';
import generatePostHTML from './base-post.js';

const buttonPost = document.getElementById('buttonPost');
const buttonComment = document.getElementById('buttonComment');
const space = document.getElementsByClassName('spacePostComment')[0];
const baseOffset = 10;
let selected = '';
let offset = baseOffset;

function incrementOffset() {
    offset += baseOffset;
}

buttonPost.addEventListener('click', () => {
    if (selected === 'post') return;
    asyncCall('profile-post.php', (response) => {
        space.innerHTML = generatePostHTML(JSON.parse(response));
    }, 'offset=0')
    offset = baseOffset;
    selected = 'post';
});

buttonComment.addEventListener('click', () => {
    if (selected === 'comment') return;
    asyncCall('profile-comment.php', (response) => {
        space.innerHTML = generateCommentHTML(JSON.parse(response));
    }, 'offset=0')
    offset = baseOffset;
    selected = 'comment';
});

space.addEventListener('scroll', () => {
    if (space.scrollTop === (space.scrollHeight - space.offsetHeight)) {
        selected === 'post' ? page='profile-post.php' : page='profile-comment.php';
        asyncCall(page, (response) => {
            space.innerHTML += response;
        }, 'offset=' + offset)
    }
    incrementOffset();
});

window.onload = () => {
    buttonPost.click();
}
