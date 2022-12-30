import asyncRequest from './default-ajax.js';
import { generateElements } from './elementGenerators.js';
import toggleFollow from './fetch-follow.js';

const buttonPost = document.getElementById('buttonPost');
const buttonComment = document.getElementById('buttonComment');
const space = document.getElementsByClassName('spacePostComment')[0];
const username = document.querySelector('div.upperProfile').id;
const buttonFollow = document.getElementById('follow');
let offset = 0;
const baseOffset = 10;
const defaultData = { type: 'user', offset, username, limit: baseOffset };
let selected = '';

function incrementOffset() {
    offset += baseOffset;
}

buttonPost.addEventListener('click', () => {
    offset = 0;
    space.innerHTML = '';
    asyncRequest('request-posts.php', (response) => {
        generateElements(response, space, 'post');
    }, defaultData);
    offset = baseOffset;
    selected = 'post';
});

buttonComment.addEventListener('click', () => {
    offset = 0;
    space.innerHTML = '';
    asyncRequest('request-comments.php', (response) => {
        generateElements(response, space, 'comment');
    }, defaultData);
    offset = baseOffset;
    selected = 'comment';
});

space.addEventListener('scroll', () => {
    if (space.scrollTop === (space.scrollHeight - space.offsetHeight)) {
        selected === 'post' ? page = 'request-posts.php' : page = 'request-comments.php';
        asyncRequest(page, (response) => {
            generateElements(response, space, selected);
        }, defaultData);
    }
    incrementOffset();
});

if (buttonFollow) {
    toggleFollow(buttonFollow, username);
}

window.onload = () => {
    buttonPost.click();
}
