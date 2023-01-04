<?php if(isset($templateParams['communityName'])): ?>
    <div class='communityInformations' id='<?php echo $templateParams['communityName'] ?>'>
        <div class='upperPart'>
            <p id='community-name'><?php echo $templateParams['communityName'] ?></p>
            <div class='rightPart'>
                <p>Members: <?php echo $templateParams['membersCount'] ?></p>
                <p><?php echo $templateParams['creationDate'] ?></p>
            </div>
            <div class='lowerPart'>
                    <button type='button' id='participateButton'>
                        <?php if($templateParams['isParticipating']): ?>
                            Leave
                            <?php else: ?>
                            Join
                            <?php endif; ?>
                    </button>
            </div>
        </div>
    </div>
    <?php if ($isUserLogged && $_SESSION['username'] == $templateParams['communityAuthor']) : ?>
        <button type='button' id='modifyButton'>Modify community</button>
    <?php endif; ?>
    <div class='spacePosts'></div>
<?php else: ?>
    <div class='spaceCommunities'></div>
<?php endif; ?>