<?php
if (!defined('SECURE_ACCESS')) {
    header("Location: ../index.php?page=er");
    exit();
}
include 'includes/header.php';

$username = "";
if (isset($_SESSION['username']) && $_SESSION['username']) {
    $username = $_SESSION['username'];
}

// Simulation des repas planifiés (exemple)
$planning = [
    "Lundi" => ["Petit-déjeuner" => "Omelette", "Déjeuner" => "Salade César", "Dîner" => "Pâtes carbonara"],
    "Mardi" => ["Petit-déjeuner" => "Pancakes", "Déjeuner" => "Poulet curry", "Dîner" => "Soupe légumes"],
    "Mercredi" => ["Petit-déjeuner" => "Smoothie", "Déjeuner" => "Steak frites", "Dîner" => "Gratin dauphinois"]
];
?>

<main>
    <div class="container py-4">
        <div class="card shadow-sm my-4 myContainer">
            <div class="card-body p-4">
                <section class="hero text-center d-flex align-items-center">
                    <div class="container">
                        <h2 class="display-4 myH3">👋 Bienvenue <span class="texteCouleur"><?= htmlspecialchars($username) ?></span> sur votre espace personnel</h2>
                    </div>
                </section>
                <section class="container py-5">
                    <h2 class="h4 text-primary mb-3">📅 Planning des repas</h2>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Jour</th>
                                    <th>Petit-déjeuner</th>
                                    <th>Déjeuner</th>
                                    <th>Dîner</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($planning as $jour => $repas) : ?>
                                    <tr>
                                        <td><strong><?= $jour ?></strong></td>
                                        <td><?= $repas["Petit-déjeuner"] ?></td>
                                        <td><?= $repas["Déjeuner"] ?></td>
                                        <td><?= $repas["Dîner"] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                </section>
                <section>
                    <h2 class="h4 text-primary mb-3">🚀 Actions rapides</h2>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="?page=recipestep1" class="btn btn-success">➕ Ajouter une recette</a>
                        <a href="?page=generer-liste-courses" class="btn btn-warning">🛒 Générer ma liste de courses</a>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>