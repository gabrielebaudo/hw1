<?php 
    require_once 'db_params.php';

    header('Content-Type: application/json');

    $conn = mysqli_connect($db_params['host'], $db_params['user'], $db_params['password'], $db_params['name']);

    $query = "SELECT * FROM liked";

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

    $result_array = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $result_array[] = $row;
    }

    mysqli_close($conn);

    echo json_encode($result_array);
?>