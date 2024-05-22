
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./public/imgs/pokeball.png"/>
    <link rel="stylesheet" href="public/styles.css">
    <title>Reto Pokedex</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body >
    <div class="background-div"></div>
    <div style="z-index: 1;" class="container">

        <div class="header">
            <img src="./public/imgs/pokemon_logo.png" alt="PokemonLogo" class="w-25 img-fluid mx-auto d-block mt-4">
        </div>
        <div class="pokemons row mt-5">
            <?php
                require_once 'app/controllers/pokemon_controller.php';
                $pokemonController = new PokemonController();
                $pokemonController->index();
            ?>
        </div>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>