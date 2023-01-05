import asyncRequest from './default-ajax.js';
import { generateElements } from './elementGenerators.js';
import toggleFollow from './fetch-follow.js';

const buttonPost = document.getElementById('buttonPost');
const buttonComment = document.getElementById('buttonComment');
const space = document.getElementsByClassName('spacePostComment')[0];
const username = document.querySelector('div.upperProfile').id;
const buttonFollow = document.getElementById('followButton');
const baseOffset = 10;
const defaultData = { type: 'user', offset: 0, username, limit: baseOffset };
let selected = '';

function incrementOffset() {
    defaultData.offset += baseOffset;
}

function render(container, args, fileName, type) {
    asyncRequest(fileName, (response) => {
        generateElements(response, container, type);
    }, args);
}

buttonPost.addEventListener('click', () => {
    defaultData.offset = 0;
    space.innerHTML = '';
    render(space, defaultData, 'request-posts.php', 'post');
    defaultData.offset = baseOffset;
    selected = 'post';
});

buttonComment.addEventListener('click', () => {
    defaultData.offset = 0;
    space.innerHTML = '';
    render(space, defaultData, 'request-comments.php', 'comment');
    defaultData.offset = baseOffset;
    selected = 'comment';
});

space.addEventListener('scroll', () => {
    if (space.scrollTop === (space.scrollHeight - space.offsetHeight)) {
        selected === 'post' ? page = 'request-posts.php' : page = 'request-comments.php';
        render(space, defaultData, page, selected);
    }
    incrementOffset();
});

if (buttonFollow) {
    toggleFollow(buttonFollow, username);
}

window.onload = () => {
    buttonPost.click();
}
