<?php if (count($templateParams['communities']) != 0): ?>
    <section>
        <h2>Create new post</h2>
        <form method='post' class='createForm' id='createPost'>
            <select name='community' id='communityList'>
                <?php foreach ($templateParams['communities'] as $community): ?>
                    <option value='<?php echo $community['name'] ?>'>
                        <?php echo $community['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select><br />
            <input name='title' id='titlePost' placeholder='Title' maxlength='256' required /><br />
            <textarea name='content' id='contentPost' placeholder='Content' maxlength='8192' required></textarea><br />
            <input type='submit' value='Create' /><br />
        </form>
    </section>
<?php endif; ?>
<section>
    <h2>Create new community</h2>
    <form method='post' class='createForm' id='createCommunity'>
        <input type='text' name='nameCommunity' id='nameCommunity' placeholder='Name' maxlength='64' pattern="^\S+$"
            required /><br />
        <textarea name='description' id='descriptionCommunity' placeholder='Description' maxlength='2048'
            required></textarea><br />
        <input type='submit' value='Create' /><br />
        <p class='warningPattern'>The community name mustn't contain whitespaces</p>
    </form>
</section>