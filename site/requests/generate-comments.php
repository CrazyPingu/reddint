<?php
require_once '../bootstrap.php';

function generateComments($comments){
    $html = '';
    foreach ($comments as $comment) {
        $html .= '
            <div class="commentAuthorDate">
                <p name="author" id="author">' . $_SESSION['username'] . '</p>
                <p name="date" id="date">' . $comment['creation_date'] . '</p>
            </div>
            <p name="text" id="text" class="commentText">' . $comment['content'] . '</p>
            <div class="vote">
                <button name="upvote" id="upvote" class="upvote" value="upvote" />
                <p name="score" id="score" class="score">' . $comment['score'] . '</p>
                <button name="downvote" id="downvote" class="downvote" value="downvote" />
            </div>
        ';
    }
    return $html;
}