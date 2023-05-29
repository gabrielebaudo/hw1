<?php
    include 'db_params.php';
    
    session_start();
    session_destroy();

    //Torniamo alla landing del sito
    header('Location: index.html');
?>