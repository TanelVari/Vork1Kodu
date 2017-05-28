<?php

require_once('functions.php');
session_start();
connect_db();

global $errors;

if (!isset($errors)) {
    $errors = array();
}

$page="pealeht";

if (isset($_GET['page']) && $_GET['page']!=""){
	$page = htmlspecialchars($_GET['page']);
}

if (isset($_GET['id']) && $_GET['id']!=""){
    $id = htmlspecialchars($_GET['id']);
}

include_once('views/header.php');


switch($page){
    case "login":
        login();
        break;
    case "start":
        show_start_page();
        break;
    case "add":
        add_book();
        break;
    case "book":
        if (isset($id)){
            show_book_page($id);
            break;
        }
        show_start_page();
        break;
    case "infra":
        show_infrastructure_page();
        break;

    case "add_room":
        add_room_form();
        break;
    case "add_bookcase":
        add_bookcase_form();
        break;
    case "add_shelf":
        add_shelf_form();
        break;

    case "add_category":
        add_category_form();
        break;
    case "add_system":
        add_system_form();
        break;

    case "logout":
        logout();
        break;
	default:
        show_start_page();
	    break;
}

include_once('views/footer.php');

?>