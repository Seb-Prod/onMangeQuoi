<?php
if (!defined('SECURE_ACCESS')) {
    header("Location: ../../index.php?page=er");
    exit();
}
$styles = ['card'];
include 'includes/header.php';

?>
<main>
    <h1>Template</h1>
</main>
<?php include 'includes/footer.php'; ?>