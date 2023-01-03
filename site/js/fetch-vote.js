import asyncRequest from './default-ajax.js';

function changeImages(vote, upvoteImg, downvoteImg) {
    upvoteImg.src = vote > 0 ? './res/upvote.svg' : './res/up-no-vote.svg';
    downvoteImg.src = vote < 0 ? './res/downvote.svg' : './res/down-no-vote.svg';
    if (vote == 0) {
        upvoteImg.src = './res/up-no-vote.svg';
        downvoteImg.src = './res/down-no-vote.svg';
    }
}

function setVote(vote, id, type, upvoteImg, downvoteImg, score) {
    asyncRequest('request-vote.php', (response) => {
        if (response === false)
            return window.location.href = './login.php';

        score.innerText = response;
        getVote(id, type, upvoteImg, downvoteImg);
    }, { vote, id, type });
}

function getVote(id, type, upvoteImg, downvoteImg) {
    asyncRequest('request-vote.php', (vote) => {
        if (upvoteImg == null || downvoteImg == null || vote === false)
            return;

        changeImages(vote, upvoteImg, downvoteImg);
    }, { id, type });
}

export { setVote, getVote };