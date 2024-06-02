<?php
include 'config/config.php';

function getAllPokemon() {
    $pokemonList = [];
    $url = "https://pokeapi.co/api/v2/pokemon?limit=10000"; 
    $data = file_get_contents($url);
    $allPokemonData = json_decode($data, true);
    if (isset($allPokemonData['results'])) {
        $pokemonList = $allPokemonData['results'];
    }
    return $pokemonList;
}

function getPokemonData($url) {
    $data = file_get_contents($url);
    return json_decode($data, true);
}

$pokemonList = getAllPokemon();

foreach ($pokemonList as $pokemon) {
    $pokemonData = getPokemonData($pokemon['url']);

    $id = sprintf("#%03d", $pokemonData['id']);
    $name = ucfirst($pokemonData['name']);
    $height = $pokemonData['height'];
    $weight = $pokemonData['weight'];
    $image_url = $pokemonData['sprites']['other']['official-artwork']['front_default'];

    $types = array_map(function($type_info) {
        return $type_info['type']['name'];
    }, $pokemonData['types']);
    $types_str = implode(',', $types);

    $base_stats = [];
    foreach ($pokemonData['stats'] as $stat) {
        $base_stats[$stat['stat']['name']] = $stat['base_stat'];
    }
    
    $base_stat_hp = $base_stats['hp'];
    $base_stat_attack = $base_stats['attack'];
    $base_stat_defense = $base_stats['defense'];
    $base_stat_special_attack = $base_stats['special-attack'];
    $base_stat_special_defense = $base_stats['special-defense'];
    $base_stat_speed = $base_stats['speed'];

    $sql = "INSERT IGNORE INTO pokemon (id, name, height, weight, image_url, types, base_stat_hp, base_stat_attack, base_stat_defense, base_stat_special_attack, base_stat_special_defense, base_stat_speed) 
            VALUES ('$id','$name', $height, $weight, '$image_url', '$types_str', $base_stat_hp, $base_stat_attack, $base_stat_defense, $base_stat_special_attack, $base_stat_special_defense, $base_stat_speed)";

    if ($conn->query($sql) === TRUE) {
        
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();

header('Location: index.php'); 
?>
