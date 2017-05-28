<?php
    $borrowers = fetch_borrowers();
?>
<h2><?php if (isset($book['title'])) echo htmlspecialchars($book['title']); ?></h2>

<?php if (!empty($_SESSION["notices"]["borrowing"])):?>
    <div class="error_text"><?php echo htmlspecialchars($_SESSION["notices"]["borrowing"]); ?></div>
    <?php $_SESSION["notices"]["borrowing"] = ""; ?>
<?php endif;?>

<?php if (isset($book)): ?>
    <div class="book_cover">
        <?php if (isset($book['cover'])): ?>
        <img src="<?php echo htmlspecialchars($book['cover']); ?>" alt="<?php if (isset($book['title'])) echo htmlspecialchars($book['title']); ?>" width="100" />
        <?php endif; ?>
    </div>

    <div class="book_info">
        <?php if (isset($book['author'])): ?>
            <p><?php echo htmlspecialchars($book['author']); ?></p>
        <?php endif; ?>
        <?php if (isset($book['year'])): ?>
            <p><?php echo htmlspecialchars($book['year']); ?></p>
        <?php endif; ?>
        <?php if (isset($book['isbn'])): ?>
            <p>ISBN: <?php echo htmlspecialchars($book['isbn']); ?></p>
        <?php endif; ?>
        <?php if (isset($book['category_name'])): ?>
            <p>Kategoorias: <?php echo htmlspecialchars($book['category_name']); ?></p>
        <?php endif; ?>
        <?php if (isset($book['rating'])): ?>
            <p>Rating: <?php echo htmlspecialchars($book['rating']); ?></p>
        <?php endif; ?>
        <?php if (isset($book['comment'])): ?>
            <p><?php echo htmlspecialchars($book['comment']); ?></p>
        <?php endif; ?>

        <?php if (isset($book['name']) && isset($book['borrow_date'])): ?>
            <p>Välja laenatud: <?php echo htmlspecialchars($book['name']); ?> (<?php echo date('j-m-Y', strtotime(htmlspecialchars($book['borrow_date']))); ?>)</p>
        <?php endif; ?>

        <a href="?page=modify&id=<?php if (isset($book['id'])) echo htmlspecialchars($book['id']); ?>">Muuda</a>

        <?php if (isset($book['name']) && isset($book['borrow_date'])): ?>
            <div class="padding_top_bigger">
                <form action="?page=return" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="book_id" value="<?php if (isset($book['id'])) echo htmlspecialchars($book['id']); ?>" />
                    <input type="hidden" name="book_page" value="true" />
                    <input type="submit" value="Tagasta" name="return" />
                </form>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if (!isset($book['name']) && !isset($book['borrow_date'])): ?>
    <div class="padding_top_bottom clear">
        <form action="?page=borrow" method="POST" enctype="multipart/form-data" id="borrow" name="borrow" onsubmit="return validate_borrow()">
            <select name="borrowers">
                <option value="0">== Vali laenutaja ==</option>
                <?php
                if (isset($borrowers)){
                    foreach($borrowers as $person){
                        echo "<option value=\"".$person["id"]."\">".$person["name"]."</option>";
                    }
                }
                ?>
            </select>
            <input type="text" name="new_borrower" placeholder="Lisa laenutaja" />
            <input type="hidden" name="book_id" value="<?php if (isset($book['id'])) echo htmlspecialchars($book['id']); ?>" />
            <input type="submit" value="Laenuta" name="borrow_book" />
        </form>
    </div>
<?php endif; ?>

<script>
    function validate_borrow() {
        var x = document.forms["borrow"]["borrowers"].value;
        var y = document.forms["borrow"]["new_borrower"].value;

        if (x == "0" && y == "") {
            alert("Laenutaja nimi on puudu");
            return false;
        }

        if (x != "0" && y != "") {
            document.forms["borrow"]["borrowers"].value = "0";
        }
    }
</script>