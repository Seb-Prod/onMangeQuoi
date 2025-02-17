<?php
session_start();
$titrePage = isset($titrePage) ? $titrePage : 'Seb-Prod';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titrePage ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a href="index.php" class="navbar-brand">On Monge Quoi ?</a>
                <button
                    class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div
                    class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb2 mb-lg-0">
                        <li class="nav-item">
                            <a href="logIn.php" class="nav-link">LogIn
                            <i class="fa-solid fa-right-to-bracket"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">LogOut
                            <i class="fa-solid fa-right-from-bracket"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="container py-4">