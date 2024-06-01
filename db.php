<?php
include 'config/config.php';

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = 20; 

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

        echo '<img src="' . $row['image_url'] . '" alt="' . $row['name'] . '" class="w-75 mx-auto card-img-top img-fluid col-6 col-md-4" style="position: relative; z-index: 1; filter: drop-shadow(0 0 0.55rem yellow);">';

        $types = explode(',', $row['types']);
        echo '<p class="text-center mt-1">';
        foreach ($types as $type) {
            $badgeClass = getTypeBadgeClass($type);
            $typeTranslated = translateType($type);
            echo '<span class="badge ' . $badgeClass . ' rounded-pill mr-1 mx-1 px-3 py-2">' . $typeTranslated . '</span>';
        }
        echo '</p>';

        echo '<h4 class="text-center mb-3"><b>' . $row['name'] . '</b></h4>';
        echo '<p class="px-2"><b>- Altura: </b>' . $row['height'] . ' decímetros.</p>';
        echo '<p class="px-2"><b>- Peso: </b>' . $row['weight'] . ' kg.</p>';
        
        echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pokemonModal" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '" data-height="' . $row['height'] . '" data-weight="' . $row['weight'] . '" data-image="' . $row['image_url'] . '" data-types="' . htmlspecialchars($row['types']) . '">Conoce más</button>';
        
        echo '</div>';
    }
} else {
    echo '<div class="text-center">No se encontraron Pokémon en la base de datos.</div>';
}
?>



<div class="modal fade" id="pokemonModal" tabindex="-1" aria-labelledby="pokemonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pokemonModalLabel">Información de </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="pokemonImage" src="" alt="" class="img-fluid mx-auto d-block" style="max-width: 150px;">
                <h4 class="text-center" id="pokemonName"></h4>
                <p id="pokemonTypes"></p>
                <p><b>- Altura: </b><span id="pokemonHeight"></span> decímetros.</p>
                <p><b>- Peso: </b><span id="pokemonWeight"></span> kg.</p>
            </div>
        </div>
    </div>
</div>



<script>
    var pokemonModal = document.getElementById('pokemonModal');
    pokemonModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var name = button.getAttribute('data-name');
        var height = button.getAttribute('data-height');
        var weight = button.getAttribute('data-weight');
        var image = button.getAttribute('data-image');
        var types = button.getAttribute('data-types').split(',');

        var modalTitle = pokemonModal.querySelector('.modal-title');
        var pokemonImage = pokemonModal.querySelector('#pokemonImage');
        var pokemonName = pokemonModal.querySelector('#pokemonName');
        var pokemonHeight = pokemonModal.querySelector('#pokemonHeight');
        var pokemonWeight = pokemonModal.querySelector('#pokemonWeight');
        var pokemonTypes = pokemonModal.querySelector('#pokemonTypes');

        modalTitle.textContent = 'Información de ' + name;
        pokemonImage.src = image;
        pokemonName.textContent = name;
        pokemonHeight.textContent = height;
        pokemonWeight.textContent = weight;

        pokemonTypes.innerHTML = '';
        types.forEach(function(type) {
            var badgeClass = '<?php echo json_encode(getTypeBadgeClass('TYPE_PLACEHOLDER')); ?>'.replace('TYPE_PLACEHOLDER', type);
            var typeTranslated = '<?php echo json_encode(translateType('TYPE_PLACEHOLDER')); ?>'.replace('TYPE_PLACEHOLDER', type);
            pokemonTypes.innerHTML += '<span class="badge ' + badgeClass + ' rounded-pill mr-1 mx-1 px-3 py-2">' + typeTranslated + '</span>';
        });
    });
</script>
