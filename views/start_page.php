<h2>Esileht</h2>

<?php if (isset($errors)):?>
    <?php foreach($errors as $error):?>
        <div class="error_text"><?php echo htmlspecialchars($error); ?></div>
    <?php endforeach;?>
<?php endif;?>

<div>
    <?php if (!empty($borrowers)): ?>
        <?php foreach($borrowers as $book):?>
            <div>
                <form action="?page=return" method="POST" enctype="multipart/form-data">
                <?php if (isset($book['title'])) echo htmlspecialchars($book["title"]); ?><br/>
                <?php if (isset($book['name'])) echo htmlspecialchars($book["name"]); ?> (<?php if (isset($book['borrow_date'])) echo date('j-m-Y', strtotime(htmlspecialchars($book['borrow_date']))); ?>)<br/>
                <input type="hidden" name="book_id" value="<?php if (isset($book['id'])) echo htmlspecialchars($book['id']); ?>" />
                <input type="submit" value="Tagasta" name="return" />
                </form>
            </div>
        <?php endforeach;?>
    <?php endif; ?>
</div>