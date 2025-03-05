<?php
class FormInput
{
    private string $name;
    private string $label;
    private string $value = "";
    private string $type = "text";
    private ?string $listName = null;
    private string $validation = "";
    private ?string $unitLabel = null;
    private bool $required = true;
    private ?int $min = null;
    private ?int $max = null;
    private ?float $step = null;
    private bool $whithLabel = true;

    /**
     * Crée un nouveau champ de formulaire
     *
     * @param string $name      Le nom du champ
     * @param string $label     Le texte du label
     */
    public function __construct(string $name, string $label)
    {
        $this->name = $name;
        $this->label = $label;
    }

    /**
     * Rendu du champ de formulaire.
     *
     * @return string Le code HTML du champ de formulaire.
     */
    public function render(): string
    {
        $attributes = [
            'type' => $this->type,
            'class' => 'form-control myInput' . $this->validation,
            'value' => htmlspecialchars($this->value),
            'name' => htmlspecialchars($this->name),
            'autocomplete' => 'off',
        ];

        if ($this->listName) {
            $attributes['list'] = htmlspecialchars($this->listName);
        }

        if ($this->required) {
            $attributes['required'] = true;
        }

        if ($this->min !== null && in_array($this->type, ['number', 'date'])) {
            $attributes['min'] = $this->min;
        }

        if ($this->max !== null && in_array($this->type, ['number', 'date'])) {
            $attributes['max'] = $this->max;
        }

        if ($this->step !== null && in_array($this->type, ['number', 'date'])) {
            $attributes['step'] = $this->step;
        }

        $attributeString = '';
        foreach ($attributes as $key => $value) {
            if ($value === true) {
                $attributeString .= ' ' . $key;
            } else {
                $attributeString .= ' ' . $key . '="' . $value . '"';
            }
        }

        $label = '';
        if ($this->whithLabel) {
            $label = '<span class="input-group-text myLabel">' . htmlspecialchars($this->label) . '</span>';
        }

        $unitLabel = '';
        if ($this->unitLabel) {
            $unitLabel = '<span class="input-group-text myLabel">' . htmlspecialchars($this->unitLabel) . '</span>';
        }

        $html = <<<HTML
        <div class="input-group mb-3">
            {$label}
            <input {$attributeString}>
            {$unitLabel}
        </div>
        HTML;

        return $html;
    }

    /**
     * Ajoute une liste de données pour le champ (datalist).
     *
     * @param string $listName Le nom de la liste.
     *
     * @return self
     */
    public function addList(string $listName): self
    {
        $this->listName = $listName;
        return $this;
    }

    /**
     * Définit le type du champ.
     *
     * @param string $type Le type du champ.
     *
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Ajoute une unité (label) après le champ.
     *
     * @param string $label L'unité à ajouter.
     *
     * @return self
     */
    public function addLabelUnit(String $label): self
    {
        $this->unitLabel = $label;
        return $this;
    }

    /**
     * Définit la valeur du champ et sa validation.
     *
     * @param string $value     La valeur du champ.
     * @param bool   $isValid   Indique si la valeur est valide.
     *
     * @return self
     */
    public function setValue(string $value, bool $isValid = true): self
    {
        $this->value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        $this->validation = $isValid ? "is-valid" : "is-invalid";
        return $this;
    }

    /**
     * Définit une valeur min
     */
    public function setMin(int $value): self
    {
        $this->min = $value;
        return $this;
    }

    /**
     * Définit une valeur max
     */
    public function setMax(int $value): self
    {
        $this->max = $value;
        return $this;
    }

    /**
     * Définit un step (incrémentation/décrenmation)
     */
    public function setStep(int $value): self
    {
        $this->step = $value;
        return $this;
    }

    /**
     * Enleve le label
     */
    public function removeLabel(): self
    {
        $this->whithLabel = false;
        return $this;
    }

    /**
     * Permet de définir si le champ est requis.
     *
     * @param bool $required
     *
     * @return $this
     */
    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }
}

class TripleInput
{

    public function __construct() {}



    public function render(): string
    {
        $html = <<<HTML
        <div class="input-group mb-3">
            <input type="number" class="form-control w-20" placeholder="Qts">
            <input type="text" class="form-control w-25" placeholder="unité">
            <input type="text" class="form-control w-50" placeholder="ingredient">
        </div>
        HTML;

        return $html;
    }
}
