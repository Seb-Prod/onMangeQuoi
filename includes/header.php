<?php
// includes/config.php
define('SITE_TITLE', 'On Mange Quoi ?');

// includes/header.php
session_start();

class Navigation {
    private bool $isConnected;
    private bool $isAdmin;
    private string $pageTitle;
    private string $css;

    public function __construct(string $pageTitle = SITE_TITLE, string $css="") {
        $this->isConnected = isset($_SESSION['pseudo']);
        $this->isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === 1;
        $this->pageTitle = $pageTitle;
        $this->css = $css;
    }

    private function getNavItems(): array {
        $items = [];

        if (!$this->isConnected) {
            $items[] = [
                'url' => 'login',
                'icon' => 'fa-right-to-bracket',
                'text' => 'LogIn'
            ];
        } else {
            $items[] = [
                'url' => 'recettes',
                'icon' => 'fa-plus',
                'text' => 'Ajouter une recette'
            ];
            $items[] = [
                'url' => 'logout',
                'icon' => 'fa-solid fa-right-from-bracket',
                'text' => 'Déconnexion'
            ];
        }

        if ($this->isAdmin) {
            $items[] = [
                'url' => 'admin',
                'icon' => 'fa-gear',
                'text' => 'Admin'
            ];
        }

        $items[] = [
            'url' => 'contact',
            'icon' => 'fa-circle-info',
            'text' => 'Informations'
        ];

        return $items;
    }

    private function renderNavItem(array $item): string {
        return sprintf(
            '<li class="nav-item">
                <a href="index.php?page=%s" class="nav-link">
                    <i class="fa-solid %s"></i>
                    %s
                </a>
            </li>',
            $item['url'],
            $item['icon'],
            $item['text']
        );
    }

    public function render(): void {
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= htmlspecialchars($this->pageTitle) ?></title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
            <link rel="stylesheet" href="css/style.css">
            <?php if($this->css) : ?>
                <link rel="stylesheet" href="css/<?php echo $this->css ?>.css">
            <?php endif ?>
        </head>
        <body>
            <header>
                <nav class="navbar navbar-expand-lg bg-body-tertiary">
                    <div class="container-fluid">
                        <a href="index.php" class="navbar-brand"><?= SITE_TITLE ?></a>
                        <button 
                            class="navbar-toggler" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent" 
                            aria-expanded="false" 
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb2 mb-lg-0">
                                <?php
                                foreach ($this->getNavItems() as $item) {
                                    echo $this->renderNavItem($item);
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
            <main class="container py-4">
        <?php
    }
}
?>