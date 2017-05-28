<?php

/*** connect_db ***/
function connect_db(){
	global $connection;

	$host="localhost";
	$user="test";
	$pass="t3st3r123";
	$db="test";
	$connection = mysqli_connect($host, $user, $pass, $db) or die("Can't connect to database - ".mysqli_error());
	mysqli_query($connection, "SET CHARACTER SET UTF8") or die("Cannot set character set - ".mysqli_error($connection));
}

/*** login ***/
function login(){
    global $connection;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['login'])){

        // check if form fields are filled
        if (empty($_POST['username'])){
            $errors[] = 'Kasutajanimi on puudu';
        }
        if (empty($_POST['password'])){
            $errors[] = 'Parool on puudu';
        }

        // if errors then do errors on login page
        if (!empty($errors)){
            include_once('views/login.php');
            die();
        }
        else { // if no errors then try to login
            $u = mysqli_real_escape_string($connection, $_POST['username']);
            $p = mysqli_real_escape_string($connection, $_POST['password']);

            $sql = "SELECT * FROM tvari_kodu_users WHERE name = '".$u."' AND password = SHA1('".$p."')";
            $result = mysqli_query($connection, $sql);

            if ($result && mysqli_num_rows($result) == 1){

                $row = mysqli_fetch_assoc($result);
                $_SESSION['username'] = $row['name'];
                $_SESSION['role'] = $row['role'];

                header("Location: ?page=start");
                die();
            }
            else {
                include_once('views/login.php');
            }
        }
    }
    else {
        include_once('views/login.php');
    }
}

/*** logout ***/
function logout(){
	$_SESSION = array();
	session_destroy();
	header("Location: ?");
}

/*** show_start_page ***/
function show_start_page(){

    if (!isset($_SESSION['username'])){
        header("Location: ?page=login");
        die();
    }

    global $connection;


    include_once('views/start_page.php');
}

/*** check_admin_permissions ***/
function check_admin_permissions(){

    if (!isset($_SESSION['username']) || !isset($_SESSION['role'])){
        header("Location: ?page=login");
        die();
    }

    if ($_SESSION['role'] != 'owner'){
        header("Location: ?page=login");
        die();
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

/*** show_book_page ***/
function show_book_page($id){
    global $connection;

    check_admin_permissions();
    $book = array();

    $id = mysqli_real_escape_string($connection, htmlspecialchars($id));

    $sql = "SELECT * FROM tvari_kodu_books INNER JOIN tvari_kodu_categories ON tvari_kodu_books.category = tvari_kodu_categories.id WHERE tvari_kodu_books.id = ".$id;
    $result = mysqli_query($connection, $sql);

    while ($row = mysqli_fetch_assoc($result)){
        $book = $row;
    }

    include_once('views/book_page.php');
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

/*** fetch_categories ***/
function fetch_categories(){
    global $connection;

    $categories = array();

    $sql = "SELECT id, category FROM tvari_kodu_categories ORDER BY category";
    $result = mysqli_query($connection, $sql);

    while ($row = mysqli_fetch_assoc($result)){
        $categories[] = $row;
    }

    return $categories;
}

/*** fetch_systems ***/
function fetch_systems(){
    global $connection;

    $systems = array();

    $sql = "SELECT id, description FROM tvari_kodu_systems ORDER BY description";
    $result = mysqli_query($connection, $sql);

    while ($row = mysqli_fetch_assoc($result)){
        $systems[] = $row;
    }

    return $systems;
}

/*** upload ***/
function upload($name, $year){
    global $errors;

	$allowedExts = array("jpg", "jpeg", "gif", "png");
	$allowedTypes = array("image/gif", "image/jpeg", "image/png","image/pjpeg");


    $tmp = explode('.', $_FILES[$name]["name"]);
    $extension = end($tmp);
    $file_name = "";

    for ( $i = 0; $i < count($tmp) - 1; $i++){
        $file_name = $file_name.$tmp[$i];
    }

    $file_name = sanitize_filename($file_name)."-".$year.".".$extension;

    if ($_FILES[$name]["size"] > 200000){
        $errors[] = "Fail on liiga suur, otsi pisem pilt (väiksem kui 200KB)";
        return "";
    }

	if ( in_array($_FILES[$name]["type"], $allowedTypes)
		    && in_array($extension, $allowedExts)) {
        // fail õiget tüüpi ja suurusega
		if ($_FILES[$name]["error"] > 0) {
			$_SESSION['notices'][]= "Return Code: " . $_FILES[$name]["error"];
			return "";
		}
		else {
		// vigu ei ole
			if (file_exists("covers/" .$file_name)) {
                // fail olemas ära uuesti lae, tagasta failinimi
				$_SESSION['notices'][]= $file_name. " juba eksisteerib. ";
				return "covers/" .$file_name;
			} else {
                // kõik ok
				move_uploaded_file($_FILES[$name]["tmp_name"], "covers/" .$file_name);
				return "covers/" .$file_name;
			}
		}
	} else {
		return "";
	}
}

/*** upload ***/
/*** https://stackoverflow.com/questions/2668854/sanitizing-strings-to-make-them-url-and-filename-safe ***/
function sanitize_filename($string)
{
    $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
    $string = preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $string);
    $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
    $string = preg_replace(array('~[^0-9a-z]~i', '~[ -]+~'), '', $string);

    return trim($string, ' -');
}

/*
echo "<pre>";
print_r($categories);
echo "</pre>";
*/

?>