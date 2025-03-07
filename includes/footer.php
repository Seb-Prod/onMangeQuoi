<footer class="site-footer">
        <p>&copy; <?php echo date('Y'); ?> <?php echo $appName ?> - Tous droits réservés</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<?php if (isset($scripts)) : ?>
        <?php foreach ($scripts as $script) : ?>
            <script src="js/<?php echo $script ?>.js"></script>
        <?php endforeach ?>
    <?php endif ?>
</body>

</html>