import asyncRequest from "./default-ajax.js";
import { generatePost, generateElements } from './elementGenerators.js';
import addConfirmButton from './add-buttons.js';

const spacePost = document.querySelector('.post-container');
const spaceComments = document.querySelector('.comments-container');
const formTag = document.getElementById('form-comment');
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
    }, { type: 'post', postId, limit: baseOffset });
}

window.onload = function () {
    asyncRequest('request-posts.php', (response) => {
        spacePost.appendChild(generatePost(response));
    }, { type: 'single', postId });
    loadComments();


    if(editButton) {
        editButton.addEventListener('click', (e) => {
            e.preventDefault();
            const title = document.querySelector('h2.postTitle a');
            title.contentEditable = true;
            title.classList.add('editable');

            const content = document.querySelector('p.postContent');
            content.contentEditable = true;
            content.classList.add('editable');

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
                    } else {
                        let errorTag = document.createElement('p');
                        errorTag.append('Error during the post editing');
                        space.appendChild(errorTag);
                    }
                }, { type: 'edit', postId, titlePost: title.innerText, contentPost: content.innerText });
            });
        });
    }

    if (deleteButton) {
        deleteButton.addEventListener('click', (e) => {
            addConfirmButton(space, 'request-posts.php', {type: 'delete', postId});
        });
    }
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
