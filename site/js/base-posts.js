// TODO: move this to elementGenerator module, needs re-implementing

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

        html += element['postVote'] === 1 ?
            `<button class="upvote" value="upvote"><img src="./res/upvote.svg" alt="upvote"/></button>` :
            `<button class="upvote" value="upvote"><img src="./res/up-no-vote.svg" alt="up-no-vote"/></button>`;

        html += `<p class="score">${element['numVotes']}</p>`;

        html += element['postVote'] === -1 ?
        `<button class="downvote" value="downvote"><img src="./res/downvote.svg" alt="downvote"/></button>` :
        `<button class="downvote" value="downvote"><img src="./res/down-no-vote.svg" alt="down-no-vote"/></button>`;

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
