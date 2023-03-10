import asyncRequest from './default-ajax.js';

function changeImages(vote, upvoteImg, downvoteImg) {
    upvoteImg.src = vote > 0 ? './res/svg/upvote.svg' : './res/svg/up-no-vote.svg';
    downvoteImg.src = vote < 0 ? './res/svg/downvote.svg' : './res/svg/down-no-vote.svg';
}

function setVote(vote, id, type, upvoteImg, downvoteImg, score) {
    return asyncRequest('request-vote.php', (response) => {
        if (response === false)
            return window.location.href = './login.php';

        score.innerText = response;
        getVote(id, type, upvoteImg, downvoteImg);
    }, { vote, id, type });
}

function getVote(id, type, upvoteImg, downvoteImg) {
    return asyncRequest('request-vote.php', (vote) => {
        if (upvoteImg == null || downvoteImg == null || vote === false)
            return;

        changeImages(vote, upvoteImg, downvoteImg);
    }, { id, type });
}

export { setVote, getVote };