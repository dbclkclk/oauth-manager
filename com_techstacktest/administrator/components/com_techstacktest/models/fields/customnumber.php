<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('number');

/**
 * Form Field class for the Joomla Platform.
 * Provides a select list of integers with specified first, last and step values.
 *
 * @since  11.1
 */
class JFormFieldCustomNumber extends JFormFieldNumber
{
	
/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.2
	 */
    
        public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$return = parent::setup($element, $value, $group);

		if ($return)
		{
			// It is better not to force any default limits if none is specified
			$this->databind  = isset($this->element['databind']) ? $this->element['databind'] : null;
			
		}

		return $return;
	}
    
	protected function getInput()
	{
		// Translate placeholder text
		$hint = $this->translateHint ? JText::_($this->hint) : $this->hint;

		// Initialize some field attributes.
		$size     = !empty($this->size) ? ' size="' . $this->size . '"' : '';

		// Must use isset instead of !empty for max/min because "zero" boundaries are always acceptable
		$max      = isset($this->max) ? ' max="' . $this->max . '"' : '';
		$min      = isset($this->min) ? ' min="' . $this->min . '"' : '';
                
                $dataBind = isset($this->databind) ? ' data-bind="'. $this->databind .'"' : '';

		$step     = !empty($this->step) ? ' step="' . $this->step . '"' : '';
		$class    = !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$readonly = $this->readonly ? ' readonly' : '';
		$disabled = $this->disabled ? ' disabled' : '';
		$required = $this->required ? ' required aria-required="true"' : '';
		$hint     = $hint ? ' placeholder="' . $hint . '"' : '';

		$autocomplete = !$this->autocomplete ? ' autocomplete="off"' : ' autocomplete="' . $this->autocomplete . '"';
		$autocomplete = $autocomplete == ' autocomplete="on"' ? '' : $autocomplete;

		$autofocus = $this->autofocus ? ' autofocus' : '';

		$value = (float) $this->value;
		$value = empty($value) ? $this->min : $value;

		// Initialize JavaScript field attributes.
		$onchange = !empty($this->onchange) ? ' onchange="' . $this->onchange . '"' : '';

		// Including fallback code for HTML5 non supported browsers.
		JHtml::_('jquery.framework');
		JHtml::_('script', 'system/html5fallback.js', false, true);

		return '<input type="number" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly
			. $hint . $onchange . $max . $step . $min . $required . $autocomplete . $autofocus . $dataBind . ' />';
	}

        
    
}
