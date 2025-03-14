<?php
function addItem($item, $qts = '', $unit = ''): string
{
    $content = htmlspecialchars($item);

    if(!empty($qts) && !empty($unit)){
        $content = htmlspecialchars($qts . ' ' . $unit . ' ' . $item);
    }

    $hiddenFields = '<input type="hidden" name="item[]" value="' . htmlspecialchars($item) . '">';

    if (!empty($qts)) { // Vérifie si $qts a une valeur
        $hiddenFields .= '<input type="hidden" name="itemQts[]" value="' . htmlspecialchars($qts) . '">';
    }
    if (!empty($unit)) { // Vérifie si $qts a une valeur
        $hiddenFields .= '<input type="hidden" name="itemUnit[]" value="' . htmlspecialchars($unit) . '">';
    }


    return <<<HTML
        <div class="item-row">
            <span class="btn btn-sm position-relative badgeBtn">
                {$content}
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill">
                    <button type="button" class="btn btn-sm remove p-0" style="font-size: 0.7rem; line-height: 1;">
                    <i class="fas fa-trash-alt"></i>
                    </button>
                </span>
                {$hiddenFields}
            </span>
        </div>
    HTML;
}

function addIngredient(array $ingredient):string{
    $qts = $ingredient['qts'];
    $unit = $ingredient['unit'];
    $name = $ingredient['ingredient'];
    return addItem($name, $qts, $unit);
}
