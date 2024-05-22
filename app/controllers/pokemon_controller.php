<?php

require_once 'app/models/PokeAPIService.php';

class PokemonController {
    public function index() {
        $pokeApiService = new PokeAPIService();
        $pokemonList = $pokeApiService->getAllPokemon(30);
        include 'app/views/pokemon_list.php';
    }
}

?>
