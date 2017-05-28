<h2>Esileht</h2>

<?php if (isset($errors)):?>
    <?php foreach($errors as $error):?>
        <div class="error_text"><?php echo htmlspecialchars($error); ?></div>
    <?php endforeach;?>
<?php endif;?>

<div class="padding_top_bottom clear">
    <form action="?page=search" method="POST" enctype="multipart/form-data">
        <input type="text" name="keyword" placeholder="Sisesta otsisõna" />
        <input type="submit" value="Otsi" name="search" />
    </form>
</div>

<div class="padding_top_bottom clear">
    <form action="?page=category" method="POST" enctype="multipart/form-data">
        <label for="category">Vaata raamatuid kategooriast</label>
        <select name="category" id="category">
            <?php

            $categories = fetch_categories();

            if (isset($categories)){
                foreach($categories as $cat){
                    echo "<option value=\"".$cat["id"]."\">".$cat["category"]."</option>";
                }
            }
            ?>
        </select>
        <input type="submit" value="Vaata" name="search_category" />
    </form>
</div>

<div class="padding_top_bottom">
    <hr class="line_lighter">
</div>

<?php if (!empty($search_results)): ?>
    <div>
        <?php foreach($search_results as $book):?>
            <div>
                <?php if (isset($book['title'])): ?>
                    <p><a href="?page=book&id=<?php if (isset($book['id'])) echo htmlspecialchars($book["id"]); ?>"><?php echo htmlspecialchars($book["title"]); ?></a></p>
                <?php endif; ?>

                <?php if (isset($book['author'])): ?>
                    <p><?php echo htmlspecialchars($book["author"]); ?></p>
                <?php endif; ?>

                <?php if (isset($book['borrower'])): ?>
                    <p>Välja laenutatud</p>
                <?php endif; ?>
            </div>
        <?php endforeach;?>
    </div>
<?php endif; ?>

<?php if (!empty($category_search_results)): ?>
    <div>
        <h3><?php if (isset($category_search_results[0]["book_category"])) echo htmlspecialchars($category_search_results[0]["book_category"]); ?></h3>
        <?php foreach($category_search_results as $book):?>
            <div>
                <?php if (isset($book['title']) && isset($book['author']) && isset($book['year'])): ?>
                    <p><a href="?page=book&id=<?php if (isset($book['id'])) echo htmlspecialchars($book["id"]); ?>">
                            <?php echo htmlspecialchars($book["title"]); ?>;
                            <?php echo htmlspecialchars($book["author"]); ?>;
                            <?php echo htmlspecialchars($book["year"]); ?>;
                    </a></p>
                <?php endif; ?>
            </div>
        <?php endforeach;?>
    </div>
<?php endif; ?>

<?php if (!empty($borrowers)): ?>
    <div>
        <?php foreach($borrowers as $book):?>
            <div>
                <div class="padding_top_bottom">
                    <h3>Välja laenutatud</h3>
                </div>
                <form action="?page=return" method="POST" enctype="multipart/form-data">
                    <?php if (isset($book['title'])) echo htmlspecialchars($book["title"]); ?><br/>
                    <span class="font_smaller"><?php if (isset($book['name'])) echo htmlspecialchars($book["name"]); ?> (<?php if (isset($book['borrow_date'])) echo date('j-m-Y', strtotime(htmlspecialchars($book['borrow_date']))); ?>)</span><br/>
                    <input type="hidden" name="book_id" value="<?php if (isset($book['id'])) echo htmlspecialchars($book['id']); ?>" />
                    <input type="submit" value="Tagasta" name="return" />
                </form>
            </div>
        <?php endforeach;?>
    </div>
<?php endif; ?>