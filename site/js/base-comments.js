function generateCommentHTML(data) {
    let html = '';
    data.forEach(element => {
        html += `
            <div class="commentAuthorDate" id="${element['commentId']}">
                <a name="author" id="author" href="../profile.php?username=${element['username']}">${element['username']}</a>
                <p name="date" id="date">${element['creationDate']}</p>
            </div>
            <p name="text" id="text" class="commentText">${element['content']}</p>
            <div class="vote">`;
            if (element['commentUserVote'] == 1) {
                html += `<button name="upvote" id="upvote" class="upvote voted" value="upvote" />`;
            }else{
                html += `<button name="upvote" id="upvote" class="upvote" value="upvote" />`;
            }
            html+= `
            <p name="score" id="score" class="score">'${element['commentVote']}'</p>`;
            if (element['commentUserVote'] == -1) {
                html += `<button name="downvote" id="downvote" class="downvote voted" value="downvote" />`;
            }else{
                html += `<button name="downvote" id="downvote" class="downvote" value="downvote" />`;
            }
            html += `</div>`;
    });
    return html;
}

export default generateCommentHTML;