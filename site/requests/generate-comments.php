<?php

function generateJson($comments, $dbh){
    $json = array();
    foreach ($comments as $comment) {
        $jsonComment = array();
        $jsonComment['username'] = $dbh->getUser($comment['author'])['username'];
        $jsonComment['commentUserVote'] = isset($_SESSION['userId']) ? $dbh->getUserCommentVote($_SESSION['userId'], $comment['id']) : 0;
        $jsonComment['commentVote'] =  $dbh->getCommentVote($comment['id']);
        $jsonComment['content'] = $comment['content'];
        $jsonComment['creationDate'] = $comment['creation_date'];
        $jsonComment['commentId'] = $comment['id'];
        array_push($json, $jsonComment);
    }
    return json_encode($json);
}

// function generateComments($comments, $dbh){
//     $html = '';
//     foreach ($comments as $comment) {
//         $username = $dbh->getUser($comment['author'])['username'];
//         $commentId = $comment['id'];
//         $commentVote = isset($_SESSION['userId']) ? $dbh->getUserCommentVote($_SESSION['userId'],$commentId) : 0;
//         $html .= '
//             <div class="commentAuthorDate" id="' . $commentId . '>
//                 <a name="author" id="author" href="../profile.php?username='. $username.'">' . $username . '</a>
//                 <p name="date" id="date">' . $comment['creation_date'] . '</p>
//             </div>
//             <p name="text" id="text" class="commentText">' . $comment['content'] . '</p>
//             <div class="vote">
//                 <button name="upvote" id="upvote" class="upvote '. ($commentVote == 1 ? 'voted' : '') .'" value="upvote" />
//                 <p name="score" id="score" class="score">' . $dbh->getCommentVote($comment['id']) . '</p>
//                 <button name="downvote" id="downvote" class="downvote '. ($commentVote == -1 ? 'voted' : '') .'" value="downvote" />
//             </div>
//         ';
//     }
//     return $html;
// }

?>