import asyncRequest from './default-ajax.js';

function setVote(vote, id, type, upvoteImg, downvoteImg, score) {
    asyncRequest('request-vote.php', (response) => {
        if (!response) {
            window.location.href = './login.php';
        }
    }, { vote, id, type });
}

function getVote(id, type, upvoteImg, downvoteImg) {
    asyncRequest('request-vote.php', (response) => {
        if (upvoteImg == null || downvoteImg == null || !response) {
            return response;
        }
        const vote = response;
        upvoteImg.src = vote > 0 ? './res/upvote.svg' : './res/up-no-vote.svg';
        downvoteImg.src = vote < 0 ? './res/downvote.svg' : './res/down-no-vote.svg';
    }, { id, type });
}

export { setVote, getVote };