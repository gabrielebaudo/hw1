<?php
    require_once 'db_params.php';

    if (!isset($_GET["q"])) {
        //Usciamo dalla pagina senza mostrare errori
        exit;
    }

    header('Content-Type: application/json');

    $content = $_GET["q"];
    $endpoint = "http://api.giphy.com/v1/gifs/search";
    $api_key = "zN9tC8LN5iILHLdCuYAJGqXsOhLDTIpg";

    $query = $endpoint.'?q='.$content.'&api_key='.$api_key.'&limit=12';

    $data = file_get_contents($query);
    $results = json_decode($data);

    // Crea un array di URL delle immagini
    $image_urls = array();
    foreach($results->data as $result) {
        $url = $result->images->fixed_width->url;
        array_push($image_urls, $url);
    }

    echo json_encode($image_urls);
?>

