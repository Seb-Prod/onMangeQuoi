function addIngredient() {
  let container = document.getElementById("ingredients");

  // Create ingredient group container
  let ingredientGroup = document.createElement("div");
  ingredientGroup.classList.add("ingredient-group");

  // Add initial hr
  let initialHr = document.createElement("hr");
  ingredientGroup.appendChild(initialHr);

  // Add nom div
  let nomDiv = document.createElement("div");
  nomDiv.classList.add("input-group", "input-group-sm", "mb-3");
  nomDiv.innerHTML = `
        <span class="input-group-text fixed-width">Nom</span>
        <input type="text" class="form-control" name="ingredient_nom[]" autocomplete="off" required list="listIngredient">
    `;

  // Add quantité div
  let qtsDiv = document.createElement("div");
  qtsDiv.classList.add("input-group", "input-group-sm", "mb-3");
  qtsDiv.innerHTML = `
        <span class="input-group-text fixed-width">Qts</span>
        <input type="number" class="form-control" name="ingredient_qts[]" min="0" step="0.5" required>
        <input type="text" class="form-control" name="ingredient_unite[]" placeholder="unité" list="listUnite" autocomplete="off" required>
        <button type="button" class="btn btn-danger" onclick="removeIngredient(this)"><i class="fa-solid fa-trash-can"></i></button>
    `;

  // Append all elements
  ingredientGroup.appendChild(nomDiv);
  ingredientGroup.appendChild(qtsDiv);

  container.appendChild(ingredientGroup);
}

function removeIngredient(button) {
  // Remove the entire ingredient group
  let ingredientGroup = button.closest(".ingredient-group");
  ingredientGroup.remove();
}

function addStep() {
  let container = document.getElementById("instructions");
  let stepNumber = container.querySelectorAll(".form-floating").length + 1;

  let newStep = document.createElement("div");
  newStep.classList.add("form-floating", "mb-3", "step-group");
  newStep.id = `step-${stepNumber}`;

  newStep.innerHTML = `
    <div class="d-flex gap-2 align-items-center mb-3">
        <div class="form-floating flex-grow-1">
            <textarea 
                class="form-control" 
                placeholder="Décrivez l'étape ${stepNumber}" 
                name="etapes[]" 
                id="etape${stepNumber}" 
                rows="3" 
                required
            ></textarea>
            <label for="etape${stepNumber}">Étape n°${stepNumber}</label>
        </div>
        <button type="button" class="btn btn-danger remove-btn" onclick="removeStep(this)">
            <i class="fa-solid fa-trash-can"></i>
        </button>
    </div>
    `;

  container.appendChild(newStep);
}

function removeStep(button) {
  // Remove the entire step group
  button.closest(".step-group").remove();
  reindexSteps();
}

function reindexSteps() {
  let steps = document.querySelectorAll("#instructions .step-group");

  steps.forEach((step, index) => {
    let stepNumber = index + 1;

    step.id = `step-${stepNumber}`;

    let textarea = step.querySelector("textarea");
    let label = step.querySelector("label");

    if (textarea) {
      textarea.id = `etape${stepNumber}`;
      textarea.placeholder = `Décrivez l'étape ${stepNumber}`;
    }

    if (label) {
      label.setAttribute("for", `etape${stepNumber}`);
      label.textContent = `Étape n°${stepNumber}`;
    }
  });
}

const forms = document.querySelectorAll("form.needs-validation");

forms.forEach((form) => {
  form.addEventListener("submit", (event) => {
    if (!form.checkValidity()) {
      event.preventDefault();
      event.stopPropagation();

      // Trouvons les champs invalides
      const invalidInputs = form.querySelectorAll(":invalid");
      console.log("Champs invalides :", invalidInputs);

      invalidInputs.forEach((input) => {
        console.log("Champ invalide :", input.name, "Valeur :", input.value);
      });
    } else {
      console.log("Formulaire valide - tentative de soumission");
    }

    form.classList.add("was-validated");
  });
});
