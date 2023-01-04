<section>
    <h1>Change description</h1>
    <form method='post' id='changeDescription' data-id='<?php echo $templateParams['nameCommunity']; ?>'>
        <textarea name='description' id='description' placeholder='Description' maxlength='2048'
            required><?php echo $templateParams['description']; ?></textarea><br />
        <button type='submit' name='submitDescription'>Change</button><br />
    </form>
</section>
<section>
    <h1>Delete community</h1>
    <div class='deleteButton'>
        <button type='button' id='deleteCommunity'>Delete</button>
    </div>
</section>