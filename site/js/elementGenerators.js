import toggleFollow from "./fetch-follow.js";
import { getVote, setVote } from "./fetch-vote.js";

export function generatePost(postData) {
    // Outer post div
    const post = Object.assign(document.createElement("article"), {className: 'post', id: postData.id});

    // topPart div containing community, author and date
    const topPart = Object.assign(document.createElement("div"), {className: 'topPartPost'});
    const communityAuthor = Object.assign(document.createElement("div"), {className: 'communityAuthor'});
    const communityLink = Object.assign(document.createElement("a"), {href: `./community.php?community=${encodeURIComponent(postData.community)}`,innerText: postData.community});
    const authorLink = Object.assign(document.createElement("a"), {href: `./profile.php?username=${encodeURIComponent(postData.author)}`,innerText: postData.author});
    const date = Object.assign(document.createElement("p"), {innerText: postData.creation_date});

    // Post title and content
    const postTitle = Object.assign(document.createElement("h2"), {className: 'postTitle'});
    const postLink = Object.assign(document.createElement("a"), {href: `./post.php?postId=${encodeURIComponent(postData.id)}`,innerText: postData.title});
    const postContent = Object.assign(document.createElement("p"), {className: 'postContent',innerText: postData.content});
    const botPart = Object.assign(document.createElement("div"), {className: 'botPart'});

    // botPart Div containing vote buttons, score and comments button
    const vote = Object.assign(document.createElement("div"), {className: 'vote'});
    const upvote = Object.assign(document.createElement("button"), {className: 'upvote',value: 'upvote'});
    const upvoteImg = Object.assign(document.createElement("img"), {className: 'svg',src: './res/up-no-vote.svg',alt: 'upvote'});
    const downvote = Object.assign(document.createElement("button"), {className: 'downvote',value: 'downvote'});
    const downvoteImg = Object.assign(document.createElement("img"), {className: 'svg',src: './res/down-no-vote.svg',alt: 'downvote'});
    const score = Object.assign(document.createElement("p"), {className: 'score',innerText: postData.vote});

    // comments Div containing comment button
    const comments = Object.assign(document.createElement("a"), {className: 'comments', href: `./post.php?postId=${encodeURIComponent(postData.id)}`});
    const commentsImg = Object.assign(document.createElement("img"), {className: 'svg',src: './res/comments.svg',alt: 'comments'});
    const numComments = Object.assign(document.createElement("p"), {innerText: postData.comments + ' comments'});

    // Add event listeners to vote buttons
    upvote.addEventListener('click', () => setVote(1, postData.id, 'post', upvoteImg, downvoteImg, score));
    downvote.addEventListener('click', () => setVote(-1, postData.id, 'post', upvoteImg, downvoteImg, score));

    // Receive vote from server and change image accordingly
    getVote(postData.id, 'post', upvoteImg, downvoteImg);

    // Append topPart, post, botPart to post
    communityAuthor.appendChild(communityLink);
    communityAuthor.appendChild(authorLink);
    topPart.appendChild(communityAuthor);
    topPart.appendChild(date);
    postTitle.appendChild(postLink);
    post.appendChild(topPart);
    post.appendChild(postTitle);
    post.appendChild(postContent);
    upvote.appendChild(upvoteImg);
    downvote.appendChild(downvoteImg);
    vote.appendChild(upvote);
    vote.appendChild(score);
    vote.appendChild(downvote);
    botPart.appendChild(vote);
    comments.appendChild(commentsImg);
    comments.appendChild(numComments);
    botPart.appendChild(comments);
    post.appendChild(botPart);

    return post;
}

export function generateComment(commentData) {
    // Outer comment div
    const comment = Object.assign(document.createElement("div"), {className: 'comment', id: commentData.id});

    // div containing author and date
    const authorDate = Object.assign(document.createElement("div"), {className: 'commentAuthorDate'});
    const authorLink = Object.assign(document.createElement("a"), {href: `./profile.php?username=${encodeURIComponent(commentData.author)}`,innerText: commentData.author});
    const date = Object.assign(document.createElement("p"), {innerText: commentData.creation_date});

    // comment content
    const commentContent = Object.assign(document.createElement("p"), {className: 'commentContent',innerText: commentData.content});

    // vote div containing vote buttons and score
    const vote = Object.assign(document.createElement("div"), {className: 'vote'});
    const upvote = Object.assign(document.createElement("button"), {className: 'upvote',value: 'upvote'});
    const upvoteImg = Object.assign(document.createElement("img"), {className: 'svg',src: './res/up-no-vote.svg',alt: 'upvote'});
    const downvote = Object.assign(document.createElement("button"), {className: 'downvote',value: 'downvote'});
    const downvoteImg = Object.assign(document.createElement("img"), {className: 'svg',src: './res/down-no-vote.svg',alt: 'downvote'});
    const score = Object.assign(document.createElement("p"), {className: 'score',innerText: commentData.vote});

    // Add event listeners to vote buttons
    upvote.addEventListener('click', () => setVote(1, commentData.id, 'comment', upvoteImg, downvoteImg, score));
    downvote.addEventListener('click', () => setVote(-1, commentData.id, 'comment', upvoteImg, downvoteImg, score));

    // Receive vote from server and change image accordingly
    getVote(commentData.id, 'comment', upvoteImg, downvoteImg);

    // Append elements to comment div
    authorDate.appendChild(authorLink);
    authorDate.appendChild(date);
    comment.appendChild(authorDate);
    comment.appendChild(commentContent);
    upvote.appendChild(upvoteImg);
    downvote.appendChild(downvoteImg);
    vote.appendChild(upvote);
    vote.appendChild(score);
    vote.appendChild(downvote);
    comment.appendChild(vote);

    return comment;
}

export function generateFollow(followData) {
    // Outer follow div
    const follow = Object.assign(document.createElement("div"), {className: 'follow', id: followData.id});

    // username
    const username = Object.assign(document.createElement("a"), {href: `./profile.php?username=${encodeURIComponent(followData.username)}`,innerText: followData.username});
    follow.appendChild(username);

    // follow button
    const isFollowing = followData.following;
    const followButton = Object.assign(document.createElement("button"), {className: isFollowing ? 'unfollow':'follow', innerText: isFollowing ? 'Unfollow':'Follow'});
    toggleFollow(followButton, followData.username);
    follow.appendChild(followButton);

    return follow;
}

export function generateNotification(notificationData) {
    // Outer notification div
    const notification = Object.assign(document.createElement("div"), {className: 'notification' + (notificationData.seen ? ' seen':'')});
    // notification content
    const notificationContent = Object.assign(document.createElement("p"), {className: 'notificationContent',innerText: notificationData.content});
    // date
    const date = Object.assign(document.createElement("p"), {className: 'notificationDate',innerText: notificationData.date});

    // Append elements to notification div
    notification.appendChild(notificationContent);
    notification.appendChild(date);

    return notification;
}

export function generateCommunity(communityData) {
    // Outer community div
    const community = Object.assign(document.createElement("article"), {className: 'community', id: communityData.id});

    // community name
    const communityName = Object.assign(document.createElement("h2"), {className: 'communityName'});
    // community link
    const communityLink = Object.assign(document.createElement("a"), {href: `./community.php?community=${encodeURIComponent(communityData.name)}`,innerText: communityData.name});
    // community description
    const communityDescription = Object.assign(document.createElement("p"), {className: 'communityDescription',innerText: communityData.description});
    // community members
    const communityMembers = Object.assign(document.createElement("p"), {className: 'communityMembers',innerText: 'Members: '+communityData.participating});

    // Append elements to community div
    communityName.appendChild(communityLink);
    community.appendChild(communityName);
    community.appendChild(communityDescription);
    community.appendChild(communityMembers);

    return community;
}

export function generateElements(response, container, type) {
    const funcName = 'generate'+type.charAt(0).toUpperCase()+type.slice(1);
    const func = eval(funcName);
    for (const element of response) {
        container.appendChild(func(element));
    }
}