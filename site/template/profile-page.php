<div class='upperProfile' id='<?php echo $templateParams['userUsername']; ?>'>
    <p id='username'>
        <?php echo $templateParams['userUsername']; ?>
    </p>
    <div class='rightProfile'>
        <div class='follow'>
            <a href='follow-list.php?username=<?php echo $templateParams['userUsername']; ?>&type=followersList'>Followers: <?php echo $templateParams['followersCount']; ?></a>
            <a href='follow-list.php?username=<?php echo $templateParams['userUsername']; ?>&type=followingList'>Following: <?php echo $templateParams['followingCount']; ?></a>
        </div>
        <p>
            <?php echo $templateParams['userCreationDate']; ?>
        </p>
    </div>
    <?php if ($isUserLogged && $templateParams['userUsername'] != $_SESSION['username']): ?>
        <div class='followButton'>
            <?php if ($templateParams['isFollowing']): ?>
                <button type='button' id='followButton'>Unfollow</button>
                <?php else: ?>
                <button type='button' id='followButton'>Follow</button>
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