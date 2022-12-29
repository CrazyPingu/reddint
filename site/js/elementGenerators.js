//TODO: actually generate elements

function generatePost(jsonPostData) {
    var post = document.createElement("div");
    post.className = "post";
    post.innerHTML = jsonPostData.content;
    return post;
}

function generateComment(jsonCommentData) {
    var comment = document.createElement("div");
    comment.className = "comment";
    comment.innerHTML = jsonCommentData.content;
    return comment;
}

export { generatePost, generateComment };