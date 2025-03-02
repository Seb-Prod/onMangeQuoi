<?php
if (!defined('SECURE_ACCESS')) {
    header("Location: ../index.php?page=er");
    exit();
}
$styles = ['about'];
include 'includes/header.php'
?>
<main>
    <div class="container py-4">
        <div class="card shadow-sm my-4 myContainer">
            <div class="card-body p-4">
                <!-- Section Introduction -->
                <article class="mb-4">
                    <h3 class="display-5 text-center mb-3 myH3">
                    <img src="./img/logo.png" alt="PHP" title="PHP" class="img-fluid" style="max-height: 60px;">
                         On Mange Quoi ?</h3>
                    <h3 class="h4 text-primary mb-3 myH3">Notre mission</h3>
                    <p class="lead">On Mange Quoi ? est né d'une question simple que chacun se pose régulièrement : "Qu'est-ce qu'on mange aujourd'hui ?". Notre application vise à simplifier votre quotidien en vous aidant à planifier vos repas, organiser vos recettes et gérer efficacement vos courses.</p>
                </article>

                <!-- Section Qui sommes-nous -->
                <article class="mb-4">
                    <h3 class="h4 text-primary mb-3 myH3">Qui sommes-nous ?</h3>
                    <p>“On Mange Quoi ?” est un projet personnel développé par (<a href="https://github.com/Seb-Prod"  target="_blank">Seb-Prod</a>) dans le cadre de ma reconversion professionnelle à la <a href="https://codingfactory.fr/" target="_blank">Coding Factory</a>. Sur mon temps personnel, je conçois cette application avec l’objectif de simplifier la planification des repas, la gestion des recettes et des courses. Ce projet me permet d’allier ma passion pour le développement et mon envie de créer un outil pratique et intuitif pour le quotidien.</p>
                </article>

                <!-- Section Avantages -->
                <article class="mb-4">
                    <h2 class="h3 text-center mb-4 myH3">Pourquoi utiliser On Mange Quoi ?</h2>

                    <div class="row row-cols-1 row-cols-md-2 g-4 mb-3">
                        <div class="col">
                            <div class="card h-100 border-0 bg-light myCard">
                                <div class="card-body">
                                    <h3 class="h5 card-title">🍽️ Fini le casse-tête des repas</h3>
                                    <p class="card-text">Plus besoin de vous demander quoi cuisiner chaque jour. Planifiez vos repas à l'avance et variez facilement vos menus.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="card h-100 border-0 bg-light myCard">
                                <div class="card-body">
                                    <h3 class="h5 card-title">📝 Organisez vos recettes</h3>
                                    <p class="card-text">Créez, modifiez et consultez votre collection personnelle de recettes en quelques clics.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="card h-100 border-0 bg-light myCard">
                                <div class="card-body">
                                    <h3 class="h5 card-title">🛒 Courses simplifiées</h3>
                                    <p class="card-text">Générez automatiquement votre liste de courses en fonction des repas planifiés, et ne manquez plus jamais d'ingrédients.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="card h-100 border-0 bg-light myCard">
                                <div class="card-body">
                                    <h3 class="h5 card-title">💰 Économisez temps et argent</h3>
                                    <p class="card-text">En planifiant vos repas et vos courses, réduisez le gaspillage alimentaire et évitez les achats impulsifs.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 bg-light mb-3 myCard">
                        <div class="card-body">
                            <h3 class="h5 card-title">📱 Accessible partout</h3>
                            <p class="card-text">Interface responsive accessible sur tous vos appareils : ordinateur, tablette ou smartphone.</p>
                        </div>
                    </div>
                </article>

                <!-- Section Technologies -->
                <article class="mb-4">
                    <h3 class="h4 text-primary mb-3 myH3">Technologies</h3>
                    <p>Notre application s'appuie sur des technologies fiables et performantes :</p>

                    <div class="d-flex justify-content-center align-items-center flex-wrap gap-4 my-4">
                        <img src="./img/logo_php.png" alt="PHP" title="PHP" class="img-fluid" style="max-height: 60px;">
                        <img src="./img/logo_mysql.png" alt="MySQL" title="MySQL" class="img-fluid" style="max-height: 60px;">
                        <img src="./img/logo_web.png" alt="HTML/CSS/JS" title="Technologies Web" class="img-fluid" style="max-height: 60px;">
                        <img src="./img/logo_bootstrap.png" alt="Bootstrap" title="Bootstrap" class="img-fluid" style="max-height: 60px;">
                    </div>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent myLi">PHP pour le développement backend</li>
                        <li class="list-group-item bg-transparent myLi">MySQL pour la gestion des données</li>
                        <li class="list-group-item bg-transparent myLi">HTML, CSS et JavaScript pour l'interface utilisateur</li>
                        <li class="list-group-item bg-transparent myLi">Bootstrap pour un design responsive et moderne</li>
                    </ul>
                </article>

                <!-- Section Confidentialité -->
                <article class="mb-4">
                    <h3 class="h4 text-primary mb-3 myH3">Confidentialité et données personnelles</h3>
                    <div class="alert alert-info myInfo">
                        <p class="mb-0">
                            Nous accordons une importance capitale à la protection de vos données. Les informations collectées se limitent à celles nécessaires au bon fonctionnement de l'application et ne sont jamais partagées avec des tiers.
                        </p>
                    </div>
                </article>

                <!-- Section Contact -->
                <article class="mb-4">
                    <h3 class="h4 text-primary mb-3 myH3">Contact et support</h3>
                    <p>
                        Pour toute question, suggestion ou signalement de bug, n'hésitez pas à contacter le développeur :
                    </p>
                    <div class="d-flex gap-3 align-items-center">
                        <a href="https://github.com/Seb-Prod" target="_blank" class="d-inline-flex align-items-center ">
                            <img src="./img/logo_github.png" alt="GitHub" title="GitHub" width="30" height="30" class="me-2">
                            Seb-Prod
                        </a>
                        <a href="https://www.linkedin.com/in/s%C3%A9bastien-drillaud-b68b3318a/" target="_blank" class="d-inline-flex align-items-center">
                            <img src="./img/logo_linkedin.png" alt="LinkedIn" title="LinkedIn" width="30" height="30" class="me-2">
                            Sébastien Drillaud
                        </a>
                    </div>
                </article>

                <!-- Section Licence -->
                <article>
                    <h3 class="h4 text-primary mb-3 myH3">Licence</h3>
                    <p>On Mange Quoi ? est un projet sous licence MIT, ce qui signifie que vous êtes libre de l'utiliser, de le modifier et de le distribuer selon les termes de cette licence.</p>
                </article>

                <hr class="my-4">
                <p class="text-center text-muted"><em>Version actuelle : En développement (2025)</em></p>
            </div>
        </div>
    </div>

</main>
<?php include 'includes/footer.php'; ?>