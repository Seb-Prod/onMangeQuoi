/**
 * Script de gestion dynamique des éléments de formulaire (ingrédients ou types de recettes)
 *
 * Ce script gère l'ajout et la suppression dynamique des éléments dans un formulaire.
 */

/**
 * Gère l'ajout et la suppression dynamique des éléments.
 *
 * @param {string} containerId - L'ID du conteneur des éléments.
 * @param {string} addButtonId - L'ID du bouton d'ajout.
 * @param {string} inputId - L'ID du champ de saisie.
 * @param {string} nameField - Le nom du champ caché pour le nom.
 * @param {string} qtsField - Le nom du champ caché pour la quantité (optionnel).
 * @param {string} unitField - Le nom du champ caché pour l'unité (optionnel).
 */
function setupDynamicElements(
  containerId,
  addButtonId,
  inputId,
  nameField,
  qtsField = null,
  unitField = null
) {
  const container = document.getElementById(containerId);
  const addButton = document.getElementById(addButtonId);
  const input = document.getElementById(inputId);
  const qtsInput = qtsField ? document.getElementById("qts") : null;
  const unitInput = unitField ? document.getElementById("unit") : null;

  /**
   * Crée un élément HTML avec un bouton de suppression.
   *
   * @param {string} name - Le nom de l'élément.
   * @param {string} qts - La quantité (optionnel).
   * @param {string} unit - L'unité (optionnel).
   * @returns {HTMLElement} - L'élément DOM créé.
   */
  function createTypeElement(name, qts = null, unit = null) {
    const row = document.createElement("div");
    row.classList.add("type-row");

    let content = name;
    if (qts && unit) {
      content += ` (${qts} ${unit})`;
    }

    let hiddenFields = `<input type="hidden" name="${nameField}" value="${name}">`;
    if (qtsField && unitField) {
      hiddenFields += `<input type="hidden" name="${qtsField}" value="${qts}">`;
      hiddenFields += `<input type="hidden" name="${unitField}" value="${unit}">`;
    }

    row.innerHTML = `
                <span class="btn btn-sm btn-primary position-relative green">
                    ${content}
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <button type="button" class="btn btn-danger btn-sm remove-type p-0"
                                style="font-size: 0.7rem; line-height: 1;">X</button>
                    </span>
                    ${hiddenFields}
                </span>
            `;

    return row;
  }

  /**
   * Ajoute un nouvel élément basé sur la valeur de l'input.
   */
  function addNew() {
    const name = input.value.trim();
    const qts = qtsInput ? qtsInput.value.trim() : null;
    const unit = unitInput ? unitInput.value.trim() : null;

    if (!name || (qtsInput && !qts) || (unitInput && !unit)) {
      return; // Ne rien faire si l'input est vide
    }

    const element = createTypeElement(name, qts, unit);
    container.appendChild(element);

    input.value = "";
    input.focus();
    if (qtsInput) qtsInput.value = "";
    if (unitInput) unitInput.value = "";
  }

  /**
   * Gère la suppression d'un élément.
   *
   * @param {Event} event - L'événement de clic.
   */
  function handleTypeRemoval(event) {
    if (event.target.classList.contains("remove-type")) {
      const row = event.target.closest(".type-row");

      if (row) {
        row.remove();
      }
    }
  }

  addButton.addEventListener("click", addNew);

  input.addEventListener("keypress", function (event) {
    if (event.key === "Enter") {
      event.preventDefault();
      addNew();
    }
  });

  container.addEventListener("click", handleTypeRemoval);
}
