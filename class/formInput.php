<?php
class FormInput
{
    private string $name;
    private string $label;
    private string $value;
    private string $type;
    private ?string $listName = null;
    private string $validation ="";

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
        $this->value = "";
        $this->type = "text";
    }

    public function render(): string
    {
        $list = $this->listName ? 'list = "' . $this->listName . '"' : "";
        $html = <<<HTML
        <div class="input-group mb-3">
            <span class="input-group-text myLabel">{$this->label}</span>
            <input 
                type="{$this->type}" 
                class="form-control myInput {$this->validation}"
                value="{$this->value}"
                name = "{$this->name}"
                {$list}
                autocomplete='off'
                required
            >
        </div>
        HTML;

        return $html;
    }

    public function addList(string $listName)
    {
        $this->listName = $listName;
    }

    public function setType(string $type):self{
        $this->type = $type;
        return $this;
    }

    public function setValue(string $value, bool $validation):self{
        $this->value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        
        if($validation){
            $this->validation = "is-valid";
        }else{
            $this->validation = "is-invalid";
        }
        
        return $this;
    }
}
