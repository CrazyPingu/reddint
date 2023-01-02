function generatePost(postData) {
    // Outer post div
    const post = Object.assign(document.createElement("div"), {className: 'post', id: postData.id});

    // topPart div containing community, author and date
    const topPart = Object.assign(document.createElement("div"), {className: 'topPartPost'});
    // community and author div
    const communityAuthorLine = Object.assign(document.createElement("div"), {className: 'communityAuthorLine'});
    const communityLink = Object.assign(document.createElement("a"), {href: `./community.php?community=${encodeURIComponent(postData.community)}`,innerText: postData.community});
    const authorLink = Object.assign(document.createElement("a"), {href: `./profile.php?username=${encodeURIComponent(postData.author)}`,innerText: postData.author});
    communityAuthorLine.appendChild(communityLink);
    communityAuthorLine.appendChild(authorLink);
    topPart.appendChild(communityAuthorLine);
    // Add date to topPart
    const date = Object.assign(document.createElement("p"), {innerText: postData.creation_date});
    topPart.appendChild(date);
    // Add topPart to post
    post.appendChild(topPart);

    // Post title
    const postTitle = Object.assign(document.createElement("a"), {className: 'postTitle',href: `./post.php?postId=${encodeURIComponent(postData.id)}`,innerText: postData.title});
    post.appendChild(postTitle);
    // Post content
    const postContent = Object.assign(document.createElement("p"), {className: 'postContent',innerText: postData.content});
    post.appendChild(postContent);

    // botPart Div containing vote buttons, score and comments button
    const botPart = Object.assign(document.createElement("div"), {className: 'botPart'});

    // vote Div containing vote buttons and score
    const vote = Object.assign(document.createElement("div"), {className: 'vote'});

    const upvote = Object.assign(document.createElement("button"), {className: 'upvote',value: 'upvote'});
    const upvoteImg = Object.assign(document.createElement("img"), {className: 'svg',src: './res/up-no-vote.svg',alt: 'upvote'});
    upvote.appendChild(upvoteImg);

    const downvote = Object.assign(document.createElement("button"), {className: 'downvote',value: 'downvote'});
    const downvoteImg = Object.assign(document.createElement("img"), {className: 'svg',src: './res/down-no-vote.svg',alt: 'downvote'});
    downvote.appendChild(downvoteImg);

    const score = Object.assign(document.createElement("p"), {className: 'score',innerText: postData.vote});

    // add vote buttons and score to vote div, then add vote div to botPart
    vote.appendChild(upvote);
    vote.appendChild(score);
    vote.appendChild(downvote);
    botPart.appendChild(vote);

    // comments Div containing comment button
    const comments = Object.assign(document.createElement("a"), {className: 'comments', href: `./post.php?postId=${encodeURIComponent(postData.id)}`});
    const commentsImg = Object.assign(document.createElement("img"), {className: 'svg',src: './res/comments.svg',alt: 'comments'});
    const numComments = Object.assign(document.createElement("p"), {innerText: postData.comments + ' comments'});
    // add comment button to comments div, then add comments div to botPart
    comments.appendChild(commentsImg);
    comments.appendChild(numComments);
    botPart.appendChild(comments);

    // add botPart to post
    post.appendChild(botPart);

    return post;
}

function generateElements(response, container, type) {
    for (let element of response) {
        container.appendChild(type == 'post' ? generatePost(element): generateComment(element));
    }
}

function generateComment(commentData) {
    // Outer comment div
    const comment = Object.assign(document.createElement("div"), {className: 'comment', id: commentData.id});

    // div containing author and date
    const authorDate = Object.assign(document.createElement("div"), {className: 'commentAuthorDate'});
    // author
    const authorLink = Object.assign(document.createElement("a"), {href: `./profile.php?username=${encodeURIComponent(commentData.author)}`,innerText: commentData.author});
    // date
    const date = Object.assign(document.createElement("p"), {innerText: commentData.creation_date});
    authorDate.appendChild(authorLink);
    authorDate.appendChild(date);
    // add author-date to comment
    comment.appendChild(authorDate);

    // comment content
    const commentContent = Object.assign(document.createElement("p"), {className: 'commentContent',innerText: commentData.content});
    comment.appendChild(commentContent);

    // vote div containing vote buttons and score
    const vote = Object.assign(document.createElement("div"), {className: 'vote'});

    const upvote = Object.assign(document.createElement("button"), {className: 'upvote',value: 'upvote'});
    const upvoteImg = Object.assign(document.createElement("img"), {className: 'svg',src: './res/up-no-vote.svg',alt: 'upvote'});
    upvote.appendChild(upvoteImg);

    const downvote = Object.assign(document.createElement("button"), {className: 'downvote',value: 'downvote'});
    const downvoteImg = Object.assign(document.createElement("img"), {className: 'svg',src: './res/down-no-vote.svg',alt: 'downvote'});
    downvote.appendChild(downvoteImg);

    const score = Object.assign(document.createElement("p"), {className: 'score',innerText: commentData.vote});

    // add vote buttons and score to vote div, then add vote div to comment
    vote.appendChild(upvote);
    vote.appendChild(score);
    vote.appendChild(downvote);

    comment.appendChild(vote);

    return comment;
}

export { generatePost, generateElements, generateComment };