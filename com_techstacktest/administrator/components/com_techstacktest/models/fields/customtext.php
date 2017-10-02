<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('text');

/**
 * Form Field class for the Joomla Platform.
 * Supports a one line text field.
 *
 * @link   http://www.w3.org/TR/html-markup/input.text.html#input.text
 * @since  11.1
 */
class JFormFieldCustomText extends JFormFieldText
{
	
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$result = parent::setup($element, $value, $group);

		if ($result == true)
		{
			
			$this->databind = $this->element['databind'];
		}

		return $result;
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		// Translate placeholder text
		$hint = $this->translateHint ? JText::_($this->hint) : $this->hint;

		// Initialize some field attributes.
		$size         = !empty($this->size) ? ' size="' . $this->size . '"' : '';
		$maxLength    = !empty($this->maxLength) ? ' maxlength="' . $this->maxLength . '"' : '';
		$class        = !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$readonly     = $this->readonly ? ' readonly' : '';
		$disabled     = $this->disabled ? ' disabled' : '';
		$required     = $this->required ? ' required aria-required="true"' : '';
		$hint         = $hint ? ' placeholder="' . $hint . '"' : '';
		$autocomplete = !$this->autocomplete ? ' autocomplete="off"' : ' autocomplete="' . $this->autocomplete . '"';
		$autocomplete = $autocomplete == ' autocomplete="on"' ? '' : $autocomplete;
		$autofocus    = $this->autofocus ? ' autofocus' : '';
		$spellcheck   = $this->spellcheck ? '' : ' spellcheck="false"';
		$pattern      = !empty($this->pattern) ? ' pattern="' . $this->pattern . '"' : '';
		$inputmode    = !empty($this->inputmode) ? ' inputmode="' . $this->inputmode . '"' : '';
		$dirname      = !empty($this->dirname) ? ' dirname="' . $this->dirname . '"' : '';
                $databind     = !empty($this->databind) ? ' data-bind="' .$this->databind.'"' : '';

		// Initialize JavaScript field attributes.
		$onchange = !empty($this->onchange) ? ' onchange="' . $this->onchange . '"' : '';

		// Including fallback code for HTML5 non supported browsers.
		JHtml::_('jquery.framework');
		JHtml::_('script', 'system/html5fallback.js', false, true);

		$datalist = '';
		$list     = '';

		/* Get the field options for the datalist.
		Note: getSuggestions() is deprecated and will be changed to getOptions() with 4.0. */
		$options  = (array) $this->getSuggestions();

		if ($options)
		{
			$datalist = '<datalist id="' . $this->id . '_datalist">';

			foreach ($options as $option)
			{
				if (!$option->value)
				{
					continue;
				}

				$datalist .= '<option value="' . $option->value . '">' . $option->text . '</option>';
			}

			$datalist .= '</datalist>';
			$list     = ' list="' . $this->id . '_datalist"';
		}

		$html[] = '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . $dirname . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $list
			. $hint . $onchange . $maxLength . $required . $autocomplete . $autofocus . $spellcheck . $inputmode . $pattern .$databind. ' />';
		$html[] = $datalist;

		return implode($html);
	}

	
}
