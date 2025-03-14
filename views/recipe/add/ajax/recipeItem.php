<?php
include __dir__ . '/../components/renderItem.php'; // Inclusion de la fonction sans bloquer l'accès

// Empêcher l'accès direct sauf en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Accès interdit";
    exit;
}

$itemName = $_POST['name'] ?? '';
$itemQts = $_POST['qts'] ?? '';
$itemUnit = $_POST['unit'] ?? '';

if (empty($itemName)) {
    echo "Erreur : Nom requis";
    echo var_dump($_POST);
    exit;
}

echo addItem($itemName, $itemQts, $itemUnit);
?>