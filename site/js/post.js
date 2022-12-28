import asyncCall from "./default-ajax.js";
import generatePostHTML from './base-posts.js';
import generateCommentHTML from './base-comments.js';

const spacePost = document.querySelector('.post');
const spaceComments = document.querySelector('.comments');
const postId = spacePost.id;

let offset = 0;
let baseOffset = 10;

window.onload = function() {
    asyncCall('single-post.php', (response) => {
        spacePost.innerHTML = generatePostHTML(response);
    }, {postId: postId});
    asyncCall('single-post-comments.php', (response) => {
        spaceComments.innerHTML = generateCommentHTML(response);
    }, {offset: 0, postId: postId});
}

spaceComments.addEventListener('scroll', () => {
    if(spaceComments.scrollTop >= (spaceComments.scrollHeight - spaceComments.offsetHeight)) {
        asyncCall('single-post-comments.php', (response) => {
            spaceComments.innerHTML += generateCommentHTML(response);
        }, {offset: offset, postId: postId});
    }
    offset += baseOffset;
});