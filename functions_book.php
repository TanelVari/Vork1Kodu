<?php

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

        if (!isset($_POST['no_isbn'])){
            if (!empty($_POST['isbn'])){
                $book['isbn'] = htmlspecialchars($_POST['isbn']);
            } else {
                $errors[] = 'ISBN on puudu';
            }
        } else {
            $book['no_isbn'] = "checked";
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

/*** apply_book_changes ***/
function apply_book_changes($id){
    global $connection;
    global $errors;

    check_admin_permissions();

    $book = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['modify_book'])){

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


        if ($_FILES['cover']['error'] == 4){
            if (!empty($_POST['default_cover'])){
                $book['cover'] = htmlspecialchars($_POST['default_cover']);
            }
        }
        else {
            $upload_result = upload("cover", $book['year']);

            if ( $upload_result == ""){
                $errors[] = 'Probleem kaanepildi üleslaadimisega';
            }
            else {
                $book['cover'] = htmlspecialchars($upload_result);
            }
        }

        // if errors
        if (!empty($errors)){
            include_once('views/modify_page.php');
            die();
        }
        else {
            $id = mysqli_real_escape_string($connection, $id);

            $title = mysqli_real_escape_string($connection, $book['title']);
            $author = mysqli_real_escape_string($connection, $book['author']);
            $cover = mysqli_real_escape_string($connection, $book['cover']);
            $year = mysqli_real_escape_string($connection, $book['year']);

            $sql_values = "title='".$title."', author='".$author."', year=".$year.", cover='".$cover."'";

            if (isset($book['isbn'])){
                $isbn = mysqli_real_escape_string($connection, $book['isbn']);
                $sql_values = $sql_values.", isbn=".$isbn;
            }
            if (isset($book['rating'])){
                $rating = mysqli_real_escape_string($connection, $book['rating']);
                $sql_values = $sql_values.", rating=".$rating;
            }
            if (isset($book['comments'])){
                $comment = mysqli_real_escape_string($connection, $book['comments']);
                $sql_values = $sql_values.", comment='".$comment."'";
            }

            if (isset($book['category'])){
                $category = mysqli_real_escape_string($connection, $book['category']);
                $sql_values = $sql_values.", category=".$category;
            }

            $sql = "UPDATE tvari_kodu_books SET ".$sql_values." WHERE id = ".$id;
            $result = mysqli_query($connection, $sql);

            if ($result){
                header("Location: ?page=book&id=".$id);
                die();
            }
            else{
                $errors[] = 'Raamatu detailide muutmine ebaõnnestus';
                include_once('views/modify_page.php');
                die();
            }
        }
    }
    else {
        header("Location: ?page=book&id=".$id);
        die();
    }
}

/*** show_book_page ***/
function show_book_page($id){
    global $connection;

    check_admin_permissions();
    $book = array();

    $id = mysqli_real_escape_string($connection, htmlspecialchars($id));

    $sql = "
            SELECT tvari_kodu_books.*, tvari_kodu_categories.category AS 'category_name', tvari_kodu_users.name 
            FROM ((tvari_kodu_books 
            INNER JOIN tvari_kodu_categories ON tvari_kodu_books.category = tvari_kodu_categories.id) 
            LEFT JOIN tvari_kodu_users ON tvari_kodu_users.id = tvari_kodu_books.borrower) 
            WHERE tvari_kodu_books.id = ".$id;
    //echo $sql;
    $result = mysqli_query($connection, $sql);

    while ($row = mysqli_fetch_assoc($result)){
        $book = $row;
    }

    include_once('views/book_page.php');
}

/*** modify_book_page ***/
function modify_book_page($id){
    global $connection;

    check_admin_permissions();
    $book = array();

    $id = mysqli_real_escape_string($connection, htmlspecialchars($id));

    $sql = "
            SELECT tvari_kodu_books.*, tvari_kodu_categories.category AS 'category_name', tvari_kodu_users.name 
            FROM ((tvari_kodu_books 
            INNER JOIN tvari_kodu_categories ON tvari_kodu_books.category = tvari_kodu_categories.id) 
            LEFT JOIN tvari_kodu_users ON tvari_kodu_users.id = tvari_kodu_books.borrower) 
            WHERE tvari_kodu_books.id = ".$id;
    $result = mysqli_query($connection, $sql);

    while ($row = mysqli_fetch_assoc($result)){
        $book = $row;
    }

    include_once('views/modify_page.php');
}

?>