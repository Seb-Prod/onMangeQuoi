<?php
class RecipeCard {
    private $recipe;
    private $index;

    public function __construct(array $recipe, int $index) {
        $this->recipe = $recipe;
        $this->index = $index;
    }

    private function renderAccordionSection(string $id, string $icon, string $title, string $content): string {
        return <<<HTML
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#{$id}-{$this->index}" 
                        aria-expanded="false">
                    <i class="fa-solid {$icon}"></i> {$title}
                </button>
            </h2>
            <div id="{$id}-{$this->index}" 
                 class="accordion-collapse collapse" 
                 data-bs-parent="#accordion-{$this->index}">
                <div class="accordion-body">
                    {$content}
                </div>
            </div>
        </div>
        HTML;
    }

    private function renderTimeSection(): string {
        $content = <<<HTML
        <ul class="list-unstyled">
            <li>Préparation : {$this->recipe['temps_preparation']} min</li>
            <li>Repos : {$this->recipe['temps_repos']} min</li>
            <li>Cuisson : {$this->recipe['temps_cuisson']} min</li>
        </ul>
        HTML;

        return $this->renderAccordionSection(
            'temps',
            'fa-clock',
            "Temps total : {$this->recipe['temps_total']} min.",
            $content
        );
    }

    private function renderIngredientsSection(): string {
        $ingredients = array_map(function($ingredient) {
            return sprintf(
                '<li>%s %s de %s</li>',
                htmlspecialchars($ingredient['quantite']),
                htmlspecialchars($ingredient['unite']),
                htmlspecialchars($ingredient['ingredient_nom'])
            );
        }, $this->recipe['ingredients']);

        $content = '<ul class="list-unstyled">' . implode('', $ingredients) . '</ul>';

        return $this->renderAccordionSection(
            'ingredients',
            'fa-carrot',
            'Ingrédients',
            $content
        );
    }

    private function renderInstructionsSection(): string {
        $steps = array_map(function($etape) {
            return sprintf(
                '<li class="list-group-item">%s</li>',
                htmlspecialchars($etape)
            );
        }, $this->recipe['etapes']);

        $content = '<ol class="list-group list-group-numbered">' . implode('', $steps) . '</ol>';

        return $this->renderAccordionSection(
            'etapes',
            'fa-list-ol',
            'Instructions',
            $content
        );
    }

    public function render(): string {
        $nom = htmlspecialchars($this->recipe['nom']);
        $auteur = htmlspecialchars($this->recipe['auteur']);
        $note = number_format($this->recipe['note'], 1);
        $portion = $this->recipe['portion'];
        $pluriel = $portion > 1 ? 's' : '';

        return <<<HTML
        <div class="col-auto">
            <div class="shadow-sm card text-bg-light">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{$nom}</h5>
                        <span class="badge bg-primary">{$note}/5</span>
                    </div>
                    <small class="text-muted">Par {$auteur}</small>
                </div>
                <div class="card-body">
                    <div class="accordion" id="accordion-{$this->index}">
                        {$this->renderTimeSection()}
                        {$this->renderIngredientsSection()}
                        {$this->renderInstructionsSection()}
                    </div>
                </div>
                <div class="card-footer text-muted">
                    Pour {$portion} personne{$pluriel}
                </div>
            </div>
        </div>
        HTML;
    }
}