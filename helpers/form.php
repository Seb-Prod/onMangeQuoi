<?php
function generateInput($label, $name, $type = "text", $defaultValue = "")
{
    $value = $_SESSION['form_data'][$name] ?? $defaultValue;
    $html = '<div class="input-group input-group-sm mb-3">';
    $html .= '<span class="input-group-text" id="inputGroup-' . htmlspecialchars($name) . '">'
        . htmlspecialchars($label) . '</span>';
    $html .= '<input type="' . htmlspecialchars($type) . '"';
    $html .= ' class="form-control'
        . (isset($_SESSION['validation_errors'][$name]) ? ' is-invalid' : '') . '"';
    $html .= ' name="' . htmlspecialchars($name) . '"';
    $html .= ' id="' . htmlspecialchars($name) . '"';
    $html .= ' value="' . htmlspecialchars($value) . '"';
    $html .= ' aria-label="' . htmlspecialchars($label) . '"';
    $html .= ' aria-describedby="inputGroup-' . htmlspecialchars($name) . '"';
    $html .= ' required>';

    if (isset($_SESSION['validation_errors'][$name])) {
        $html .= '<div class="invalid-feedback">';
        $html .= htmlspecialchars($_SESSION['validation_errors'][$name]);
        $html .= '</div>';
    }

    $html .= '</div>';
    return $html;
}
