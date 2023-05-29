<?php 
    require_once 'db_params.php';

    if(!isset($_GET["q"])) {
        exit;
    }

    header('Content-Type: application/json');

    $conn = mysqli_connect($db_params['host'], $db_params['user'], $db_params['password'], $db_params['name']);

    $type = mysqli_real_escape_string($conn, $_GET["q"]);

    //richiediamo i post
    if($type == 'post') {
        //se c'è anche l'user nella query torniamo solo i post associati a quell'utente
        if(isset($_GET["user"])) {
            $user = mysqli_real_escape_string($conn, $_GET["user"]);
            $query = "SELECT * FROM posts WHERE author = '$user' ORDER BY date DESC";
        } else {
            $query = "SELECT * FROM posts ORDER BY date DESC";
        }
    } else if($type == 'comment') { //torniamo tutti i commenti di un certo post
        $id = mysqli_real_escape_string($conn, $_GET["id"]);
        $query = "SELECT * FROM comments where post_id = $id";
    }

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

    $result_array = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $result_array[] = $row;
    }

    mysqli_close($conn);

    echo json_encode($result_array);
?>