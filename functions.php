<?php


function connect_db(){
	global $connection;

	$host="localhost";
	$user="test";
	$pass="t3st3r123";
	$db="test";
	$connection = mysqli_connect($host, $user, $pass, $db) or die("Can't connect to database - ".mysqli_error());
	mysqli_query($connection, "SET CHARACTER SET UTF8") or die("Cannot set character set - ".mysqli_error($connection));
}

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

        echo $_POST['username'];
        echo $_POST['password'];
        //die();

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

function logout(){
	$_SESSION = array();
	session_destroy();
	header("Location: ?");
}

function show_start_page(){

    if (!isset($_SESSION['username'])){
        header("Location: ?page=login");
        die();
    }

    global $connection;


    include_once('views/start_page.php');
}


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

function upload($name){
    global $errors;

	$allowedExts = array("jpg", "jpeg", "gif", "png");
	$allowedTypes = array("image/gif", "image/jpeg", "image/png","image/pjpeg");

    $tmp = explode('.', $_FILES[$name]["name"]);
    $extension = end($tmp);

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
			if (file_exists("covers/" . $_FILES[$name]["name"])) {
                // fail olemas ära uuesti lae, tagasta failinimi
				$_SESSION['notices'][]= $_FILES[$name]["name"] . " juba eksisteerib. ";
				return "covers/" .$_FILES[$name]["name"];
			} else {
                // kõik ok
				move_uploaded_file($_FILES[$name]["tmp_name"], "covers/" . $_FILES[$name]["name"]);
				return "covers/" .$_FILES[$name]["name"];
			}
		}
	} else {
		return "";
	}
}

?>