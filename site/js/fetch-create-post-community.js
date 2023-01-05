import asyncRequest from "./default-ajax.js";
import obtainData from "./form-data.js";

const createPostForm = document.getElementById("createPost");
const createCommunityForm = document.getElementById("createCommunity");

document.querySelectorAll('#createPost, #createCommunity').forEach((form) => {
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const data = obtainData(new FormData(form));
        data.type = 'create';
        let post = form.id == 'createPost' ? 'request-posts.php' : 'request-community.php';
        asyncRequest(post, (response) => {
            let responseTag = document.createElement('p');
            if(response) {
                responseTag.append('Successfully created! ');
                responseTag.appendChild(Object.assign(document.createElement('a'), {href: 'community.php?community='+data.nameCommunity, innerText: 'Go to the community'}));
            } else {
                responseTag.append('Error, not created!');
            }
            form.appendChild(responseTag);
        }, data);
    });
});