<?php 
include 'includes/header.php';
$errors = [
    '500' => [
        'titre' => 'Oups ! Erreur interne du serveur',
        'texte' => "Une erreur inattendue s'est produite. Veuillez réessayer plus tard."
    ],
    '404' => [
        'titre' => 'Oups ! Page non trouvée',
        'texte' => "La page que vous recherchez n'existe pas."
    ],
    '403' => [
        'titre' => 'Oups ! Accès interdit',
        'texte' => "Vous n'avez pas les autorisations nécessaires pour accéder à cette page."
    ],
];

if (isset($_SESSION['code_erreur'])) {
    $code_erreur = $_SESSION['code_erreur'];
    unset($_SESSION['code_erreur']);
} else {
    $code_erreur = '404';
}


?>
<main class="d-flex align-items-center justify-content-center vh-100 text-center">
    <div class="container">
        <h1 class="display-1 text-danger"><?php echo $code_erreur ?></h1>
        <h2 class="h4 text-muted"><?php echo $errors[$code_erreur]['titre'] ?></h2>
        <p class="lead"><?php echo $errors[$code_erreur]['texte'] ?></p>
        <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>
    </div>
</main>
<?php include 'includes/footer.php'; ?>