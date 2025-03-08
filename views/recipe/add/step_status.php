<?php

function setStep(int $n, $texte, $link = "#")
{
    $step = 'step' . $n;
    if (isset($_SESSION[$step])) {
        if ($_SESSION[$step]) {
            $color = "success";
            $link = "index.php?page={$link}";
        } else {
            $color = "warning";
            $link = "index.php?page={$link}";
        }
    } else {
        $color = "secondary";
        $disabled = "";
        $link = "#"; // Force le lien à # quand disabled
    }

    $html = <<<HTML
        <a href="{$link}" class="btn step-link" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="{$texte}">
            <span class="badge rounded-pill bg-{$color}">
                {$n}
            </span>
        </a>
    HTML;

    return $html;
}

?>

<div class="col-12 mb-4">
    <div class="card myCard">
        <div class="card-body">
            <h5 class="myh5 text-center">Ajout d'une recette</h5>
            <div class="container">
                <div class="d-flex justify-content-center align-items-center">
                    <?php echo setStep(1, "Information", "recipestep1"); ?>
                    <div class="step-line"></div>
                    <?php echo setStep(2, "Temps de préparation", "recipestep2"); ?>
                    <div class="step-line"></div>
                    <?php echo setStep(3, "Liste des ingrédients", "recipestep3"); ?>
                    <div class="step-line"></div>
                    <?php echo setStep(4, "Etapes de la préparation", "recipestep4"); ?>
                    <div class="step-line"></div>
                    <?php echo setStep(5, "Validation", "recipestep5"); ?>
                </div>
            </div>
        </div>
    </div>
</div>