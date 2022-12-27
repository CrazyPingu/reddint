<?php

function generateComments($comments, $dbh){
    $html = '';
    foreach ($comments as $comment) {
        $username = $dbh->getUser($comment['author'])['username'];
        $html .= '
            <div class="commentAuthorDate" id="' . $comment['id'] . '>
                <a name="author" id="author" href="../profile.php?username='. $username.'">' . $username . '</a>
                <p name="date" id="date">' . $comment['creation_date'] . '</p>
            </div>
            <p name="text" id="text" class="commentText">' . $comment['content'] . '</p>
            <div class="vote">
                <button name="upvote" id="upvote" class="upvote" value="upvote" />
                <p name="score" id="score" class="score">' . $dbh->getCommentVote($comment['id']) . '</p>
                <button name="downvote" id="downvote" class="downvote" value="downvote" />
            </div>
        ';
    }
    return $html;
}

?>