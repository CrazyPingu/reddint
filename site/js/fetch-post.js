import asyncRequest from "./default-ajax.js";
import { generatePost, generateElements } from './elementGenerators.js';
import addConfirmButton from './add-buttons.js';
import pushNotification from './fetch-notifications.js';
import { throttle } from "./throttle.js";

const spacePost = document.querySelector('.post-container');
const spaceComments = document.querySelector('.comments-container');
const textTag = document.getElementById('content');
const postId = spacePost.getAttribute('data-id');
const editButton = document.getElementById('editButton');
const deleteButton = document.getElementById('deleteButton');
const space = document.getElementById('editSpace');

let offset = 0;
let baseOffset = 10;


function loadComments() {
    spaceComments.innerHTML = '';
    asyncRequest('request-comments.php', (response) => {
        generateElements(response, spaceComments, 'comment');
    }, { type: 'post', postId, limit: baseOffset, offset });
}

window.onload = function () {
    // Load the post and the comments
    asyncRequest('request-posts.php', (response) => {
        spacePost.appendChild(generatePost(response));
    }, { type: 'single', postId });
    loadComments();

    if(editButton) {
        // Add the event listeners to the edit button
        editButton.addEventListener('click', (e) => {
            e.preventDefault();
            const title = document.querySelector('h2.postTitle a');
            title.contentEditable = true;
            title.classList.add('editable');

            const content = document.querySelector('p.postContent');
            content.contentEditable = true;
            content.classList.add('editable');

            if (!document.getElementById('saveButton')) {
                // Create a save button and add a listener to it
                let saveButton = Object.assign(document.createElement('button'), {id: 'saveButton', innerText: 'Save'});
                document.querySelector('article.post').appendChild(saveButton);
                saveButton.addEventListener('click', () => {
                    asyncRequest('request-posts.php', (response) => {
                        if(response) {
                            title.contentEditable = false;
                            title.classList.remove('editable');
                            content.contentEditable = false;
                            content.classList.remove('editable');
                            document.querySelector('article.post').removeChild(saveButton);
                            if (space.contains(document.getElementById('error'))) {
                                space.removeChild(document.getElementById('error'));
                            }
                        } else {
                            let errorTag = Object.assign(document.createElement('p'), {id: 'error'});
                            errorTag.append('Error during the post editing');
                            space.appendChild(errorTag);
                        }
                    }, { type: 'edit', postId, titlePost: title.innerText, contentPost: content.innerText });
                });
            }
        });
    }

    if (deleteButton) {
        // Add the event listeners to the delete button
        deleteButton.addEventListener('click', (e) => {
            addConfirmButton(space, 'request-posts.php', {type: 'delete', postId});
        });
    }
}

document.getElementById('form-comment').addEventListener('submit', (e) => {
    e.preventDefault(); // Prevent the form from submitting
    asyncRequest('request-comments.php', (response) => {
        if (response) {
            loadComments();
            pushNotification(document.querySelector('div.authorPost a').innerText, 'added a comment to your', postId);
            textTag.value = '';
        }else{
            let errorTag = document.createElement('p');
            errorTag.append('Error during the comment creation');
            spaceComments.insertAdjacentHTML('afterbegin', errorTag.innerHTML);
        }
    }, { type: 'addComment', postId, commentContent: textTag.value });
});

window.onscroll = function () {
    if (window.innerHeight + window.pageYOffset >= document.body.offsetHeight) {
        throttle(() => {
            offset += baseOffset;
            loadComments();
        }, 1000);
    }
}