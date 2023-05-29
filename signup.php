<?php
  require_once 'db_params.php';
  require_once 'check_auth.php';

  if (checkAuth()) {
    header("Location: home.php");
    exit;
  }  

  // Verifica l'esistenza di dati POST
  if (!empty($_POST["name"]) && !empty($_POST["lastname"]) && !empty($_POST["username"]) && !empty($_POST["mail"]) && !empty($_POST["pass"]) && !empty($_POST["confirmpass"])) {
    $error = array();

    //Apriamo connessione
    $conn = mysqli_connect($db_params['host'], $db_params['user'], $db_params['password'], $db_params['name']) or die(mysqli_error($conn));

    #CHECK USERNAME
    if(!preg_match('/^[a-z][a-z0-9_]{0,15}$/', $_POST['username'])) {
      $error[] = "Errore username. Sono ammesse solo lettere minuscole, numeri e underscore. Max 15 caratteri";
    }
    else {
      $username = mysqli_real_escape_string($conn, $_POST['username']);
      $query = "SELECT username FROM users WHERE username = '$username'";
      $res = mysqli_query($conn, $query);

      if (mysqli_num_rows($res) > 0) {
          $error[] = "Username già in uso";
      }
    }

    #CHECK PASSWORD
    if(strlen($_POST["pass"]) < 8) {
      $error[] = "La password deve essere di almeno 8 caratteri";
    } 
    if(!preg_match('/^(?=.*[A-Z])(?=.*[\W_])(?=.*[a-zA-Z0-9]).{8,}$/', $_POST['pass'])) {
      $error[] = "La password deve conenere almeno una lettera maiuscola e un simbolo";
    }

    #CHECK CONFIRM PASSWORD
    if (strcmp($_POST["pass"], $_POST["confirmpass"]) != 0) {
      $error[] = "Le password non coincidono";
    }

    #CHECK EMAIL
    if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
      $error[] = "Il formato dell'email non è valido";
    }
    else {
      $email = mysqli_real_escape_string($conn, strtolower($_POST['mail']));
      $res = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
      if (mysqli_num_rows($res) > 0) {
          $error[] = "Email già in uso";
      }
    }

    # REGISTRAZIONE NEL DATABASE
    if(count($error) == 0) {
      $name = mysqli_real_escape_string($conn, $_POST['name']);
      $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
      $password = mysqli_real_escape_string($conn, $_POST['pass']);
      $password = password_hash($password, PASSWORD_BCRYPT);

      $query = "INSERT INTO users(name, lastname, username, email, password) VALUES('$name', '$lastname', '$username', '$email', '$password')";
      
      if (mysqli_query($conn, $query)) {
          $_SESSION["_agora_username"] = $_POST["username"];
          mysqli_close($conn);
          header("Location: home.php");
          exit;
      } else {
          $error[] = "Errore di connessione al Database";
      }
    }
    mysqli_close($conn);
  }
?>

<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrazione</title>
    <link rel="icon" type="image/png" href="utility/favicon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;600&display=swap">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="sign&login.css">
    <link rel="stylesheet" href="signup.css">
    <script src='signup.js' defer></script>
  </head>
  <body>
    <div class="box">
      <img src="utility/logo.png" id="logo">
      <h2>Registrazione</h2>
      <form method='post' name='signup' autocomplete = "on">
        <div class="name">
            <label for="name">Nome:</label>
            <input type="text" name="name" <?php if(isset($_POST["name"])){echo "value=".$_POST["name"];} ?>>
            <span>Inserisci il tuo nome</span>
        </div>
        
        <div class="lastname">
            <label for="lastname">Cognome:</label>
            <input type="text" name="lastname" <?php if(isset($_POST["lastname"])){echo "value=".$_POST["lastname"];} ?>>
            <span>Inserisci il tuo cognome</span>
        </div>

        <div class="username">
            <label for="username">Nome utente:</label>
            <input type="text" name="username" <?php if(isset($_POST["username"])){echo "value=".$_POST["username"];} ?>>
            <span>Nome utente già in uso</span>
        </div>

        <div class="mail">
            <label for="mail">Email:</label>
            <input type="mail" name="mail" <?php if(isset($_POST["mail"])){echo "value=".$_POST["mail"];} ?>>
            <span>Il formato dell'email non è corretto</span>
        </div>

        <div class="pass">
            <label for="pass">Password:</label>
            <input class = "shorter" type="password" name="pass">
            <img class = "eye" src="utility/show.png"></img>
            <span>La password deve essere almeno di 8 caratteri</span>
        </div>

        <div class="confirmpass">
            <label for="confirmpass">Conferma password:</label>
            <input class = "shorter" type="password" name="confirmpass">
            <img class = "eye" src="utility/show.png"></img>
            <span>Le password non coincidono</span>
        </div>

       <?php 
            if(isset($error)) {
              foreach($error as $err) {
                  echo "<div class='error'><span>".$err."</span></div>";
              }
            }
        ?>
        <input type="submit" value="Registrati">
      </form>
      <div id="message">
                <span>Hai un account?</span>
                <a href = "login.php">Accedi</a>
      </div>
    </div>
  </body>
</html>