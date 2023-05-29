<?php
    require_once 'check_auth.php';
    require_once 'db_params.php';

    if (!$username = checkAuth()) {
        exit;
    }

    $conn = mysqli_connect($db_params['host'], $db_params['user'], $db_params['password'], $db_params['name']);

    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);

    if($type == 'insert'){
        $query = "INSERT INTO liked(username, post_id) VALUES('$username','$post_id')";
    }
    else if($type == 'delete') {
        $query = "DELETE FROM liked WHERE username = '$username' AND post_id = '$post_id'";
    }

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

    mysqli_close($conn);
?>