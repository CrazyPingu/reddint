import asyncRequest from './default-ajax.js';

function requestCall(id, type, imgArray, scoreRef, vote) {
    asyncRequest('request-vote.php', (response) => {
        if (response.vote == 'error') {
            let error = document.createElement('p');
            error.innerHTML = 'Something went wrong';
            upvoteButton.parentNode.appendChild(error);
        } else if (response.vote == 'no-login') {
            window.location.href = './login.php';
        } else {
            imgArray[0].src = response.vote <= 0 ? './res/up-no-vote.svg' : './res/upvote.svg';
            imgArray[1].src = response.vote >= 0 ? './res/down-no-vote.svg' : './res/downvote.svg';
            scoreRef.innerText = response.score;
        }
    }, { vote: vote, id: id, type: type });
}

function vote(button, idPostComment, type, imgArray, scoreRef) {
    button.addEventListener('click', function () {
        let vote = button.classList.contains('upvote') ? 1 : -1;
        requestCall(idPostComment, type, imgArray, scoreRef, vote);
    });
}

export default vote;