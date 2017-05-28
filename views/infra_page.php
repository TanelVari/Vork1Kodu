<h2>Taristu</h2>

<?php if (isset($errors)):?>
    <?php foreach($errors as $error):?>
        <div class="error_text"><?php echo htmlspecialchars($error); ?></div>
    <?php endforeach;?>
    <?php die() ?>
<?php endif;?>

<?php
    $categories = fetch_categories();
    $systems = fetch_systems();
?>

<form action="?page=add_room" method="POST" enctype="multipart/form-data" id="add_room" name="add_room" onsubmit="return validate_room()"></form>

<form action="?page=add_category" method="POST" enctype="multipart/form-data" id="add_category" name="add_category" onsubmit="return validate_category()"></form>
<form action="?page=add_system" method="POST" enctype="multipart/form-data" id="add_system" id="add_system" onsubmit="return validate_system()"></form>

<div class="padding_bottom">
    <select>
        <option>== Olemasolevad kategooriad ==</option>
        <?php
        if (isset($categories)){
            foreach($categories as $cat){
                echo "<option>".$cat["category"]."</option>";
            }
        }
        ?>
    </select>
    <input type="text" name="new_category" placeholder="Lisa kategooria" form="add_category" />
    <select name="new_category_system" id="new_category_system" form="add_category">
        <?php
        if (isset($systems)){
            foreach($systems as $sys){
                echo "<option value=\"".$sys["id"]."\">".$sys["description"]."</option>";
            }
        }
        ?>
    </select>
    <input type="color" name="new_category_color" value="#FFFFFF" form="add_category" />
    <input type="submit" value="Lisa" name="add_category" form="add_category" />
</div>

<div class="padding_top_bottom">
    <select>
        <option>== Sorteerimine ==</option>
        <?php
        if (isset($systems)){
            foreach($systems as $sys){
                echo "<option>".$sys["description"]."</option>";
            }
        }
        ?>
    </select>
    <input type="text" name="new_system" placeholder="Lisa kriteerium" form="add_system" />
    <input type="submit" value="Lisa" name="add_system" form="add_system" />
</div>

<hr class="line" />

<div class="padding_top_bigger">
    <input type="text" name="new_room" placeholder="Lisa tuba" form="add_room" />
    <input type="submit" value="Lisa" name="add_room" form="add_room" />
</div>

<?php foreach ($infra as $key_room => $room): ?>
    <div class="box_room">
        <h3><?= $key_room ?></h3>

        <div class="padding_bottom">
            <form action="?page=add_bookcase" method="POST" enctype="multipart/form-data" id="add_bookcase<?= $room["id"] ?>" name="add_bookcase<?= $room["id"] ?>" onsubmit="return validate_bookcase(<?= $room["id"] ?>)">
                <input type="text" name="new_bookcase" placeholder="Lisa tuppa raamatukapp"/>
                <input type="hidden" name="new_bookcase_room_id" value="<?= $room["id"] ?>"/>
                <input type="submit" value="Lisa" name="add_bookcase"/>
            </form>
        </div>

        <?php if (isset($room["bookcases"])): ?>
            <?php foreach ($room["bookcases"] as $bookcase): ?>
                <div class="box_bookcase">
                    <h4><?= $bookcase["description"] ?></h4>

                    <div class="padding_bottom">
                        <form action="?page=add_shelf" method="POST" enctype="multipart/form-data" id="add_shelf<?= $bookcase["id"] ?>" name="add_shelf<?= $bookcase["id"] ?>" onsubmit="return validate_shelf(<?= $bookcase["id"] ?>)">
                            <label>Lisa kappi riiul: </label>
                            <select name="new_shelf_category" id="new_shelf_category">
                                <?php
                                if (isset($categories)){
                                    foreach($categories as $cat){
                                        echo "<option value=\"".$cat["id"]."\">".$cat["category"]."</option>";
                                    }
                                }
                                ?>
                            </select>
                            <label>Riiuli jrk. nr: </label>
                            <input type="number" min="1" max="10" name="new_shelf_nr" id="new_shelf_nr" />
                            <input type="hidden" name="new_shelf_bookcase_id" value="<?= $bookcase["id"] ?>" />
                            <input type="submit" value="Lisa" name="add_shelf" />
                        </form>
                    </div>

                    <?php if (isset($bookcase["shelves"])): ?>
                        <hr class="line" />
                        <?php foreach ($bookcase["shelves"] as $shelf): ?>
                            <div class="box_shelf" style="background-color: <?= $shelf["color_id"] ?>">
                                Riiul <?= $shelf["shelf_nr"] ?>.<br/>
                                <?= $shelf["category"] ?><br/>
                                <span class="font_smaller"><?= $shelf["category_description"] ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<script>
    function validate_room() {
        var x = document.forms["add_room"]["new_room"].value;
        if (x == "") {
            alert("Ruumi nimi on täitmata");
            return false;
        }
    }

    function validate_bookcase(id) {
        var x = document.forms["add_bookcase"+id]["new_bookcase"].value;
        if (x == "") {
            alert("Raamatukapi nimi on puudu");
            return false;
        }
    }

    function validate_shelf(id) {
        var x = document.forms["add_shelf"+id]["new_shelf_category"].value;
        //var e = document.getElementById("new_shelf_category");
        //var x = e.options[e.selectedIndex].value;

        if (x == "") {
            alert("Riiuli kategooria on valimata");
            return false;
        }

        //x = document.getElementById('new_shelf_nr').value;
        x = document.forms["add_shelf"+id]["new_shelf_nr"].value;
        if (x == "") {
            alert("Riiuli järjekorranumber on puudu");
            return false;
        }
    }

    function validate_category() {
        var x = document.forms["add_category"]["new_category"].value;
        if (x == "") {
            alert("Kategooria on puudu");
            return false;
        }

        var e = document.getElementById("new_category_system");
        x = e.options[e.selectedIndex].value;
        if (x == "") {
            alert("Kategooria sortimissüsteem on puudu");
            return false;
        }

        x = document.forms["add_category"]["new_category_color"].value;
        if (x == "") {
            alert("Kategooria taustavärv on puudu");
            return false;
        } else if (x.toLowerCase() == "#ffffff"){
            alert("Ära vaikimisi värvi pane");
            return false;
        }
    }

    function validate_system() {
        var x = document.forms["add_system"]["new_system"].value;
        if (x == "") {
            alert("Uus sorteerimissüsteem on puudu");
            return false;
        }
    }
</script>
