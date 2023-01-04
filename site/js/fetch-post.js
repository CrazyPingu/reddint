import asyncRequest from "./default-ajax.js";
import { generatePost, generateElements } from './elementGenerators.js';

const spacePost = document.querySelector('.post-container');
const spaceComments = document.querySelector('.comments-container');
const formTag = document.getElementById('form-comment');
const textTag = document.getElementById('content');
const postId = spacePost.getAttribute('data-id');

let offset = 0;
let baseOffset = 10;


function loadComments() {
    spaceComments.innerHTML = '';
    asyncRequest('request-comments.php', (response) => {
        generateElements(response, spaceComments, 'comment');
    }, { type: 'post', postId, limit: baseOffset });
}

window.onload = function () {
    asyncRequest('request-posts.php', (response) => {
        spacePost.appendChild(generatePost(response));
    }, { type: 'single', postId });
    loadComments();
}

formTag.addEventListener('submit', (e) => {
    e.preventDefault(); // Prevent the form from submitting
    asyncRequest('request-comments.php', (response) => {
        if (response) {
            loadComments();
            textTag.value = '';
        }else{
            let errorTag = document.createElement('p');
            errorTag.append('Error during the comment creation');
            spaceComments.insertAdjacentHTML('afterbegin', errorTag.innerHTML);
        }
    }, { type: 'addComment', postId, commentContent: textTag.value });
});

spaceComments.addEventListener('scroll', () => {
    if (spaceComments.scrollTop >= (spaceComments.scrollHeight - spaceComments.offsetHeight)) {
        asyncCall('request-comments.php', (response) => {
            generateElements(response, spaceComments, 'comment');
        }, { type: 'post', offset, limit: baseOffset, postId });
    }
    offset += baseOffset;
});