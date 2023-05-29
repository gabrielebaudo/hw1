<?php
    require_once 'db_params.php';

    session_start();

    function checkAuth() {
        if(isset($_SESSION['_agora_username'])) {
            return $_SESSION['_agora_username'];
        } else 
            return 0;
    }
?>