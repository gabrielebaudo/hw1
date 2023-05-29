<?php
    require_once 'db_params.php';
    require_once 'check_auth.php';

    if (checkAuth()) {
        header("Location: home.php");
        exit;
    }

    if (!empty($_POST["username"]) && !empty($_POST["pass"])) {
        $conn = mysqli_connect($db_params['host'], $db_params['user'], $db_params['password'], $db_params['name']) or die(mysqli_error($conn));

        $username = mysqli_real_escape_string($conn, $_POST['username']);

        $query = "SELECT * FROM users WHERE username = '$username'";

        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));;
        
        if (mysqli_num_rows($res) > 0) {
            // Ritorna una sola riga, il che ci basta perché l'utente autenticato è solo uno
            $entry = mysqli_fetch_assoc($res);

            if (password_verify($_POST['pass'], $entry['password'])) {

                $_SESSION["_agora_username"] = $entry['username'];
                mysqli_free_result($res);
                mysqli_close($conn);
                header("Location: home.php");
                exit;
            }
            else{
                $error = "Username o password errati";
            }
        }
        else{
            $error = "Username o password errati";
        }
    }
    else if(isset($_POST["username"]) || isset($_POST["pass"])) {
        $error = "Inserisci username e/o password";
    }
?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Accedi</title>
        <link rel="icon" type="image/png" href="utility/favicon.png">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;600&display=swap">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="sign&login.css">
        <link rel="stylesheet" href="login.css">
        <script src='login.js' defer></script>
    </head>
    <body>
        <div class="box">
            <img src="utility/logo.png" id="logo">
            <h2>Accedi</h2>
            <form name='login' method='post'>
                <div class="username">
                    <label for='username'>Nome utente:</label>
                    <input type='text' name='username'>
                    <span>Inserisci il nome utente</span>
                </div>
                <div class="pass">
                    <label for="pass">Password:</label>
                    <input class = "shorter" type="password" name='pass'>
                    <img class = "eye" src="utility/show.png"></img>
                    <span>Inserisci la password</span>
                </div>
                <input type="submit" value="Accedi">
            </form>
            <?php 
            if(isset($error)) {
                  echo "<div class='error'><span>".$error."</span></div>";
            }
            ?>
            <div id="message">
                <span>Non hai un account?</span>
                <a href = "signup.php">Iscriviti</a>
            </div>           
        </div>
    </body>
</html>