<?php

function generatePosts($posts, $dbh){
    $html = '';
    foreach ($posts as $post) {
        $username = $dbh->getUser($post['author'])['username'];
        $community = $dbh->getCommunity($post['community'])['name'];
        $postId = $post['id'];
        $html .= '
            <div class="post">
                <div class="topPartPost">
                    <div class="communityAuthorLine">
                        <a name="community" id="community" href="../community.php?community='. $community .'">' . $community . '</a>
                        <a name="author" id="author" href="../profile.php?username='. $username .'">' . $username . '</a>
                    </div>
                    <p name="date" id="date">' . $post['creation_date'] . '</p>
                </div>
                <a name="title" id="title" class="postTitle" href="../post.php?postId='. $postId .'">' . $post['title'] . '</a>
                <p name="text" id="text" class="postText">' . $post['content'] . '</p>
                <div class="vote">
                    <button name="upvote" id="upvote" class="upvote" value="upvote" />
                    <p name="score" id="score" class="score">' . $dbh->getPostVote($postId) . '</p>
                    <button name="downvote" id="downvote" class="downvote" value="downvote" />
                    <a name="comment" id="comment" class="comment" value="comment" href="../post.php?postId='. $postId .'"></a>
                    <p name="numComments" id="numComments" class="numComments">' . $dbh->getPostNumberOfComments($postId) . '</p>
                </div>
            </div>
            ';
    }
    return $html;
}

?>