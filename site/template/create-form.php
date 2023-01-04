<section>
    <h1>Create new post</h1>
    <form method='post' id='createPost'>
        <select name='community' id='communityList'>
            <?php foreach ($templateParams['communities'] as $community): ?>
                <option value='<?php echo $community['name'] ?>'>
                    <?php echo $community['name'] ?>
                </option>
            <?php endforeach; ?>
        </select><br />
        <textarea name='title' id='titlePost' placeholder='Title' maxlength='256' required></textarea><br />
        <textarea name='content' id='contentPost' placeholder='Content' maxlength='8192' required></textarea><br />
        <input type='submit' value='Create' /><br />
    </form>
</section>
<section>
    <h1>Create new community</h1>
    <form method='post' id='createCommunity'>
        <textarea name='nameCommunity' id='nameCommunity' placeholder='Name' maxlength='64' required></textarea><br />
        <textarea name='description' id='descriptionCommunity' placeholder='Description' maxlength='2048'
            required></textarea><br />
        <input type='submit' value='Create' /><br />
    </form>
</section>