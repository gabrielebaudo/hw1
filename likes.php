<?php 
  require_once 'check_auth.php';
  require_once 'db_params.php';

  if (!$username = checkAuth()) {
      header("Location: login.php");
      exit;
  }

  //HANDLER DEL COMMENTO
  if (!empty($_POST["comment-insert"])) {
    $conn = mysqli_connect($db_params['host'], $db_params['user'], $db_params['password'], $db_params['name']) or die(mysqli_error($conn));

    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $media = $_POST['media'];

    if($media == '0') {
      $content = mysqli_real_escape_string($conn, $_POST['comment-insert']);
      $query = "INSERT INTO comments (author, content, media, post_id) VALUES ('$username',  '$content', '$media', '$post_id')";
      mysqli_query($conn, $query);
      mysqli_close($conn); 
    }
  }
  else if(isset($_POST["comment-insert"])) {
    $error = "Ops non puoi pubblicare un commento vuoto";
  }

  //HANDLER DELLA GIF
  if(!empty($_POST['gif-source'])){
    $conn = mysqli_connect($db_params['host'], $db_params['user'], $db_params['password'], $db_params['name']) or die(mysqli_error($conn));

    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $media = $_POST['media'];

    if($media == '1') {
      $content = mysqli_real_escape_string($conn, $_POST['gif-source']);
      $query = "INSERT INTO comments (author, content, media, post_id) VALUES ('$username',  '$content', '$media', '$post_id')";
      mysqli_query($conn, $query);
      mysqli_close($conn); 
    }
  }
?>

<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link rel="icon" type="image/png" href="utility/favicon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;600&display=swap">
    <link rel="stylesheet" href="home.css">
    <script src='likes.js' defer></script>
  </head>
  <body>
    <nav>
    <img src="utility/logo.png" id="logo">
      <div id="link">
        <a href = "home.php">Home</a>
        <a href = "likes.php">Likes</a>
        <a href = "profile.php">Profile</a>
        <a href = "logout.php">Logout</a>
      </div>
      <div id="menu">
        <div></div>
        <div></div>
        <div></div>
      </div>
    </nav>
    <span id="description">I post che ti piacciono</span>
    <footer>
      <p>Powered by Gabriele Baudo<br>ID Number: 1000016055</p>
      <p>Web Programming Course 2023</p>
    </footer>
  </body>
</html>