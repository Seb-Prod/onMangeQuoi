<?php if (isset($_SESSION['message'])): ?>
    <div class="shadow-lg card <?php echo $_SESSION['message_type'] ?? 'text-bg-info' ?> mb-3 w-100">
        <h5 class="card-header"><?php echo $_SESSION['message'] ?></h5>
    </div>
    <?php 
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
    ?>
<?php endif; ?>