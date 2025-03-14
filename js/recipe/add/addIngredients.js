function setupDynamicElements(containerId, addButtonId, inputId, qtsId, unitId) {
    const container = document.getElementById(containerId);
    const addButton = document.getElementById(addButtonId);
    const input = document.getElementById(inputId);
    const inputQts = document.getElementById(qtsId);
    const inputUnit = document.getElementById(unitId);
  
    /**
     * Ajoute un nouvel élément en appelant PHP via AJAX.
     */
    function addNew() {
      const name = input.value.trim();
      const qts = inputQts.value.trim();
      const unit = inputUnit.value.trim();
  
      if (!name) {
        return; // Ne rien faire si un champ est vide
      }
      removeMessage();
      // Envoi des données à PHP pour obtenir le HTML
      fetch("views/recipe/add/ajax/recipeItem.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          name: name,
          qts:qts,
          unit:unit,
        }),
      })
        .then((response) => response.text())
        .then((html) => {
          container.insertAdjacentHTML("beforeend", html);
  
          // Réinitialiser les champs
          input.value = "";
          inputQts.value = "";
          inputUnit.value ="";
          input.focus();
        })
        .catch((error) => console.error("Erreur AJAX :", error));
    }
  
    function removeMessage() {
      const element = document.getElementById("emptyItem");
      if (element) {
        element.remove();
      }
    }
  
    /**
     * Gère la suppression d'un élément.
     */
    function handleTypeRemoval(event) {
      // Vérifier si l'élément cliqué est l'icône ou un parent avec la classe "remove"
      const removeButton = event.target.closest(".remove");
      if (removeButton) {
        const row = removeButton.closest(".item-row");
        if (row) {
          row.remove();
        }
        const nombreElements = container.children.length;
        if (nombreElements === 0 && !document.getElementById("emptyItem")) {
          container.innerHTML = `<span id="emptyItem">Liste vide</span>`;
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
  
  document.addEventListener("DOMContentLoaded", function () {
    setupDynamicElements("recipeIngredients", "button-ingredient", "input-ingredient", "input-qts", "input-unit");
  });
  