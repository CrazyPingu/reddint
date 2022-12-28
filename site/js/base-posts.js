function generatePostHTML(data) {
    let html = '';
    data.forEach(element => {
        html += `
            <div class="post" id="${element['postId']}">
                <div class="topPartPost">
                    <div class="communityAuthorLine">
                        <a name="community" id="community" href="../community.php?community=${element['community']}">${element['community']}</a>
                        <a name="author" id="author" href="../profile.php?username=${element['username']}">${element['username']}</a>
                    </div>
                    <p name="date" id="date">${element['creationDate']}</p>
                </div>
                <a name="title" id="title" class="postTitle" href="../post.php?postId=${element['postId']}">${element['title']}</a>
                <p name="text" id="text" class="postText">${element['content']}</p>
                <div class="vote">
        `;

        html += element['postVote'] === 1 ? `<button name="upvote" id="upvote" class="upvote voted" value="upvote"></button>` : `<button name="upvote" id="upvote" class="upvote" value="upvote"></button>`;

        html += `<p name="score" id="score" class="score">${element['numVotes']}</p>`;

        html += element['postVote'] === -1 ? `<button name="downvote" id="downvote" class="downvote voted" value="downvote"></button>` : `<button name="downvote" id="downvote" class="downvote" value="downvote"></button>`;

        html += `
                    <a name="comment" id="comment" class="comment" value="comment" href="../post.php?postId=${element['postId']}">comments</a>
                    <p name="numComments" id="numComments" class="numComments">${element['numComments']}</p>
                </div>
            </div>
        `;
    });
    return html;
}

export default generatePostHTML;
