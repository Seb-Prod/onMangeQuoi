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
    private ?string $id = null;

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

        if ($this->id) {
            $attributes['id'] = htmlspecialchars($this->id);
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

    /**
     * Ajout de l'id à l'input principal
     *
     * @param string $id
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }
}



class FormDataList
{
    private array $datas;
    private string $name;

    public function __construct(string $name, array $datas)
    {
        $this->datas = $datas;
        $this->name = $name;
    }

    public function render(): string
    {
        $options = '';
        foreach ($this->datas as $data) {
            $options .= '<option value="' . htmlspecialchars($data) . '"></option>';
        }

        $html = <<<HTML
        <datalist id="{$this->name}">
            {$options}
        </datalist>
        HTML;

        return $html;
    }
}

class Input
{
    private string $type = "text";
    private string $value = "";
    private string $placeholder;
    private bool $required = true;
    private string $name;
    private string $id;
    private ?bool $whithButton = null;
    private ?string $listName = null;
    private string $idButton;
    private string $textButton;
    private ?string $errorMessage = null;
    private float $min = 0;
    private float $max = 59;

    public function __construct(string $name, string $placeholder)
    {
        $this->name = $name;
        $this->placeholder = $placeholder;
        $unique_id = uniqid();
        $this->id = $name . $unique_id;
    }

    public function addList(string $listName):self
    {
        $this->listName = $listName;
        return $this;
    }

    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }

    public function addButton(string $idButton, string $textButton):self
    {
        $this->whithButton = true;
        $this->idButton = $idButton;
        $this->textButton = $textButton;
        $this->id = 'input-'.$idButton;
        return $this;
    }

    public function setValue(string $value):self{
        $this->value = $value;
        return $this;
    }

    public function setErrorMessage(string $errorMessage): self {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setMin(float $min):self{
        $this->min = $min;
        return $this;
    }

    public function setMax(float $max):self{
        $this->max = $max;
        return $this;
    }

    public function setType(string $type):self{
        $this->type = $type;
        return $this;
    }

    public function render()
    {
        //Les attribues de l'input
        $attributes = [
            'class' => "form-control",
            'type' => $this->type,
            'placeholder' => $this->placeholder,
            'id' => $this->id,
            'name' => $this->name
        ];

        if ($this->listName) {
            $attributes['list'] = htmlspecialchars($this->listName);
        }

        if ($this->required) {
            $attributes['required'] = true;
        }

        if ($this->value !== null) { 
            $attributes['value'] = htmlspecialchars($this->value);
        }

        if ($this->errorMessage) {
            $attributes['class'] .= ' is-invalid';
        }

        if($this->type === 'number'){
            $attributes['min'] = $this->min;
            $attributes['max'] = $this->max;
        }

        //Convetion du tableau d'attribues en chaine de String
        $attributeString = '';
        foreach ($attributes as $key => $value) {
            if ($value === true) {
                $attributeString .= ' ' . $key;
            } else {
                $attributeString .= ' ' . $key . '="' . $value . '"';
            }
        }

        //Si ajout bouton
        $button = '';
        if ($this->whithButton) {
            $button = '<button type="button" id="button-' . $this->idButton . '" class="btn btn-primary ms-2">' . $this->textButton . '</button>';
        }

        $errorDiv = '';
        if ($this->errorMessage) {
            $errorDiv = '<div class="invalid-feedback">' . htmlspecialchars($this->errorMessage) . '</div>';
        }

        $html = <<<HTML
        <div class="d-flex align-items-center mb-3 ">
            <div class="form-floating flex-grow-1 has-validation">
                <input {$attributeString}>
                <label for="{$this->id}">{$this->placeholder}</label>
                {$errorDiv}
            </div>
            {$button}
        </div>
        HTML;

        return $html;
    }
}
