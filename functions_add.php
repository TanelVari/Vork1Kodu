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

?>