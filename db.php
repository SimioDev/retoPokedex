<?php
include 'config/config.php';

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = 20; // Número de Pokémon a cargar en cada solicitud

$sql = "SELECT * FROM pokemon LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

function getTypeBadgeClass($type) {
    $typeClasses = [
        'normal' => 'text-bg-secondary',
        'fire' => 'text-bg-danger',
        'water' => 'text-bg-primary',
        'electric' => 'text-bg-warning',
        'grass' => 'text-bg-success',
        'ice' => 'text-bg-info',
        'fighting' => 'text-bg-dark',
        'poison' => 'badge-purple',
        'ground' => 'badge-brown',
        'flying' => 'badge-sky',
        'psychic' => 'badge-pink',
        'bug' => 'badge-green',
        'rock' => 'badge-grey',
        'ghost' => 'badge-black',
        'dragon' => 'badge-teal',
        'dark' => 'badge-dark',
        'steel' => 'text-bg-light',
        'fairy' => 'badge-light-pink'
    ];
    return $typeClasses[$type] ?? 'badge-secondary';
}

function translateType($type) {
    $typeTranslations = [
        'normal' => 'Normal',
        'fire' => 'Fuego',
        'water' => 'Agua',
        'electric' => 'Eléctrico',
        'grass' => 'Planta',
        'ice' => 'Hielo',
        'fighting' => 'Lucha',
        'poison' => 'Veneno',
        'ground' => 'Tierra',
        'flying' => 'Volador',
        'psychic' => 'Psíquico',
        'bug' => 'Bicho',
        'rock' => 'Roca',
        'ghost' => 'Fantasma',
        'dragon' => 'Dragón',
        'dark' => 'Siniestro',
        'steel' => 'Acero',
        'fairy' => 'Hada'
    ];
    return $typeTranslations[$type] ?? ucfirst($type);
}

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="card pokemon-card mb-4 shadow mx-auto" style="width: 16rem; overflow: hidden;">';
        echo '<div class="pokemon-number-badge rounded-pill bg-danger px-2 ">' . sprintf("#%03d", $row['id']) . '</div>';
        echo '<img src="./assets/imgs/fondo.webp" alt="fondo" class="card-img-top img-fluid mx-auto" style="position: absolute; top: 0; left: 0; z-index: 0;">';
        echo '<img src="' . $row['image_url'] . '" alt="' . $row['name'] . '" class="w-75 mx-auto card-img-top img-fluid col-6 col-md-4" style="position: relative; z-index: 1;">';
        echo '<h4 class="text-center mt-2 mb-3"><b>' . $row['name'] . '</b></h4>';
        echo '<p class="px-2"><b>- Altura: </b>' . $row['height'] . ' decímetros.</p>';
        echo '<p class="px-2"><b>- Peso: </b>' . $row['weight'] . ' kg.</p>';

        $types = explode(',', $row['types']);
        echo '<p class="text-center">';
        foreach ($types as $type) {
            $badgeClass = getTypeBadgeClass($type);
            $typeTranslated = translateType($type);
            echo '<span class="badge ' . $badgeClass . ' rounded-pill mr-1 mx-2 px-3 py-2">' . $typeTranslated . '</span>';
        }
        echo '</p>';

        echo '</div>';
    }
} else {
    echo '<div style="width:100%;height:0;padding-bottom:62%;position:relative;"><iframe src="https://giphy.com/embed/uRROb0WYxnPoc" width="100%" height="100%" style="position:absolute" frameBorder="0" class="giphy-embed" allowFullScreen></iframe></div><p><a href="https://giphy.com/gifs/crying-while-eating-uRROb0WYxnPoc">via GIPHY</a></p>';
    echo '<div class="col text-center">No se encontraron Pokémon en la base de datos.</div>';
}
?>
