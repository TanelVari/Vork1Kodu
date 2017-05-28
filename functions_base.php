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
/*** fetch_borrowers ***/
function fetch_borrowers(){
    global $connection;

    $borrowers = array();

    $sql = "SELECT id, name FROM tvari_kodu_users WHERE role = 'guest' ORDER BY name";
    $result = mysqli_query($connection, $sql);

    while ($row = mysqli_fetch_assoc($result)){
        $borrowers[] = $row;
    }

    return $borrowers;
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