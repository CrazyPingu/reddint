import asyncRequest from "./default-ajax.js";
import {generateElement} from "./elementGenerators.js";

const postsDiv = document.getElementById('postsSpace');
let selectedButton = null;
let offset = 0;

function renderPosts() {
    asyncRequest(`request-posts.php`, (response) => {
            generateElement(response, postsDiv, 'post');
    }, {type: selectedButton.id == 'communitiesPosts' ? 'communities' : 'users',
        offset,
        limit: 10});
}

document.querySelectorAll('#communitiesPosts, #usersPosts').forEach(button => {
    button.addEventListener('click', () => {
        postsDiv.innerHTML = '';
        offset = 0;
        selectedButton = button;
        renderPosts();
    });
});

postsDiv.addEventListener('scroll', () => {
    // TODO: verify this actually works, find a better way to do this
    const endOfPage = postsDiv.scrollTop + postsDiv.clientHeight >= postsDiv.scrollHeight;
    if (endOfPage) {
        offset += 10;
        renderPosts();
    }
});

window.onload = function () {
    document.getElementById('communitiesPosts').click();
};