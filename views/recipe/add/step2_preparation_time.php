<?php
// Vérification de l'accès sécurisé pour empêcher l'accès direct au fichier.
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../../index.php?page=er");
    exit();
}

$styles = ['card'];
$scripts = [''];

// Inclusions des fichiers nécessaires.
include 'includes/header.php';
include 'class/formInput.php';
include 'includes/connection.php';



function inputTime($type): string
{
    $inputHeure = (new Input('heure' . $type, 'h'))->settype('number')->setMax(48)->setRequired(false);
    $inputMinute = (new Input('minute' . $type, 'min'))->settype('number')->setMax(59)->setRequired(false);

    $inputHeure->setValue("0");
$inputMinute->setValue("0");

    $html = <<<HTML
    <div class="row justify-content-center">
        <div class="col-auto">
            <div class="d-flex align-items-center">
                <div class="me-2">
                    {$inputHeure->render()}
                </div>
                <div>:</div>
                <div class="ms-2">
                    {$inputMinute->render()}
                </div>
            </div>
        </div>
    </div>
HTML;

    return $html;
}



?>
<main>
    <div class="container">
        <div class="row">
            <?php include 'views/recipe/add/recipe_card.php' ?>
            <div class="col-12 col-md-6">
                <div class="card myCard">
                    <div class="card-body">
                        <form action="controllers/recipe/add/step2.php" method="post">
                            <h5 class="myh5">Temps de préparations</h5>
                            <?php echo inputTime('preparation')?>
                            <hr>
                            <h5 class="myh5">Temps de repos</h5>
                            <?php echo inputTime('repos')?>
                            <hr>
                            <h5 class="myh5">Temps de cuisson</h5>
                            <?php echo inputTime('cuisson')?>
                            <div class="row justify-content-between">
                                <div class="col-auto">
                                </div>
                                <div class="col-auto">
                                    <input type="submit" class="btn btn-primary myButton" value="Suivant">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
// Inclusion du fichier de pied de page (footer).
include 'includes/footer.php';
?>