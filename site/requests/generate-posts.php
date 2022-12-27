<?php

function generateJson($posts, $dbh){
    $json = array();
    foreach ($posts as $post) {
        $jsonPosts = array();
        $jsonPosts['username'] = $dbh->getUser($post['author'])['username'];
        $jsonPosts['community'] = $dbh->getCommunity($post['community'])['name'];
        $jsonPosts['creationDate'] = $post['creation_date'];
        $jsonPosts['title'] = $post['title'];
        $jsonPosts['content'] = $post['content'];
        $jsonPosts['postId'] = $post['id'];
        $jsonPosts['postVote'] = isset($_SESSION['userId']) ? $dbh->getUserPostVote($_SESSION['userId'], $post['id']) : 0;
        $jsonPosts['numComments'] = $dbh->getPostNumberOfComments($post['id']);
        $jsonPosts['numVotes'] = $dbh->getPostVote($post['id']);

        array_push($json, $jsonPosts);
    }
    return $json;
}

// function generatePosts($posts, $dbh){
//     $html = '';
//     foreach ($posts as $post) {
//         $username = $dbh->getUser($post['author'])['username'];
//         $community = $dbh->getCommunity($post['community'])['name'];
//         $postId = $post['id'];
//         $postVote = isset($_SESSION['userId']) ? $dbh->getUserPostVote($_SESSION['userId'],$postId) : 0;
//         $html .= '
//             <div class="post">
//                 <div class="topPartPost">
//                     <div class="communityAuthorLine">
//                         <a name="community" id="community" href="../community.php?community='. $community .'">' . $community . '</a>
//                         <a name="author" id="author" href="../profile.php?username='. $username .'">' . $username . '</a>
//                     </div>
//                     <p name="date" id="date">' . $post['creation_date'] . '</p>
//                 </div>
//                 <a name="title" id="title" class="postTitle" href="../post.php?postId='. $postId .'">' . $post['title'] . '</a>
//                 <p name="text" id="text" class="postText">' . $post['content'] . '</p>
//                 <div class="vote">
//                     <button name="upvote" id="upvote" class="upvote '. ($postVote == 1 ? 'voted' : '') .'" value="upvote" />
//                     <p name="score" id="score" class="score">' . $dbh->getPostVote($postId) . '</p>
//                     <button name="downvote" id="downvote" class="downvote '. ($postVote == -1 ? 'voted' : '') .'" value="downvote" />
//                     <a name="comment" id="comment" class="comment" value="comment" href="../post.php?postId='. $postId .'"></a>
//                     <p name="numComments" id="numComments" class="numComments">' . $dbh->getPostNumberOfComments($postId) . '</p>
//                 </div>
//             </div>
//             ';
//     }
//     return $html;
// }

?>