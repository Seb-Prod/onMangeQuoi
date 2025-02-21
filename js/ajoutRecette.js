function addIngredient() {
  let container = document.getElementById("ingredients");

  // Créer le conteneur pour l'ingrédient
  let ingredientContainer = document.createElement("div");
  ingredientContainer.classList.add("ingredient-container");

  // Ajouter le nom
  let nomDiv = document.createElement("div");
  nomDiv.classList.add("input-group", "input-group-sm", "mb-3");
  nomDiv.innerHTML = `
      <span class="input-group-text fixed-width">Nom</span>
      <input 
          type="text" 
          class="form-control" 
          name="ingredient_nom[]"
          autocomplete="off"
          required
          list="listIngredient">
      <button type="button" class="btn btn-danger d-flex align-items-center" onclick="removeIngredient(this)">
          <i class="fa-solid fa-trash-can"></i>
      </button>
  `;

  // Ajouter les quantités
  let qtsDiv = document.createElement("div");
  qtsDiv.classList.add("input-group", "input-group-sm", "mb-3");
  qtsDiv.innerHTML = `
      <span class="input-group-text fixed-width">Quantité</span>
      <input 
          type="number" 
          class="form-control"
          name="ingredient_qts[]" 
          min="1"
          value=""
          required
          step="0.5">
      <input 
          type="text" 
          class="form-control" 
          autocomplete="off"
          name="ingredient_unite[]" 
          required
          placeholder="unité" 
          list="listUnite">
  `;

  ingredientContainer.appendChild(nomDiv);
  ingredientContainer.appendChild(qtsDiv);

  // Ajouter un séparateur visuel
  let hr = document.createElement("hr");
  ingredientContainer.appendChild(hr);

  container.appendChild(ingredientContainer);
}

function removeIngredient(button) {
  // Remonter jusqu'au conteneur parent et le supprimer
  let container = button.closest(".ingredient-container");
  container.remove();
}

function addStep() {
  let container = document.getElementById("instructions");
  let stepNumber =
    container.getElementsByClassName("step-container").length + 1;

  let stepContainer = document.createElement("div");
  stepContainer.classList.add("step-container");

  stepContainer.innerHTML = `
      <div class="form-floating mb-3">
          <div class="d-flex gap-2 align-items-start">
              <div class="flex-grow-1 form-floating">
                  <textarea 
                      class="form-control" 
                      name="etapes[]" 
                      required
                      id="etape${stepNumber}"
                      style="height: 100px"
                      placeholder="Décrivez l'étape ici"></textarea>
                  <label for="etape${stepNumber}">Étape n°${stepNumber}</label>
              </div>
              <button type="button" class="btn btn-danger align-self-stretch" onclick="removeStep(this)">
                  <i class="fa-solid fa-trash-can"></i>
              </button>
          </div>
      </div>
  `;

  container.appendChild(stepContainer);
}

function removeStep(button) {
  let stepContainer = button.closest(".step-container");
  stepContainer.remove();
  reindexSteps();
}

function reindexSteps() {
  let steps = document.getElementsByClassName("step-container");

  Array.from(steps).forEach((step, index) => {
    let stepNumber = index + 1;
    let textarea = step.querySelector("textarea");
    let label = step.querySelector("label");

    textarea.id = `etape${stepNumber}`;
    label.setAttribute("for", `etape${stepNumber}`);
    label.textContent = `Étape n°${stepNumber}`;
  });
}

function hideMessageCard() {
  const messageCard = document.querySelector(
    ".card.text-bg-success, .card.text-bg-danger"
  );
  if (messageCard) {
    messageCard.style.display = "none";
  }
}

// Validation du formulaire
document.querySelectorAll("form.needs-validation").forEach((form) => {
  form.addEventListener("submit", (event) => {
    if (!form.checkValidity()) {
      event.preventDefault();
      event.stopPropagation();

      // Log des champs invalides pour le débogage
      const invalidInputs = form.querySelectorAll(":invalid");
      invalidInputs.forEach((input) => {
        console.log("Champ invalide:", input.name, "Valeur:", input.value);
      });
      hideMessageCard();
    }

    form.classList.add("was-validated");
  });
});
