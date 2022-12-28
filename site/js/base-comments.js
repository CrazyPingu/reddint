function generateCommentHTML(data) {
    let html = '';
    data.forEach(element => {
        html += `
            <div class="commentAuthorDate" id="${element['commentId']}">
                <a href="./profile.php?username=${element['username']}">${element['username']}</a>
                <p>${element['creationDate']}</p>
            </div>
            <p class="commentText">${element['content']}</p>
            <div class="vote">`;
            if (element['commentUserVote'] == 1) {
                html += `<button class="upvote voted" value="upvote"></button>`;
            }else{
                html += `<buttonclass="upvote" value="upvote"></buttonclass=>`;
            }
            html+= `
            <p class="score">'${element['commentVote']}'</p>`;
            if (element['commentUserVote'] == -1) {
                html += `<button class="downvote voted" value="downvote"></button>`;
            }else{
                html += `<button class="downvote" value="downvote"></button>`;
            }
            html += `</div>`;
    });
    return html;
}

export default generateCommentHTML;