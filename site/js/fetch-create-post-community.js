import asyncRequest from "./default-ajax.js";
import obtainData from "./form-data.js";

// Get the submit buttons of the forms and adds to them an event listener
document.querySelectorAll('#createPost, #createCommunity').forEach((form) => {
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const data = obtainData(new FormData(form));
        data.type = 'create';
        let fileName = form.id == 'createPost' ? 'request-posts.php' : 'request-community.php';
        asyncRequest(fileName, (response) => {
            // If a response tag is already present, removes it
            if (form.querySelector('#response')) {
                form.removeChild(form.querySelector('#response'));
            }

            // Create and append a p tag to write the response
            let responseTag = Object.assign(document.createElement('p'), {id: 'response'});
            if(response) {
                responseTag.innerText = 'Successfully created! ';
                if (form.id == 'createCommunity') {
                    responseTag.appendChild(Object.assign(document.createElement('a'), {href: 'community.php?community='+data.nameCommunity, innerText: 'Go to the community'}));
                }
            } else {
                responseTag.innerText = 'Error, not created!';
            }
            form.appendChild(responseTag);
        }, data);
    });
});