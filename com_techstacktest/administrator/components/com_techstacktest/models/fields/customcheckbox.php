<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of customcheckbox
 *
 * @author dclarke
 */


defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('checkbox');

class JFormFieldCustomCheckbox extends JFormFieldCheckbox {
    
        public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$return = parent::setup($element, $value, $group);

		if ($return)
		{
			$checked = (string) $this->element['checked'];
			$this->checked = ($checked == 'true' || $checked == 'checked' || $checked == '1');
                        
                        $this->databind = $this->element['databind'];

			empty($this->value) || $this->checked ? null : $this->checked = true;
		}

		return $return;
	}
    
    
        protected function getInput()
	{
		// Initialize some field attributes.
		$class     = !empty($this->class) ? ' class="' . $this->class . '"' : '';
                $dataBind = !empty($this->databind) ? ' data-bind="'. $this->databind.'"' : '';
		$disabled  = $this->disabled ? ' disabled' : '';
		$value     = !empty($this->default) ? $this->default : '1';
		$required  = $this->required ? ' required aria-required="true"' : '';
		$autofocus = $this->autofocus ? ' autofocus' : '';
		$checked   = $this->checked || !empty($this->value) ? ' checked' : '';

		// Initialize JavaScript field attributes.
		$onclick  = !empty($this->onclick) ? ' onclick="' . $this->onclick . '"' : '';
		$onchange = !empty($this->onchange) ? ' onchange="' . $this->onchange . '"' : '';

		// Including fallback code for HTML5 non supported browsers.
		JHtml::_('jquery.framework');
		JHtml::_('script', 'system/html5fallback.js', false, true);

		return '<input type="checkbox" name="' . $this->name . '" id="' . $this->id . '" value="'
			. htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '"' . $class . $checked . $dataBind . $disabled . $onclick . $onchange
			. $required . $autofocus . ' />';
	}
}
