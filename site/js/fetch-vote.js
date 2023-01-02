import asyncRequest from './default-ajax.js';

function setVote(vote, id, type) {
    asyncRequest('request-vote.php', ()=>{}, { vote, id, type });
}

function getVote(id, type, upvoteImg, downvoteImg) {
    asyncRequest('request-vote.php', (response)=>{
        if (upvoteImg == null || downvoteImg == null) {
            return response;
        }

        const vote = parseInt(response);
        if (vote == 1) {
            upvoteImg.src = './res/upvote.svg';
            downvoteImg.src = './res/down-no-vote.svg';
        } else if (vote == -1) {
            downvoteImg.src = './res/downvote.svg';
            upvoteImg.src = './res/up-no-vote.svg';
        } else {
            upvoteImg.src = './res/up-no-vote.svg';
            downvoteImg.src = './res/down-no-vote.svg';
        };
    },{ id, type });
}

export { setVote, getVote };