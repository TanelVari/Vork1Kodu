<?php

/*** show_start_page ***/
function show_start_page(){

    if (!isset($_SESSION['username'])){
        header("Location: ?page=login");
        die();
    }

    global $connection;

    $borrowers = array();

    $sql = "
            SELECT tvari_kodu_books.*, tvari_kodu_users.name
            FROM (tvari_kodu_books 
            LEFT JOIN tvari_kodu_users ON tvari_kodu_users.id = tvari_kodu_books.borrower) 
            WHERE tvari_kodu_books.borrower IS NOT NULL AND tvari_kodu_books.borrow_date IS NOT NULL
            ";
    //echo $sql;
    $result = mysqli_query($connection, $sql);

    if ($result){
        while ($row = mysqli_fetch_assoc($result)){
            $borrowers[] = $row;
        }
    }

    include_once('views/start_page.php');
}

/*** show_infrastructure_page ***/
function show_infrastructure_page(){
    global $connection;

    check_admin_permissions();
    $infra = array();

    $sql = "SELECT DISTINCT * FROM tvari_kodu_rooms ORDER BY id";
    $result = mysqli_query($connection, $sql);

    while ($row = mysqli_fetch_assoc($result)){
        $infra[$row["name"]]["id"] = $row["id"];
    }

    $sql = "
            SELECT tvari_kodu_bookcases.id, tvari_kodu_bookcases.description, tvari_kodu_rooms.name 
            FROM tvari_kodu_bookcases 
            INNER JOIN tvari_kodu_rooms ON tvari_kodu_bookcases.room = tvari_kodu_rooms.id
            ";
    $result = mysqli_query($connection, $sql);

    while ($row = mysqli_fetch_assoc($result)){
        $infra[$row["name"]]["bookcases"][$row["id"]] = $row;
    }

    $sql = "
            SELECT tvari_kodu_bookcases.id, tvari_kodu_shelves.id AS 'shelf_id', tvari_kodu_categories.category, tvari_kodu_categories.color_id, tvari_kodu_systems.description AS 'category_description', tvari_kodu_shelves.shelf_nr, tvari_kodu_rooms.name
            FROM ((((tvari_kodu_shelves 
            INNER JOIN tvari_kodu_bookcases ON tvari_kodu_bookcases.id = tvari_kodu_shelves.bookcase)
            INNER JOIN  tvari_kodu_categories ON tvari_kodu_categories.id = tvari_kodu_shelves.category)
            INNER JOIN tvari_kodu_rooms ON tvari_kodu_rooms.id = tvari_kodu_bookcases.room)
            INNER JOIN tvari_kodu_systems ON tvari_kodu_systems.id = tvari_kodu_categories.system)
            ORDER BY shelf_nr
            ";
    $result = mysqli_query($connection, $sql);

    while ($row = mysqli_fetch_assoc($result)){
        $infra[$row["name"]]["bookcases"][$row["id"]]["shelves"][] = $row;
    }

    include_once('views/infra_page.php');

    /*
    echo "<pre>";
    print_r($infra);
    echo "</pre>";
    */
}

/*** do_search ***/
function do_search(){
    global $connection;

    check_admin_permissions();

    $search_results = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST'
            && !empty($_POST['search'])
            && !empty($_POST['keyword'])){

        $keyword = mysqli_real_escape_string($connection, htmlspecialchars($_POST['keyword']));

        $sql = "SELECT * FROM tvari_kodu_books WHERE title LIKE '%".$keyword."%' OR author LIKE '%".$keyword."%'";
        $result = mysqli_query($connection, $sql);

        while ($row = mysqli_fetch_assoc($result)){
            $search_results[] = $row;
        }

        include_once('views/start_page.php');

    } else {
        header("Location: ?page=start");
        die();
    }
}

/*** show_category ***/
function show_category(){
    global $connection;

    check_admin_permissions();

    $category_search_results = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST'
        && !empty($_POST['search_category'])
        && !empty($_POST['category'])){

        $category = mysqli_real_escape_string($connection, htmlspecialchars($_POST['category']));

        $sql = "
                SELECT tvari_kodu_books.*, tvari_kodu_categories.category AS 'book_category' 
                FROM tvari_kodu_books 
                INNER JOIN tvari_kodu_categories ON tvari_kodu_categories.id = tvari_kodu_books.category 
                WHERE tvari_kodu_books.category = ".$category;
        $result = mysqli_query($connection, $sql);

        while ($row = mysqli_fetch_assoc($result)){
            $category_search_results[] = $row;
        }

        include_once('views/start_page.php');

    } else {
        header("Location: ?page=start");
        die();
    }
}

?>