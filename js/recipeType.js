document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('recipeTypes');
    const addButton = document.getElementById('button-type');
    const inputValue = document.getElementById('input-type');
    let ingredientIndex = container.querySelectorAll('.type-row').length + 1;


    // Ajouter un nouvel ingrédient
    addButton.addEventListener('click', function() {
        console.log("oj")
        const typeValue = inputValue.value; // Obtenir la valeur du champ de saisie
        if(typeValue){
            const newRow = document.createElement('div');
            newRow.classList.add('type-row'); // Ajouter une classe pour identifier la ligne
            newRow.innerHTML = `
                <span class="btn btn-sm btn-primary position-relative green">
                    ${typeValue}
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <button type="button" class="btn btn-danger btn-sm remove-type p-0" style="font-size: 0.7rem; line-height: 1;">X</button>
                    </span>
                    <input type="hidden" name="types[]" value="${typeValue}">
                </span>
            `;
            inputValue.value="";
            container.appendChild(newRow);
            ingredientIndex++;
        }
        
    });

    // Délégation d'événements pour la suppression
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-type')) {
            e.target.closest('.type-row').remove();
        }
    });
});