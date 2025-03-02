<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Site</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
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
            <h1>
                <span class="letter">O</span>n
                <span class="letter">M</span>ange
                <span class="letter">Q</span>uoi
                <span class="letter">?</span>
            </h1>
        </div>
        <!-- MOBILE SCREEN -->
        <label for="menu">
            <div></div>
            <div></div>
            <div></div>
        </label>
        <!-- END MOBILE SCREEN -->
        <nav>
            <?php foreach ($routes as $key => $item) : ?>
                <?php if (isset($item['levels']) && in_array($_SESSION['level'], $item['levels'])) : ?>
                    <a href="?page=<?php echo $key ?>"><?php echo $item['label'] ?></a>
                <?php endif ?>
            <?php endforeach ?>
        </nav>
    </header>