<?php

global $errors;

?>

<h2>Lisa raamat</h2>

<form action="?page=add" method="POST" enctype="multipart/form-data" name="add_book_form" onsubmit="return validate_book()">

    <label for="title">Pealkiri:</label><br/>
    <input type="text" name="title" id="title" value="<?php if (isset($book['title'])) echo htmlspecialchars($book['title']); ?>"/><br/><br/>

    <label for="author">Autor:</label><br/>
    <input type="text" name="author" id="author" value="<?php if (isset($book['author'])) echo htmlspecialchars($book['author']); ?>"/><br/><br/>

    <label for="year">Väljaandmise aasta:</label><br/>
    <input type="number" name="year" id="year" min="1700" max="<?php echo intval(date("Y")) + 3; ?>" value="<?php if (isset($book['year'])) {echo htmlspecialchars($book['year']);} else {echo date("Y");} ?>"/><br/><br/>

    <label for="isbn">ISBN:</label><br/>
    <input type="number" name="isbn" id="isbn" min="1000000000000" max="9999999999999" width="200" value="<?php if (isset($book['isbn'])) echo htmlspecialchars($book['isbn']); ?>"/><br/>
    <input type="checkbox" name="no_isbn" id="no_isbn" <?php if (isset($book['no_isbn'])) echo "checked"; ?>/>
    <label for="no_isbn">ISBN numbrit pole</label><br/><br/>

    <label for="category">Kategooria</label>
    <select name="category" id="category">
        <?php

        $categories = fetch_categories();

        if (isset($categories)){
            foreach($categories as $cat){
                echo "<option value=\"".$cat["category"]."\"";
                if (isset($book['category']) && $book['category'] == $cat["category"]){
                    echo " selected";
                }
                echo ">".$cat["category"]."</option>";
            }
        }
        ?>
    </select><br/><br/>

    <label for="cover">Kaanepilt:</label><br/>
    <input type="file" name="cover" id="cover"/><br/><br/>

    <label for="rating">Rating:</label><br/>
    <input type="number" min="1" max="5" name="rating" id="rating" value="<?php if (isset($book['rating'])) echo htmlspecialchars($book['rating']); ?>"/><br/><br/>

    <label for="comments">Märkusi:</label><br/>
    <textarea name="comments" id="comments"><?php if (isset($book['comments'])) echo htmlspecialchars($book['comments']); ?></textarea><br/><br/>

    <input type="submit" value="Lisa" name="add_book"/>
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
        if (x == "") {
            alert("Kaanepilt on puudu");
            return false;
        }
    }
</script>
