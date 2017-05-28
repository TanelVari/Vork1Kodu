<?php

/*** return_book ***/
function return_book(){

    check_admin_permissions();

    global $connection;

    if ($_SERVER['REQUEST_METHOD'] == 'POST'
        && !empty($_POST['return'])
        && !empty($_POST['book_id'])){

        $book_id = mysqli_real_escape_string($connection, htmlspecialchars($_POST['book_id']));

        $sql = "UPDATE tvari_kodu_books SET borrower = NULL, borrow_date = NULL WHERE id = ".$book_id;
        $result = mysqli_query($connection, $sql);

        if ($result){
            if (isset($_POST['book_page'])){
                header("Location: ?page=book&id=".htmlspecialchars($_POST['book_id']));
            } else {
                include_once('views/start_page.php');
            }
        }
    } else {
        $errors[] = 'Raamatu tagastamine ebaõnnestus';
        include_once('views/start_page.php');
        die();
    }
}

/*** borrow_book ***/
function borrow_book(){
    global $connection;

    check_admin_permissions();

    /*
    echo $_POST['borrow_book'];
    echo $_POST['borrowers'];
    echo $_POST['new_borrower'];
    echo $_POST['book_id'];
    //die();*/

    if ($_SERVER['REQUEST_METHOD'] == 'POST'
        && !empty($_POST['borrow_book'])
        && isset($_POST['borrowers'])
        && !empty($_POST['book_id'])){

        $borrower = intval(htmlspecialchars($_POST['borrowers']));

        $book_id = htmlspecialchars($_POST['book_id']);
        $b_id = mysqli_real_escape_string($connection, $book_id);

        if ($borrower != 0){
            // olemasolev laenutaja
            $b = mysqli_real_escape_string($connection, $borrower);
            $sql = "UPDATE tvari_kodu_books SET borrower=".$b.", borrow_date=CURDATE() WHERE id = ".$b_id;
            $result = mysqli_query($connection, $sql);

            if ($result){
                header("Location: ?page=book&id=".$book_id);
                die();
            } else {
                $_SESSION["notices"]["borrowing"]= "Laenutamine ebaõnnestus";
                header("Location: ?page=book&id=".$book_id);
            }

        } else {
            // uus laenutaja
            if (!empty($_POST['new_borrower'])){
                $b = mysqli_real_escape_string($connection, htmlspecialchars($_POST['new_borrower']));

                $sql = "INSERT INTO tvari_kodu_users (name, role) VALUES ('".$b."', 'guest')";
                $result = mysqli_query($connection, $sql);

                if ($result && mysqli_insert_id($connection) > 0){
                    $sql = "UPDATE tvari_kodu_books SET borrower=".mysqli_insert_id($connection).", borrow_date=CURDATE() WHERE id = ".$b_id;
                    $result = mysqli_query($connection, $sql);

                    if ($result){
                        header("Location: ?page=book&id=".$book_id);
                        die();
                    } else {
                        $_SESSION["notices"]["borrowing"]= "Laenutamine uuele kasutajale ebaõnnestus";
                        header("Location: ?page=book&id=".$book_id);
                    }

                } else {
                    $_SESSION["notices"]["borrowing"]= "Laenutaja lisamine ebaõnnestus";
                    header("Location: ?page=book&id=".$book_id);
                }
            }
        }
    }
    else {
        $_SESSION["notices"]["borrowing"]= "Laenutamiseks ei olenud piisavalt väljad täidetud!!!";
        header("Location: ?page=book&id=".htmlspecialchars($_POST['book_id']));
    }
}

?>