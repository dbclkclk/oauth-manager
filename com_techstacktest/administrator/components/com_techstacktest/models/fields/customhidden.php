<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('hidden');

/**
 * Form Field class for the Joomla Platform.
 * Provides a hidden field
 *
 * @link   http://www.w3.org/TR/html-markup/input.hidden.html#input.hidden
 * @since  11.1
 */
class JFormFieldCustomHidden extends JFormFieldHidden
{
	
	protected function getInput()
	{
		// Initialize some field attributes.
		$class = !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$disabled = $this->disabled ? ' disabled' : '';

                
                $dataBind = $this->getAttribute("databind");
                
		// Initialize JavaScript field attributes.
		$onchange = $this->onchange ? ' onchange="' . $this->onchange . '"' : '';

		return '<input type="hidden" name="' . $this->name . '" id="' . $this->id .'" data-bind="'.$dataBind .'" value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $disabled . $onchange . ' />';
	}
}
