<?php
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM trainers WHERE id = $id";
        $conn->query($sql);
        header('Location: trainers.php');
        exit();
    } else {
        $name = $_POST['name'];
        $age = $_POST['age'];
        $pokemons = isset($_POST['pokemons']) ? implode(',', $_POST['pokemons']) : '';

        if (isset($_POST['id']) && $_POST['id'] != '') {
            $id = $_POST['id'];
            $sql = "UPDATE trainers SET name = '$name', age = $age, pokemons = '$pokemons' WHERE id = $id";
        } else {
            $sql = "INSERT INTO trainers (name, age, pokemons) VALUES ('$name', $age, '$pokemons')";
        }
        $conn->query($sql);
        header('Location: trainers.php');
        exit();
    }
}

$sql = "SELECT * FROM trainers";
$result = $conn->query($sql);
$trainers = $result->fetch_all(MYSQLI_ASSOC);

$pokemonSql = "SELECT id, name FROM pokemon";
$pokemonResult = $conn->query($pokemonSql);
$pokemons = $pokemonResult->fetch_all(MYSQLI_ASSOC);

$pokemonMap = [];
foreach ($pokemons as $pokemon) {
    $pokemonMap[$pokemon['id']] = $pokemon['name'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/imgs/pokeball.png"/>
    <link rel="stylesheet" href="../assets/styles.css">
    <title>Gestión de Entrenadores - Pokedex</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="background-div"></div>
    <div class="container">
        <div class="header">
            <a href="../index.php">
                <img src="../assets/imgs/pokemon_logo.png" alt="PokemonLogo" class="w-25 img-fluid mx-auto d-block mt-4">
            </a>
        </div>

        <button class="btn btn-danger my-3" data-bs-toggle="modal" data-bs-target="#trainerModal">Crear Entrenador</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Pokémones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trainers as $trainer): ?>
                    <tr>
                        <td><?php echo $trainer['id']; ?></td>
                        <td><?php echo $trainer['name']; ?></td>
                        <td><?php echo $trainer['age']; ?></td>
                        <td>
                            <?php
                            $pokemonIds = explode(',', $trainer['pokemons']);
                            $pokemonNames = array_map(function($id) use ($pokemonMap) {
                                return $pokemonMap[$id];
                            }, $pokemonIds);
                            echo implode(', ', $pokemonNames);
                            ?>
                        </td>
                        <td class="d-flex justify-content-center px-4">
                            <button class="btn btn-warning me-4" data-bs-toggle="modal" data-bs-target="#trainerModal"
                                    data-id="<?php echo $trainer['id']; ?>"
                                    data-name="<?php echo $trainer['name']; ?>"
                                    data-age="<?php echo $trainer['age']; ?>"

                                    data-pokemons="<?php echo htmlspecialchars($trainer['pokemons']); ?>"><svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg></button>

                            <form method="POST" action="trainers.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $trainer['id']; ?>">
                                <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar este entrenador?')"><svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="trainerModal" tabindex="-1" aria-labelledby="trainerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="trainerModalLabel">Crear/Editar Entrenador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="trainerForm" method="POST" action="trainers.php">
                        <input type="hidden" name="id" id="trainerId">
                        <div class="mb-3">
                            <label for="trainerName" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="trainerName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="trainerAge" class="form-label">Edad</label>
                            <input type="number" class="form-control" id="trainerAge" name="age" required>
                        </div>
                        <div class="mb-3">
                            <label for="trainerPokemons" class="form-label">Pokémones</label>
                            <select class="form-control" id="trainerPokemons" name="pokemons[]" multiple required>
                                <?php foreach ($pokemons as $pokemon): ?>
                                    <option value="<?php echo $pokemon['id']; ?>"><?php echo $pokemon['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var trainerModal = document.getElementById('trainerModal');
        trainerModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var age = button.getAttribute('data-age');
            var pokemons = button.getAttribute('data-pokemons');

            var modalTitle = trainerModal.querySelector('.modal-title');
            var trainerId = trainerModal.querySelector('#trainerId');
            var trainerName = trainerModal.querySelector('#trainerName');
            var trainerAge = trainerModal.querySelector('#trainerAge');
            var trainerPokemons = trainerModal.querySelector('#trainerPokemons');

            if (id) {
                modalTitle.textContent = 'Editar Entrenador';
                trainerId.value = id;
                trainerName.value = name;
                trainerAge.value = age;
                
                var selectedPokemons = pokemons.split(',');
                for (var i = 0; i < trainerPokemons.options.length; i++) {
                    if (selectedPokemons.includes(trainerPokemons.options[i].value)) {
                        trainerPokemons.options[i].selected = true;
                    } else {
                        trainerPokemons.options[i].selected = false;
                    }
                }
            } else {
                modalTitle.textContent = 'Crear Entrenador';
                trainerId.value = '';
                trainerName.value = '';
                trainerAge.value = '';
                for (var i = 0; i < trainerPokemons.options.length; i++) {
                    trainerPokemons.options[i].selected = false;
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
