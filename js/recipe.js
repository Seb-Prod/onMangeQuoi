document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('ingredients-container');
    const addButton = document.getElementById('add-ingredient');
    let ingredientIndex = 1;

    // Ajouter un nouvel ingrédient
    addButton.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.innerHTML = `
            <div class="ingredient-row input-group mb-3" data-index="${ingredientIndex}">
                <input type="number" name="ingredient_qty_${ingredientIndex}" class="form-control w-sm-33" placeholder="Qts">
                <input type="text" name="ingredient_unit_${ingredientIndex}" class="form-control" placeholder="unité">
                <input type="text" name="ingredient_name_${ingredientIndex}" class="form-control w-50" placeholder="ingredient">
                <button type="button" class="btn btn-danger remove-ingredient">-</button>
            </div>
        `;
        container.appendChild(newRow.firstElementChild);
        ingredientIndex++;
    });

    // Supprimer un ingrédient
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-ingredient')) {
            // Empêcher la suppression du dernier ingrédient
            if (container.children.length > 1) {
                e.target.closest('.ingredient-row').remove();
            }
        }
    });
});