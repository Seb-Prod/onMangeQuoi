<?php
if (!defined('SECURE_ACCESS')) {
    header('Location: ../index.php');
    exit();
}
?>
<div class="d-flex flex-column align-items-center">
    <div class="shadow-lg card text-center mb-3 position-relative">
        <div class="position-absolute top-0 start-50 translate-middle">
            <img src="https://media.licdn.com/dms/image/v2/D4D35AQEMEgDGOfPN3w/profile-framedphoto-shrink_400_400/B4DZUpxKAzGcAc-/0/1740162507155?e=1740826800&v=beta&t=XleOgHlCLAEKyqqK_cKl2AJWNTRIsxkjgQawVeNS8TM" 
                 class="rounded-circle"
                 alt="Photo de profil linkedin">
        </div>
        <div class="card-body mt-4">
            <h5 class="card-title">Sébastien Drillaud</h5>
            <h6 class="card-title">Seb-Prod</h6>
            <div class="d-flex justify-content-center gap-3 mt-2">
                <a href="https://github.com/Seb-Prod" class="nav-link fs-3">
                    <i class="fa-brands fa-github"></i>
                </a>
                <a href="https://www.linkedin.com/in/sébastien-drillaud-b68b3318a/" class="nav-link fs-3">
                    <i class="fa-brands fa-linkedin"></i>
                </a>
            </div>
        </div>
    </div>
</div>