<?php
include 'config/config.php';


function getPokemonData($id) {
    $url = "https://pokeapi.co/api/v2/pokemon/$id";
    $data = file_get_contents($url);
    return json_decode($data, true);
}

for ($i = 1; $i <= 150; $i++) {
    $pokemon = getPokemonData($i);

    $name = ucfirst($pokemon['name']);
    $height = $pokemon['height'];
    $weight = $pokemon['weight'];
    $image_url = $pokemon['sprites']['other']['official-artwork']['front_default'];

    $types = array_map(function($type_info) {
        return $type_info['type']['name'];
    }, $pokemon['types']);
    $types_str = implode(',', $types);


    $sql = "INSERT INTO pokemon (name, height, weight, image_url, types) VALUES ('$name', $height, $weight, '$image_url', '$types_str')";
    
    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
