<?php

/*** add_room_form ***/
function add_room_form(){
    global $connection;
    global $errors;

    check_admin_permissions();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['add_room']) && !empty($_POST['new_room'])){

        $room = mysqli_real_escape_string($connection, htmlspecialchars($_POST['new_room']));

        $sql = "INSERT INTO tvari_kodu_rooms (name) VALUES ('".$room."')";
        $result = mysqli_query($connection, $sql);

        echo $sql;
        if ($result && mysqli_insert_id($connection) > 0){
            header("Location: ?page=infra");
            die();
        }
        else{
            $errors[] = 'Ruumi lisamine ebaõnnestus';
            include_once('views/infra_page.php');
            die();
        }
    } else {
        header("Location: ?page=infra");
    }
}

/*** add_bookcase_form ***/
function add_bookcase_form(){
    global $connection;
    global $errors;

    check_admin_permissions();

    if ($_SERVER['REQUEST_METHOD'] == 'POST'
        && !empty($_POST['add_bookcase'])
        && !empty($_POST['new_bookcase'])
        && !empty($_POST['new_bookcase_room_id'])){

        $description = mysqli_real_escape_string($connection, htmlspecialchars($_POST['new_bookcase']));
        $room = mysqli_real_escape_string($connection, htmlspecialchars($_POST['new_bookcase_room_id']));

        $sql = "INSERT INTO tvari_kodu_bookcases (description, room) VALUES ('".$description."', ".$room.")";
        $result = mysqli_query($connection, $sql);

        if ($result && mysqli_insert_id($connection) > 0){
            header("Location: ?page=infra");
            die();
        }
        else{
            $errors[] = 'Raamatukapi lisamine ebaõnnestus';
            include_once('views/infra_page.php');
            die();
        }
    } else {
        header("Location: ?page=infra");
    }
}

/*** add_shelf_form ***/
function add_shelf_form(){
    global $connection;
    global $errors;

    check_admin_permissions();

    if ($_SERVER['REQUEST_METHOD'] == 'POST'
        && !empty($_POST['add_shelf'])
        && !empty($_POST['new_shelf_bookcase_id'])
        && !empty($_POST['new_shelf_category'])
        && !empty($_POST['new_shelf_nr'])){

        $bookcase = mysqli_real_escape_string($connection, htmlspecialchars($_POST['new_shelf_bookcase_id']));
        $category = mysqli_real_escape_string($connection, htmlspecialchars($_POST['new_shelf_category']));
        $shelf_nr = mysqli_real_escape_string($connection, htmlspecialchars($_POST['new_shelf_nr']));

        $sql = "INSERT INTO tvari_kodu_shelves (bookcase, category, shelf_nr) VALUES (".$bookcase.", ".$category.", ".$shelf_nr.")";
        $result = mysqli_query($connection, $sql);

        if ($result && mysqli_insert_id($connection) > 0){
            header("Location: ?page=infra");
            die();
        }
        else{
            $errors[] = 'Riiuli lisamine ebaõnnestus';
            include_once('views/infra_page.php');
            die();
        }
    } else {
        header("Location: ?page=infra");
    }
}

/*** add_category_form ***/
function add_category_form(){
    global $connection;
    global $errors;

    check_admin_permissions();

    if ($_SERVER['REQUEST_METHOD'] == 'POST'
        && !empty($_POST['add_category'])
        && !empty($_POST['new_category'])
        && !empty($_POST['new_category_system'])
        && !empty($_POST['new_category_color'])){

        $category = mysqli_real_escape_string($connection, htmlspecialchars($_POST['new_category']));
        $system = mysqli_real_escape_string($connection, htmlspecialchars($_POST['new_category_system']));
        $color_id = mysqli_real_escape_string($connection, htmlspecialchars($_POST['new_category_color']));

        $sql = "INSERT INTO tvari_kodu_categories (category, system, color_id) VALUES ('".$category."', ".$system.", '".$color_id."')";
        $result = mysqli_query($connection, $sql);

        if ($result && mysqli_insert_id($connection) > 0){
            header("Location: ?page=infra");
            die();
        }
        else{
            $errors[] = 'Kategooria lisamine ebaõnnestus';
            include_once('views/infra_page.php');
            die();
        }
    } else {
        header("Location: ?page=infra");
    }
}

/*** add_system_form ***/
function add_system_form(){
    global $connection;
    global $errors;

    check_admin_permissions();

    if ($_SERVER['REQUEST_METHOD'] == 'POST'
        && !empty($_POST['add_system'])
        && !empty($_POST['new_system'])){

        $description = mysqli_real_escape_string($connection, htmlspecialchars($_POST['new_system']));

        $sql = "INSERT INTO tvari_kodu_systems (description) VALUES ('".$description."')";
        $result = mysqli_query($connection, $sql);

        if ($result && mysqli_insert_id($connection) > 0){
            header("Location: ?page=infra");
            die();
        }
        else{
            $errors[] = 'Sorteerimiskriteeriumi lisamine ebaõnnestus';
            include_once('views/infra_page.php');
            die();
        }
    } else {
        header("Location: ?page=infra");
    }
}

/*** add_book ***/
function add_book(){
    global $connection;
    global $errors;

    check_admin_permissions();

    $book = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['add_book'])){
        if (empty($_POST['title'])){
            $errors[] = 'Pealkiri on puudu';
        }
        else {
            $book['title'] = htmlspecialchars($_POST['title']);
        }

        if (empty($_POST['author'])){
            $errors[] = 'Autori nimi on puudu';
        }
        else {
            $book['author'] = htmlspecialchars($_POST['author']);
        }

        if (empty($_POST['year'])){
            $errors[] = 'Väljaandmise aasta on puudu';
        }
        else if (intval($_POST['year']) < 1 || intval($_POST['year']) > intval(date("Y")) + 3) {
            $errors[] = 'Imelik aastaarv';
        }
        else {
            $book['year'] = htmlspecialchars($_POST['year']);
        }

        if (empty($_POST['isbn']) && !isset($_POST['no_isbn'])){
            $errors[] = 'ISBN on puudu';
        }
        else if (!empty($_POST['isbn']) && strlen($_POST['isbn']) != 13){
            $errors[] = 'ISBN on vale pikkusega';
        }
        else {
            $book['isbn'] = htmlspecialchars($_POST['isbn']);
        }

        if (empty($_POST['category'])){
            $errors[] = 'Kategooria on puudu';
        }
        else {
            $book['category'] = htmlspecialchars($_POST['category']);
        }
        if (!empty($_POST['rating'])){
            $book['rating'] = htmlspecialchars($_POST['rating']);
        }
        if (!empty($_POST['comments'])){
            $book['comments'] = htmlspecialchars($_POST['comments']);
        }

        $upload_result = upload("cover", $book['year']);

        if ( $upload_result == ""){
            $errors[] = 'Probleem kaanepildi üleslaadimisega';
        }
        else {
            $book['cover'] = htmlspecialchars($upload_result);
        }

        // if errors
        if (!empty($errors)){
            include_once('views/add_page.php');
            die();
        }
        else {
            $title = mysqli_real_escape_string($connection, $book['title']);
            $author = mysqli_real_escape_string($connection, $book['author']);
            $cover = mysqli_real_escape_string($connection, $book['cover']);
            $year = mysqli_real_escape_string($connection, $book['year']);

            $sql_fields = "title, author, year, cover";
            $sql_values = "'".$title."', '".$author."', ".$year.", '".$cover."'";

            if (isset($book['isbn'])){
                $isbn = mysqli_real_escape_string($connection, $book['isbn']);
                $sql_fields = $sql_fields.", isbn";
                $sql_values = $sql_values.", ".$isbn;
            }
            if (isset($book['rating'])){
                $rating = mysqli_real_escape_string($connection, $book['rating']);
                $sql_fields = $sql_fields.", rating";
                $sql_values = $sql_values.", ".$rating;
            }
            if (isset($book['comments'])){
                $comment = mysqli_real_escape_string($connection, $book['comments']);
                $sql_fields = $sql_fields.", comment";
                $sql_values = $sql_values.", '".$comment."'";
            }

            if (isset($book['category'])){
                $category = mysqli_real_escape_string($connection, $book['category']);
                $sql_fields = $sql_fields.", category";
                $sql_values = $sql_values.", tvari_kodu_categories.id FROM tvari_kodu_categories WHERE category = '".$category."'";
            }

            $sql = "INSERT INTO tvari_kodu_books (".$sql_fields.") SELECT ".$sql_values;
            $result = mysqli_query($connection, $sql);

            if ($result && mysqli_insert_id($connection) > 0){
                header("Location: ?page=book&id=".mysqli_insert_id($connection));
                die();
            }
            else{
                $errors[] = 'Raamatu lisamine ebaõnnestus';
                include_once('views/add_page.php');
                die();
            }
        }
    }
    else {
        include_once('views/add_page.php');
    }
}

?>