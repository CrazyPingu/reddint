<?php

function generatePosts($posts, $dbh){
    $html = '';
    foreach ($posts as $post) {
        $html .= '
            <div class="post" id="' . $post['id'] . '>
                <div class="topPartPost">
                    <div class="communityAuthorLine">
                        <p name="community" id="community">' . $dbh->getCommunity($post['community'])['name'] . '</p>
                        <p name="author" id="author">' . $dbh->getUser($post['author'])['username'] . '</p>
                    </div>
                    <p name="date" id="date">' . $post['creation_date'] . '</p>
                </div>
                <p name="title" id="title" class="postTitle">' . $post['title'] . '</p>
                <p name="text" id="text" class="postText">' . $post['content'] . '</p>
                <div class="vote">
                    <button name="upvote" id="upvote" class="upvote" value="upvote" />
                    <p name="score" id="score" class="score">' . $dbh->getPostVote($post['id']); . '</p>
                    <button name="downvote" id="downvote" class="downvote" value="downvote" />
                    <button name="comment" id="comment" class="comment" value="comment" />
                    <p name="numComments" id="numComments" class="numComments">' . $dbh->getPostNumberOfComments($post['id']) . '</p>
                </div>
            </div>
            ';
    }
    return $html;
}
?>
