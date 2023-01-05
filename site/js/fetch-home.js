import asyncRequest from "./default-ajax.js";
import {generateElements} from "./elementGenerators.js";

const postsDiv = document.getElementById('postsSpace');
let selectedButton = null;
let offset = 0;

function renderPosts() {
    asyncRequest(`request-posts.php`, (response) => {
            postsDiv.innerHTML = '';
            if (response.length == 0) {
                const noPosts = Object.assign(document.createElement('p'), {className: 'no-result', innerText: 'No posts to show'});
                postsDiv.appendChild(noPosts);
                return;
            }
            generateElements(response, postsDiv, 'post');
    }, {type: selectedButton.id == 'communitiesPosts' ? 'communities' : 'users',
        offset,
        limit: 10});
}

document.querySelectorAll('#communitiesPosts, #usersPosts').forEach(button => {
    button.addEventListener('click', () => {
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