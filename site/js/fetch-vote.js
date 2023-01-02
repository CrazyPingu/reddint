import asyncRequest from './default-ajax.js';

function setVote(vote, id, type) {
    asyncRequest('request-vote.php', args = { vote, id, type });
}

function getVote(id, type) {
    asyncRequest('request-vote.php', args = { id, type });
}

export { setVote, getVote };