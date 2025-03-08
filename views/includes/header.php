<?php
if (isset($_SESSION['level']) && $_SESSION['level'] != '') {
    $level = $_SESSION['level'];
} else {
    $level = 0;
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Menu Zen est une application web permettant de planifier ses repas, ajouter des recettes et générer une liste de courses automatiquement.">
    <meta name="keywords" content="planificateur de repas, recettes, liste de courses, PHP, MySQL, Bootstrap, application web">
    <meta name="author" content="Sébastien Drillaud (Seb-Prod)">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Menu Zen - Planificateur de Repas">
    <meta property="og:description" content="Simplifiez votre organisation culinaire avec Menu Zen. Ajoutez des recettes, planifiez vos repas et générez automatiquement votre liste de courses !">
    <!-- <meta property="og:image" content="URL_DU_LOGO">
    <meta property="og:url" content="http://localhost/onmangequoi"> -->
    <meta property="og:type" content="website">
    <title><?php echo $appName ?></title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="apple-touch-icon" href="img/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <?php if (isset($styles)) : ?>
        <?php foreach ($styles as $style) : ?>
            <link rel="stylesheet" href="css/<?php echo $style ?>.css">
        <?php endforeach ?>
    <?php endif ?>
</head>

<body>

    <header>
        <!-- MOBILE SCREEN -->
        <input type="checkbox" id="menu">
        <!-- END MOBILE SCREEN -->
        <div class="logo">
            <img src="img/logo.png" alt="" class="logoImg">
            <h1>
                <span class="letter"><?php echo $appName ?></span>
            </h1>
        </div>
        <!-- MOBILE SCREEN -->
        <label for="menu">
            <span></span>
            <span></span>
            <span></span>
        </label>
        <!-- END MOBILE SCREEN -->
        <nav>
            <?php foreach ($routes as $key => $item) : ?>
                <?php if (isset($item['levels']) && in_array($level, $item['levels'])) : ?>
                    <?php $class = ($key === $route) ? "ok" : "" ?>
                    <a href="?page=<?php echo $key ?>" class="<?php echo $class ?>"><?php echo $item['label'] ?></a>
                <?php endif ?>
            <?php endforeach ?>
        </nav>
    </header>