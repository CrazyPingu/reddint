<div class='upperProfile' id='<?php echo $templateParams['userUsername']; ?>'>
    <p id='username'>
        <?php echo $templateParams['userUsername']; ?>
    </p>
    <div class='rightProfile'>
        <div class='follow'>
            <p>Followers: <?php echo $templateParams['followersCount']; ?></p>
            <p>Following: <?php echo $templateParams['followingCount']; ?></p>
        </div>
        <p>
            <?php echo $templateParams['userCreationDate']; ?>
        </p>
    </div>

    <?php if ($isUserLogged && $templateParams['userUsername'] != $_SESSION['username']): ?>
        <div class='followButton'>
            <?php if ($templateParams['isFollowing']): ?>
                <button type='button' id='follow'>Unfollow</button>
                <?php else: ?>
                <button type='button' id='follow'>Follow</button>
                <?php endif; ?>
        </div>
        <?php endif; ?>
    <p><?php echo $templateParams['userBio']; ?></p>
</div>
<div class='buttonPostComment'>
    <button type='button' id='buttonPost'>Post</button>
    <button type='button' id='buttonComment'>Comment</button>
</div>
<div class='spacePostComment'></div>