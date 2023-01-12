import asyncRequest from "./default-ajax.js";
import {generateElements} from "./elementGenerators.js";
import { throttle } from "./throttle.js";

const postsDiv = document.getElementById('postsSpace');
let selectedButton = null;
let offset = 0;

// Render the posts in the page
function renderPosts() {
    asyncRequest(`request-posts.php`, (response) => {
            if (response.length == 0) {
                let noPosts = document.getElementById('no-more-posts');
                if (noPosts) return;
                noPosts = Object.assign(document.createElement('p'), {id: 'no-more-posts', className: 'no-result', innerText: 'No more posts to show'});
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
        postsDiv.innerHTML = '';
        offset = 0;
        selectedButton = button;
        renderPosts();
    });
});

window.onload = function () {
    document.getElementById('communitiesPosts').click();
};

window.onscroll = function () {
    if (window.innerHeight + window.pageYOffset >= document.body.offsetHeight) {
        throttle(() => {
            offset += 10;
            renderPosts();
        }, 1000);
    }
};