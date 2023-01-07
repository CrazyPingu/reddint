<div class='post-container' data-id='<?php echo $templateParams['postId'] ?>'></div>
<?php if ($isUserLogged && $_SESSION['username'] == $templateParams['postAuthor']): ?>
    <div id='editSpace' class='optionButtons'>
        <button id='editButton'>Edit post</button>
        <button id='deleteButton'>Delete post</button><br>
    </div>
<?php endif; ?>
<div class='create-comment'>
    <form method='post' id='form-comment'>
        <textarea name='comment' id='content' placeholder='Comment' maxlength='8192' required></textarea>
        <input type='submit' name='submit' value='Comment'>
    </form>
</div>
<div class='comments-container'></div>