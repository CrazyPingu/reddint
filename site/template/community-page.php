<?php if(isset($templateParams['communityName'])): ?>
    <?php if ($isUserLogged && $_SESSION['username'] == $templateParams['communityAuthor']) : ?>
        <div id='editSpace' class='optionButtons'>
            <button type='button' id='editButton'>Edit community</button>
            <button type='button' id='deleteButton'>Delete community</button>
        </div>
    <?php endif; ?>
    <div class='communityInformations' id='<?php echo $templateParams['communityName'] ?>'>
        <div class='generalInformationCommunity'>
            <p id='community-name'><?php echo $templateParams['communityName'] ?></p>
            <p>Members: <?php echo $templateParams['membersCount'] ?></p>
            <p><?php echo $templateParams['creationDate'] ?></p>
        </div>
        <p class='communityDescription' id='communityDescription'><?php echo $templateParams['communityDescription'] ?></p>
        <button type='button' class='participateButton' id='participateButton'>
            <?php if($templateParams['isParticipating']): ?>
                Leave
                <?php else: ?>
                Join
                <?php endif; ?>
        </button>
    </div>
    <div class='postContainer'></div>
<?php else: ?>
    <div class='spaceCommunities'></div>
<?php endif; ?>