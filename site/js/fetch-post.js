import asyncCall from "./default-ajax.js";
import generatePostHTML from './base-posts.js';
import generateCommentHTML from './base-comments.js';

const spacePost = document.querySelector('.post');
const spaceComments = document.querySelector('.comments');
const postId = spacePost.id;
const formTag = document.getElementById('form-comment');
const textTag = document.getElementById('content');

let offset = 0;
let baseOffset = 10;

window.onload = function () {
    asyncCall('request-posts.php', (response) => {
        spacePost.innerHTML = generatePostHTML(response);
    }, { type: 'single', postId });
    asyncCall('request-comments.php', (response) => {
        spaceComments.innerHTML = generateCommentHTML(response);
    }, { type: 'post', postId, limit: baseOffset });
}

formTag.addEventListener('submit', (e) => {
    e.preventDefault(); // Prevent the form from submitting

    asyncCall('request-comments.php', (response) => {
        spaceComments.innerHTML = generateCommentHTML(response) + spaceComments.innerHTML;
    }, { type: 'addComment', postId, commentContent: textTag.innerText });
});

spaceComments.addEventListener('scroll', () => {
    if (spaceComments.scrollTop >= (spaceComments.scrollHeight - spaceComments.offsetHeight)) {
        asyncCall('request-comments.php', (response) => {
            spaceComments.innerHTML += generateCommentHTML(response);
        }, { type: 'post', offset, limit: baseOffset, postId });
    }
    offset += baseOffset;
});