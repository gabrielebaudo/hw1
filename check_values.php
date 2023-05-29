<?php 
    require_once 'db_params.php';

    if (!isset($_GET["q"])) {
        exit;
    }

    header('Content-Type: application/json');

    $conn = mysqli_connect($db_params['host'], $db_params['user'], $db_params['password'], $db_params['name']);

    //preleviamo i parametri dalla query
    //select sarà la variabile che discriminerà la richiesta per l'username da quella per l'email
    $select = mysqli_real_escape_string($conn, $_GET["q"]);
    $value = mysqli_real_escape_string($conn, $_GET["value"]);

    if($select == 'u'){
        $query = "SELECT username FROM users WHERE username = '$value'";
    }
    if($select == 'e'){
        $query = "SELECT email FROM users WHERE email = '$value'";
    }

    //effettuiamo la query, se c'è un errore lo torniamo con la die
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

    //torniamo un array avente il campo exist che potrà essere true o false
    //in base al risultato della query
    echo json_encode(array('exist' => mysqli_num_rows($res) > 0 ? true : false));

    mysqli_close($conn);
?>