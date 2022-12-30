import asyncRequest from "./default-ajax.js";
import { generatePost, generateElements } from './elementGenerators.js';

const spacePost = document.querySelector('.post');
const spaceComments = document.querySelector('.comments');
const postId = spacePost.id;
const formTag = document.getElementById('form-comment');
const textTag = document.getElementById('content');

let offset = 0;
let baseOffset = 10;

window.onload = function () {
    asyncRequest('request-posts.php', (response) => {
        spacePost.appendChild(generatePost(response));
    }, { type: 'single', postId });

    asyncRequest('request-comments.php', (response) => {
        generateElements(response, spaceComments, 'comment');
    }, { type: 'post', postId, limit: baseOffset });
}

formTag.addEventListener('submit', (e) => {
    e.preventDefault(); // Prevent the form from submitting

    asyncRequest('request-comments.php', (response) => {
        if (response) {
            generateElements(response, spaceComments, 'comment');
        }
    }, { type: 'addComment', postId, commentContent: textTag.innerText });
});

spaceComments.addEventListener('scroll', () => {
    if (spaceComments.scrollTop >= (spaceComments.scrollHeight - spaceComments.offsetHeight)) {
        asyncCall('request-comments.php', (response) => {
            generateElements(response, spaceComments, 'comment');
        }, { type: 'post', offset, limit: baseOffset, postId });
    }
    offset += baseOffset;
});