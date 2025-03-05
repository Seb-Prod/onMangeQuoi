<footer class="site-footer">
    <div class="footer-container">
        <div class="logo-section">
            <h2><?php echo $appName ?></h2>
            <p>Planifiez vos repas en toute simplicité</p>
        </div>
        
        <div class="footer-info">
            <p>Une application web qui vous aide à planifier vos repas, gérer vos recettes et générer des listes de courses automatiquement.</p>
            <p>Simplifiez votre quotidien culinaire et gagnez du temps dans l'organisation de vos repas.</p>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> <?php echo $appName ?> - Tous droits réservés</p>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<?php if (isset($scripts)) : ?>
        <?php foreach ($scripts as $script) : ?>
            <script src="js/<?php echo $script ?>.js"></script>
        <?php endforeach ?>
    <?php endif ?>
</body>

</html>