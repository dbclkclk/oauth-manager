<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataAttributeTextBox
 *
 * @author DEAN CLARKE
 */

defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 

class JFormFieldDatalist extends JFormFieldList  {
    
    
    /**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'DataAttribteList';

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = array();
		$attr = '';
       
                
				// Initialize some field attributes.
		$attr .= !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$attr .= !empty($this->size) ? ' size="' . $this->size . '"' : '';
		$attr .= $this->multiple ? ' multiple' : '';
		$attr .= $this->required ? ' required aria-required="true"' : '';
		$attr .= $this->autofocus ? ' autofocus' : '';
                
                
                foreach ($this->element->attributes() as  $a => $b)
                {
                    if(preg_match('(data-[a-zA-Z\-]+)', $a))
                    {
                        $attr .=$this->element[$a] ? " ".$a.'="'.(string) $this->element[$a].'"' : ' ';
                    }
                }
                
               
                

		// To avoid user's confusion, readonly="true" should imply disabled="true".
		if ((string) $this->readonly == '1' || (string) $this->readonly == 'true' || (string) $this->disabled == '1'|| (string) $this->disabled == 'true')
		{
			$attr .= ' disabled="disabled"';
		}

		// Initialize JavaScript field attributes.
		$attr .= $this->onchange ? ' onchange="' . $this->onchange . '"' : '';

		// Get the field options.
		$options = (array) $this->getOptions();

		// Create a read-only list (no name) with hidden input(s) to store the value(s).
		if ((string) $this->readonly == '1' || (string) $this->readonly == 'true')
		{
			$html[] = JHtml::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $this->value, $this->id);

			// E.g. form field type tag sends $this->value as array
			if ($this->multiple && is_array($this->value))
			{
				if (!count($this->value))
				{
					$this->value[] = '';
				}

				foreach ($this->value as $value)
				{
					$html[] = '<input type="hidden" name="' . $this->name . '" value="' . htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '"/>';
				}
			}
			else
			{
				$html[] = '<input type="hidden" name="' . $this->name . '" value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"/>';
			}
		}
		else
		// Create a regular list.
		{
			$html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
		}
                
                
                

		return implode($html);
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
    
    
}
