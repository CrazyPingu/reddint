const buttonPost = document.getElementById('buttonPost');
const buttonComment = document.getElementById('buttonComment');
const space = document.getElementsByClassName('spacePostComment')[0];

buttonPost.addEventListener('click', () => loadInteractions('profile-post.php'));
buttonComment.addEventListener('click', () => loadInteractions('profile-comment.php'));

function loadInteractions(fileName) {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            space.innerHTML += this.responseText;
        }
    };
    xhttp.open('POST', 'requests/'+fileName, true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send();
}