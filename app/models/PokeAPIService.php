<?php

class PokeAPIService {
    private $base_url = 'https://pokeapi.co/api/v2/';

    public function getAllPokemon($limit) {
        $url = $this->base_url . 'pokemon?limit=' . $limit;
        $pokemonList = $this->makeRequest($url);
        
        foreach ($pokemonList['results'] as &$pokemon) {
            $pokemonDetails = $this->makeRequest($pokemon['url']);
            $pokemon['details'] = $pokemonDetails;
        }

        return $pokemonList['results'];
    }

    private function makeRequest($url) {
        $data = file_get_contents($url);
        return json_decode($data, true);
    }
}

?>
