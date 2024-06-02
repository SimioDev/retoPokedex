<?php
include 'config/config.php';

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = 20;

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $conn->real_escape_string($_GET['sort']) : '';

$sql = "SELECT * FROM pokemon";

if (!empty($search)) {
    $sql .= " WHERE name LIKE '%$search%'";
}


if (!empty($sort)) {
    switch ($sort) {
        case 'name_asc':
            $sql .= " ORDER BY name ASC";
            break;
        case 'name_desc':
            $sql .= " ORDER BY name DESC";
            break;
        case 'number_asc':
            $sql .= " ORDER BY id ASC";
            break;
        case 'number_desc':
            $sql .= " ORDER BY id DESC";
            break;
        default:
            $sql .= " ORDER BY id ASC";
            break;
    }
}

$sql .= " LIMIT $limit OFFSET $offset";

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

        echo '<p class="px-2"><b><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-swords"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 3v5l-11 9l-4 4l-3 -3l4 -4l9 -11z" /><path d="M5 13l6 6" /><path d="M14.32 17.32l3.68 3.68l3 -3l-3.365 -3.365" /><path d="M10 5.5l-2 -2.5h-5v5l3 2.5" /></svg> Ataque Base: </b>' . $row['base_stat_special_attack'] . ' pts.</p>';

        echo '<p class="px-2"><b><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-shield"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /></svg> Defensa Base: </b>' . $row['base_stat_defense'] . ' pts.</p>';
        
        echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pokemonModal" 
                data-id="' . $row['id'] . '" 
                data-name="' . $row['name'] . '" 
                data-height="' . $row['height'] . '" 
                data-weight="' . $row['weight'] . '" 
                data-image="' . $row['image_url'] . '" 
                data-types="' . htmlspecialchars($row['types']) . '" 
                data-hp="' . $row['base_stat_hp'] . '"
                data-attack="' . $row['base_stat_attack'] . '"
                data-defense="' . $row['base_stat_defense'] . '"
                data-special-attack="' . $row['base_stat_special_attack'] . '"
                data-special-defense="' . $row['base_stat_special_defense'] . '"
                data-speed="' . $row['base_stat_speed'] . '">Conoce más</button>';
        
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
                <img id="pokemonImage" src="" alt="" class="img-fluid mx-auto d-block p-4" style="max-width: 150px; border: 2px solid black; border-radius: 50%;">
                <h4 class="text-center" id="pokemonName"></h4>
                <p><b>- Altura: </b><span id="pokemonHeight"></span> decímetros.</p>
                <p><b>- Peso: </b><span id="pokemonWeight"></span> kg.</p>
                <p><b>- HP: </b><span id="pokemonHp"></span></p>
                <p><b>- Ataque Base: </b><span id="pokemonAttack"></span> pts.</p>
                <p><b>- Defensa Base: </b><span id="pokemonDefense"></span></p>
                <p><b>- Ataque Especial: </b><span id="pokemonSpecialAttack"></span> pts.</p>
                <p><b>- Defensa Especial: </b><span id="pokemonSpecialDefense"></span> pts.</p>
                <p><b>- Velocidad: </b><span id="pokemonSpeed"></span> pts.</p>
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
        var hp = button.getAttribute('data-hp');
        var attack = button.getAttribute('data-attack');
        var defense = button.getAttribute('data-defense');
        var specialAttack = button.getAttribute('data-special-attack');
        var specialDefense = button.getAttribute('data-special-defense');
        var speed = button.getAttribute('data-speed');

        var modalTitle = pokemonModal.querySelector('.modal-title');
        var pokemonImage = pokemonModal.querySelector('#pokemonImage');
        var pokemonName = pokemonModal.querySelector('#pokemonName');
        var pokemonHeight = pokemonModal.querySelector('#pokemonHeight');
        var pokemonWeight = pokemonModal.querySelector('#pokemonWeight');
        var pokemonTypes = pokemonModal.querySelector('#pokemonTypes');
        var pokemonHp = pokemonModal.querySelector('#pokemonHp');
        var pokemonAttack = pokemonModal.querySelector('#pokemonAttack');
        var pokemonDefense = pokemonModal.querySelector('#pokemonDefense');
        var pokemonSpecialAttack = pokemonModal.querySelector('#pokemonSpecialAttack');
        var pokemonSpecialDefense = pokemonModal.querySelector('#pokemonSpecialDefense');
        var pokemonSpeed = pokemonModal.querySelector('#pokemonSpeed');

        modalTitle.textContent = 'Información de ' + name;
        pokemonImage.src = image;
        pokemonName.textContent = name;
        pokemonHeight.textContent = height;
        pokemonWeight.textContent = weight;
        pokemonHp.textContent = hp;
        pokemonAttack.textContent = attack;
        pokemonDefense.textContent = defense;
        pokemonSpecialAttack.textContent = specialAttack;
        pokemonSpecialDefense.textContent = specialDefense;
        pokemonSpeed.textContent = speed;

        pokemonTypes.innerHTML = '';
        types.forEach(function(type) {
            var badgeClass = '<?php echo json_encode(getTypeBadgeClass('TYPE_PLACEHOLDER')); ?>'.replace('TYPE_PLACEHOLDER', type);
            var typeTranslated = '<?php echo json_encode(translateType('TYPE_PLACEHOLDER')); ?>'.replace('TYPE_PLACEHOLDER', type);
            pokemonTypes.innerHTML += '<span class="badge ' + badgeClass + ' rounded-pill mr-1 mx-1 px-3 py-2">' + typeTranslated + '</span>';
        });
    });
</script>
