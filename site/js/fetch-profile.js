import asyncRequest from './default-ajax.js';
import { generateElements } from './elementGenerators.js';
import toggleFollow from './fetch-follow.js';
import { throttle } from './throttle.js';

const buttonPost = document.getElementById('buttonPost');
const buttonComment = document.getElementById('buttonComment');
const space = document.getElementsByClassName('spacePostComment')[0];
const username = document.querySelector('div.upperProfile').id;
const buttonFollow = document.getElementById('followButton');
const baseOffset = 10;
const defaultData = { type: 'user', offset: 0, username, limit: baseOffset };
let selected = '';

// Render the elements in the page
function render(container, args, fileName, type) {
    asyncRequest(fileName, (response) => {
        if (response.length == 0) {
            let noPostsComments = document.getElementById('no-more-posts-comments');
            if (noPostsComments) return;
            noPostsComments = Object.assign(document.createElement('p'), {id: 'no-more-posts-comments', className: 'no-result', innerText: `No more ${type}s to show`});
            container.appendChild(noPostsComments);
            return;
        }
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

if (buttonFollow) {
    toggleFollow(buttonFollow, username);
}

window.onload = () => {
    buttonPost.click();
}

window.onscroll = function () {
    if (window.innerHeight + window.pageYOffset >= document.body.offsetHeight) {
        throttle(() => {
            const page = selected == 'post' ? 'request-posts.php' : 'request-comments.php';
            render(space, defaultData, page, selected);
            defaultData.offset += baseOffset;
        }, 1000);
    }
}