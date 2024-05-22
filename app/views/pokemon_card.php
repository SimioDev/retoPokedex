<div class="card mb-4 mx-auto" style="width: 16rem;">

    <img src="<?php echo $pokemon['details']['sprites']['front_default']; ?>" class="w-50 mx-auto card-img-top img-fluid col-6 col-md-4" alt="<?php echo $pokemon['name']; ?>">

    <div class="card-body">

        <h5 class="card-title"><?php echo ucfirst($pokemon['name']); ?></h5>
        <p class="card-text">ID: <?php printf($pokemon['details']['id']); ?></p>
        <p><strong>Tipos:</strong></p>
        <ul>
            <?php foreach ($pokemon['details']['types'] as $type): ?>
                <li><?php printf(ucfirst($type['type']['name'])); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

</div>
