<?php
$styles = ['home']; // Ajout d'un fichier CSS spécifique pour la page d'accueil
include 'includes/header.php';
?>

<main>
    <div class="container py-4">
        <div class="card shadow-sm my-4 myContainer">
            <div class="card-body p-4">
                <section class="hero text-center d-flex align-items-center">
                    <div class="container">
                        <h2 class="display-4 myH3">
                        <img src="./img/logo.png" alt="PHP" title="PHP" class="img-fluid" style="max-height: 60px;">
                            On Mange Quoi ?
                        </h2>
                        <p class="lead couleurTexte">Planifiez vos repas facilement et découvrez de nouvelles recettes chaque jour.</p>
                        <a href="?page=recettes" class="btn btn-lg myButton">Explorer les recettes</a>
                    </div>
                </section>
                <section class="container py-5">
                    <h2 class="text-center mb-4 myH3">Pourquoi choisir On Mange Quoi ?</h2>
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <div class="col">
                            <div class="card h-100 border-0 bg-light p-3 myCard">
                                <h3 class="h5">📅 Planification facile</h3>
                                <p>Organisez vos repas à l'avance et évitez le stress du dernier moment.</p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 border-0 bg-light p-3 myCard">
                                <h3 class="h5">🛒 Liste de courses automatique</h3>
                                <p>Générez automatiquement votre liste de courses en fonction des recettes choisies.</p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 border-0 bg-light p-3 myCard">
                                <h3 class="h5">📖 Recettes personnalisées</h3>
                                <p>Enregistrez vos recettes préférées et découvrez-en de nouvelles chaque jour.</p>
                            </div>
                        </div>
                    </div>
                </section>
                <section>
                    <div class="container text-center">
                        <h2 class="myH3">Prêt à simplifier vos repas ?</h2>
                        <p>Rejoignez-nous et commencez dès aujourd'hui !</p>
                        <a href="?page=login" class="btn myButton btn-lg">Se connecter / S'inscrire</a>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>