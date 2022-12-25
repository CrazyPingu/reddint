<div class='upperProfile'>
    <h2><?php echo $templateParams['userUsername']; ?></h2>
    <div class='rightProfile'>
        <div class='follow'>
            <p><?php echo $templateParams['followersCount']; ?></p>
            <p><?php echo $templateParams['followingCount']; ?></p>
        </div>
    </div>
    <h4><?php echo $templateParams['userBio']; ?></h4>
</div>
<div class='buttonPostComment'>
    <button type='button' id='buttonPost'>Post</button>
    <button type='button' id='buttonComment'>Comment</button>
</div>
<div class='spacePostComment'></div>