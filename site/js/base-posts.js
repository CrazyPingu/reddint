function generatePostHTML(data) {
    let html = '';
    data.forEach(element => {
        html += `
            <div class="post" id="${element['postId']}">
                <div class="topPartPost">
                    <div class="communityAuthorLine">
                        <a href="./community.php?community=`+ encodeURIComponent(element['community']) + `">${element['community']}</a>
                        <a href="./profile.php?username=`+ encodeURIComponent(element['username']) + `">${element['username']}</a>
                    </div>
                    <p>${element['creationDate']}</p>
                </div>
                <a class="postTitle" href="./post.php?postId=`+ encodeURIComponent(element['postId']) + `">${element['title']}</a>
                <p class="postText">${element['content']}</p>
                <div class="vote">
        `;

        html += element['postVote'] === 1 ? `<button class="upvote voted" value="upvote"></button>` : `<button class="upvote" value="upvote"></button>`;

        html += `<p class="score">${element['numVotes']}</p>`;

        html += element['postVote'] === -1 ? `<button class="downvote voted" value="downvote"></button>` : `<button class="downvote" value="downvote"></button>`;

        html += `
                    <a class="comment" href="./post.php?postId=`+ encodeURIComponent(element['postId']) + `">comments</a>
                    <p class="numComments">${element['numComments']}</p>
                </div>
            </div>
        `;
    });
    return html;
}

export default generatePostHTML;
