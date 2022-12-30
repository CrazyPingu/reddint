import asyncRequest from './default-ajax.js';
import generateCommentHTML from './base-comments.js';
import generatePostHTML from './base-posts.js';

const buttonPost = document.getElementById('buttonPost');
const buttonComment = document.getElementById('buttonComment');
const space = document.getElementsByClassName('spacePostComment')[0];
const username = document.querySelector('div.upperProfile').id;
let offset = 0;
const baseOffset = 10;
const defaultData = { type: 'user', offset, username, limit: baseOffset };
let selected = '';

function incrementOffset() {
    offset += baseOffset;
}

buttonPost.addEventListener('click', () => {
    offset = 0;
    asyncRequest('request-posts.php', (response) => {
        space.innerHTML = generatePostHTML(response);
    }, defaultData);
    offset = baseOffset;
    selected = 'post';
});

buttonComment.addEventListener('click', () => {
    offset = 0;
    asyncRequest('request-comments.php', (response) => {
        space.innerHTML = generateCommentHTML(response);
    }, defaultData);
    offset = baseOffset;
    selected = 'comment';
});

space.addEventListener('scroll', () => {
    if (space.scrollTop === (space.scrollHeight - space.offsetHeight)) {
        selected === 'post' ? page = 'request-posts.php' : page = 'request-comments.php';
        asyncRequest(page, (response) => {
            space.innerHTML += selected === 'post' ? generatePostHTML(response) : generateCommentHTML(response);
        }, defaultData);
    }
    incrementOffset();
});

window.onload = () => {
    buttonPost.click();
}
