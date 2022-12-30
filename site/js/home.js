import asyncRequest from "./default-ajax.js";
import {generatePost} from "./elementGenerators.js";

const postsDiv = document.getElementById('postsSpace');
let selectedButton = null;
let offset = 0;

function renderPosts(type) {
    asyncRequest(`posts.php`, (response) => {
        for (let postData of response) {
            postsDiv.appendChild(generatePost(postData));
        }
    }, {type, offset, limit: 10});
}

document.querySelectorAll('#communitiesPosts, #usersPosts').forEach(button => {
    button.addEventListener('click', () => {
        postsDiv.innerHTML = '';
        offset = 0;
        selectedButton = button;
        renderPosts(selectedButton.id == 'communitiesPosts' ? 'communities' : 'users');
    });
});

postsDiv.addEventListener('scroll', () => {
    // TODO: verify this actually works, find a better way to do this
    const endOfPage = postsDiv.scrollTop + postsDiv.clientHeight >= postsDiv.scrollHeight;
    if (endOfPage) {
        offset += 10;
        renderPosts(selectedButton.id == 'communitiesPosts' ? 'communities' : 'users');
    }
});

window.onload = function () {
    document.getElementById('communitiesPosts').click();
};