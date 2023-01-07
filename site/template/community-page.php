<?php if(isset($templateParams['communityName'])): ?>
    <div class='communityInformations' id='<?php echo $templateParams['communityName'] ?>'>
        <div class='upperPart'>
            <p id='community-name'><?php echo $templateParams['communityName'] ?></p>
            <div class='rightPart'>
                <p>Members: <?php echo $templateParams['membersCount'] ?></p>
                <p><?php echo $templateParams['creationDate'] ?></p>
            </div>
            <div class='lowerPart'>
                <p id='communityDescription'><?php echo $templateParams['communityDescription'] ?></p>
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
        <div id='editSpace' class='optionButtons'>
            <button type='button' id='editButton'>Edit community</button>
            <button type='button' id='deleteButton'>Delete community</button>
        </div>
    <?php endif; ?>
    <div class='spacePosts'></div>
<?php else: ?>
    <div class='spaceCommunities'></div>
<?php endif; ?>