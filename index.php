<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./assets/imgs/pokeball.png"/>
    <link rel="stylesheet" href="./assets/styles.css">
    <title>Reto Pokedex - SimioDev</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="loader"></div>

    <div class="background-div"></div>
    <div style="z-index: 1;" class="container">

        <div class="header">
            <img src="./assets/imgs/pokemon_logo.png" alt="PokemonLogo" class="w-25 img-fluid mx-auto d-block mt-4">
        </div>

        
        <div class="filters mt-4 d-flex justify-content-center">
            <form class="row g-6" method="GET" action="index.php">
                
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                </div>
                <div class="col-md-4">
                    <select name="sort" class="form-select">
                        <option value="">Filtros</option>
                        <option value="name_asc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'name_asc' ? 'selected' : ''; ?>>Nombre (A-Z)</option>
                        <option value="name_desc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'name_desc' ? 'selected' : ''; ?>>Nombre (Z-A)</option>
                        <option value="number_asc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'number_asc' ? 'selected' : ''; ?>>Número (Menor a Mayor)</option>
                        <option value="number_desc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'number_desc' ? 'selected' : ''; ?>>Número (Mayor a Menor)</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Aplicar Filtro <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg></button>
                </div>

            </form>
            
        </div>
        
        <div class="filters d-flex justify-content-center"><a href="src/trainers.php" class="btn btn-danger mt-4 g-6">Gestionar Entrenadores <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg></a></div>


        <div id="pokemon-container" class="pokemons row mt-4">
            <?php include 'db.php'; ?>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            let offset = 20;
            const limit = 20;

            $(window).scroll(function() {
                if($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                    loadMorePokemon();
                }
            });

            function loadMorePokemon() {
                $('#loading').show();
                $.ajax({
                    url: 'load_more.php',
                    type: 'GET',
                    data: {
                        offset: offset,
                        sort: '<?php echo isset($_GET['sort']) ? $_GET['sort'] : ''; ?>',
                    },
                    success: function(data) {
                        $('#pokemon-container').append(data);
                        offset += limit;
                        $('#loading').hide();
                    },
                    error: function() {
                        $('#loading').hide();
                        alert('No se pudieron cargar más Pokémon.');
                    }
                });
            }

        });
    </script>
</body>
</html>
