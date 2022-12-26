<?php
require_once '../bootstrap.php';
//echo $_SESSION['userId'];
$posts = $dbh->getPostsByUser($_SESSION['userId']);
//var_dump($dbh);
$html = '';
foreach ($posts as $post) {
    $html .= '
            <div class="topPartPost">
                <div class="communityAuthorLine">
                    <p name="community" id="community">' . $dbh->getCommunity($post['community'])['name'] . '</p>
                    <p name="author" id="author">' . $_SESSION['username'] . '</p>
                </div>
                <p name="date" id="date">' . $post['creation_date'] . '</p>
            </div>
            <p name="title" id="title" class="postTitle">' . $post['title'] . '</p>
            <p name="text" id="text" class="postText">' . $post['content'] . '</p>
        ';
}
echo $html;
