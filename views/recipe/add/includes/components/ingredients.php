<?php
function renderIngredients(array $ingredients, callable $renderItemFunction): string
{
    $html = '';
    if (!empty($ingredients) && is_array($ingredients)) {
        try {
            foreach ($ingredients as $ingredient) {
                $html .= $renderItemFunction($ingredient);
            }
            return $html;
        } catch (InvalidArgumentException $e) {
            return '<div class="error-ingredients">Erreur lors du chargement des ingrédients.</div>';
        }
    } else {
        return '';
    }
}

/**
 * Génère le HTML pour une ingrédient
 *
 * @param array $ingredient
 * @return string HTML générer
 */
function generateIngredientItemPreviewHtml(array $ingredient): string
{
    if (array_key_exists('qts', $ingredient) &&
        array_key_exists('unit', $ingredient) &&
        array_key_exists('ingredient', $ingredient)) {

        return <<<HTML
        <div class="type-row">
            <span>{$ingredient['qts']}</span>
            <span>{$ingredient['unit']}</span>
            <span>{$ingredient['ingredient']}</span>
        </div>
        HTML;
    } else {
        return '<div class="type-row">Données d\'ingrédient incomplètes.</div>';
    }
}

/**
 * Génère le HTML pour un ingrédient à partir d'un tableau
 *
 * @param array $ingredient Tableau contenant les données de l'ingrédient (qts, unit, ingredient)
 * @return string HTML généré
 */
function generateIngredientItemHTML(array $ingredient): string
{
    if (isset($ingredient['qts'], $ingredient['unit'], $ingredient['ingredient'])) {
        $qts = $ingredient['qts'];
        $unit = $ingredient['unit'];
        $name = $ingredient['ingredient'];

        return <<<HTML
        <div class="type-row">
            <span class="btn btn-sm btn-primary position-relative green">
                {$name} ({$qts} {$unit})
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <button type="button" class="btn btn-danger btn-sm remove-type p-0" style="font-size: 0.7rem; line-height: 1;">
                        X
                    </button>
                </span>
                <input type="hidden" name="ingredient[]" value="{$name}">
                <input type="hidden" name="qts[]" value="{$qts}">
                <input type="hidden" name="unit[]" value="{$unit}">
            </span>
        </div>
        HTML;
    } else {
        return '<div class="type-row">Données d\'ingrédient incomplètes.</div>';
    }
}

