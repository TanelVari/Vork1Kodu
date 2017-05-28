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
                <?php if (isset($book['title']) && isset($book['id']) && isset($book['author'])): ?>
                    <p><a href="?page=book&id=<?php echo htmlspecialchars($book["id"]); ?>">
                        <?php echo htmlspecialchars($book["title"]); ?>;
                        <?php echo htmlspecialchars($book["author"]); ?>;
                        <?php echo htmlspecialchars($book["year"]); ?></a>
                        <?php if (isset($book['borrower'])) echo "; <span class=\"error_text\">Laenus</span>"; ?>
                    </p>
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
                        <?php echo htmlspecialchars($book["year"]); ?></a>
                        <?php if (isset($book['borrower'])) echo "; <span class=\"error_text\">Laenus</span>"; ?></a>
                    </p>
                <?php endif; ?>
            </div>
        <?php endforeach;?>
    </div>
<?php endif; ?>

<?php if (!empty($borrowers)): ?>
    <div>
        <div class="padding_top_bottom">
            <h3>Välja laenutatud</h3>
        </div>
        <?php foreach($borrowers as $book):?>
            <div class="padding_top_bigger">
                <form action="?page=return" method="POST" enctype="multipart/form-data">
                    <span class="padding_top_bigger"><?php if (isset($book['title'])) echo htmlspecialchars($book["title"]); ?> => </span>
                    <?php if (isset($book['name'])) echo htmlspecialchars($book["name"]); ?> (<?php if (isset($book['borrow_date'])) echo date('j-m-Y', strtotime(htmlspecialchars($book['borrow_date']))); ?>)
                    <input type="hidden" name="book_id" value="<?php if (isset($book['id'])) echo htmlspecialchars($book['id']); ?>" />
                    <input type="submit" value="Tõi tagasi" name="return" />
                </form>
            </div>
        <?php endforeach;?>
    </div>
<?php endif; ?>