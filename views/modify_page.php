<?php

global $errors;

?>

<h2>Muuda raamatut</h2>

<form action="?page=apply&id=<?php if (isset($book['id'])) echo htmlspecialchars($book['id']); ?>" method="POST" enctype="multipart/form-data" name="modify_book_form" onsubmit="return validate_book()">

    <input type="hidden" name="default_cover" value="<?php if (isset($book['cover'])) echo htmlspecialchars($book['cover']); ?>" />

    <label for="title">Pealkiri:</label><br/>
    <input type="text" name="title" id="title" value="<?php if (isset($book['title'])) echo htmlspecialchars($book['title']); ?>"/><br/><br/>

    <label for="author">Autor:</label><br/>
    <input type="text" name="author" id="author" value="<?php if (isset($book['author'])) echo htmlspecialchars($book['author']); ?>"/><br/><br/>

    <label for="year">Väljaandmise aasta:</label><br/>
    <input type="number" name="year" id="year" min="1700" max="<?php echo intval(date("Y")) + 3; ?>" value="<?php if (isset($book['year'])) echo htmlspecialchars($book['year']); ?>"/><br/><br/>

    <label for="isbn">ISBN:</label><br/>
    <input type="number" name="isbn" id="isbn" min="1000000000000" max="9999999999999" width="200" value="<?php if (isset($book['isbn'])) echo htmlspecialchars($book['isbn']); ?>"/><br/>
    <input type="checkbox" name="no_isbn" id="no_isbn" <?php if (!isset($book['isbn'])) echo "checked"; ?>/>
    <label for="no_isbn">ISBN numbrit pole</label><br/><br/>

    <label for="category">Kategooria</label>
    <select name="category" id="category">
        <?php

        $categories = fetch_categories();

        if (isset($categories)){
            foreach($categories as $cat){
                echo "<option value=\"".$cat["id"]."\"";
                if (isset($book['category']) && $book['category'] == $cat["id"]){
                    echo " selected";
                }
                echo ">".$cat["category"]."</option>";
            }
        }
        ?>
    </select><br/><br/>

    <?php if (isset($book['cover'])): ?>
        <img src="<?php echo htmlspecialchars($book['cover']); ?>" alt="<?php if (isset($book['title'])) echo htmlspecialchars($book['title']); ?>" width="32" />
    <?php endif; ?>

    <label for="cover">Kaanepilt:</label><br/>
    <input type="file" name="cover" id="cover"/><br/><br/>

    <label for="rating">Rating:</label><br/>
    <input type="number" min="1" max="5" name="rating" id="rating" value="<?php if (isset($book['rating'])) echo htmlspecialchars($book['rating']); ?>"/><br/><br/>

    <label for="comments">Märkusi:</label><br/>
    <textarea name="comments" id="comments"><?php if (isset($book['comments'])) echo htmlspecialchars($book['comments']); ?></textarea><br/><br/>

    <input type="submit" value="Muuda" name="modify_book"/>
</form>

<?php if (isset($errors)):?>
    <?php foreach($errors as $error):?>
        <div class="error_text"><?php echo htmlspecialchars($error); ?></div>
    <?php endforeach;?>
<?php endif;?>

<script>
    window.onload = function(){
        no_isbn_cb = document.getElementById("no_isbn");
        isbn_field = document.getElementById("isbn");

        no_isbn_cb.onclick = function(){
            isbn_field.value = '';
        }
    }

    function validate_book() {
        var x = document.forms["add_book_form"]["title"].value;
        if (x == "") {
            alert("Pealkiri on puudu");
            return false;
        }
        x = document.forms["add_book_form"]["author"].value;
        if (x == "") {
            alert("Autor on puudu");
            return false;
        }
        x = document.forms["add_book_form"]["year"].value;
        if (x == "") {
            alert("Aasta on puudu");
            return false;
        }
        x = document.forms["add_book_form"]["isbn"].value;
        if (x == "" && !document.getElementById('no_isbn').checked) {
            alert("ISBN on puudu");
            return false;
        }
        x = document.forms["add_book_form"]["cover"].value;
        var y = document.forms["add_book_form"]["default_cover"].value;
        if (x == "" && y == "") {
            alert("Kaanepilt on puudu");
            return false;
        }
    }
</script>
