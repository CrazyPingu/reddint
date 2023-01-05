<div class='buttons'>
    <button id='communitiesPosts'><?php if (!$isUserLogged) echo 'Random '; ?>Communities posts</button>
    <button id='usersPosts' <?php if (!$isUserLogged) echo 'disabled'; ?>>Users posts</button>
</div>
<div id='postsSpace'></div>
