<?php
include __dir__ . '/../components/renderItem.php'; // Inclusion de la fonction sans bloquer l'accès

// Empêcher l'accès direct sauf en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Accès interdit";
    exit;
}

$type = $_POST['type'] ?? '';

if (empty($type)) {
    echo "Erreur : Nom requis";
    exit;
}

echo addItem($type);
?>