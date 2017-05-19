<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of customtimezone
 *
 * @author dclarke
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('timezone');

class JFormFieldCustomTimezone extends JFormFieldTimezone {
    
    protected $databind;
    
    protected static $zones = array('America', 'Atlantic', 'Pacific');
    
    
    protected function getInput()
	{
		$html = array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$attr .= $this->disabled ? ' disabled' : '';
		$attr .= !empty($this->size) ? ' size="' . $this->size . '"' : '';
		$attr .= $this->multiple ? ' multiple' : '';
		$attr .= $this->required ? ' required aria-required="true"' : '';
		$attr .= $this->autofocus ? ' autofocus' : '';
                
                $attr .= $this->databind ? ' data-bind="'.$this->databind.'"' : '';
                
		// Initialize JavaScript field attributes.
		$attr .= !empty($this->onchange) ? ' onchange="' . $this->onchange . '"' : '';

		// Get the field groups.
		$groups = (array) $this->getGroups();

		// Create a read-only list (no name) with a hidden input to store the value.
		if ($this->readonly)
		{
			$html[] = JHtml::_(
				'select.groupedlist', $groups, null,
				array(
					'list.attr' => $attr, 'id' => $this->id, 'list.select' => $this->value, 'group.items' => null, 'option.key.toHtml' => false,
					'option.text.toHtml' => false
				)
			);
			$html[] = '<input type="hidden" name="' . $this->name . '" value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"/>';
		}

		// Create a regular list.
		else
		{
			$html[] = JHtml::_(
				'select.groupedlist', $groups, $this->name,
				array(
					'list.attr' => $attr, 'id' => $this->id, 'list.select' => $this->value, 'group.items' => null, 'option.key.toHtml' => false,
					'option.text.toHtml' => false
				)
			);
		}

		return implode($html);
	}
        
        public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$return = parent::setup($element, $value, $group);

		if ($return)
		{
			$this->databind = (string) $this->element['databind'];
		}

		return $return;
	}
        
        protected function getGroups()
	{
		$groups = array();

		$keyField = !empty($this->keyField) ? $this->keyField : 'id';
		$keyValue = $this->form->getValue($keyField);

		// If the timezone is not set use the server setting.
		if (strlen($this->value) == 0 && empty($keyValue))
		{
			$this->value = JFactory::getConfig()->get('offset');
		}

		// Get the list of time zones from the server.
		$zone1 = DateTimeZone::listIdentifiers(DateTimeZone::AMERICA);
                $zone2 = DateTimeZone::listIdentifiers(DateTimeZone::PACIFIC);

                $zones = array_merge($zone1, $zone2);
               
                // Build the group lists.
		foreach ($zones as $zone)
		{
			// Time zones not in a group we will ignore.
                    
                        if (!in_array($zone, array("America/New_York", "America/Chicago", "America/Denver", "America/Vancouver", "America/Anchorage", "Pacific/Honolulu")))
                                continue;
			if (strpos($zone, '/') === false)
			{
				continue;
			}

			// Get the group/locale from the timezone.
			list ($group, $locale) = explode('/', $zone, 2);

			// Only use known groups.
			if (in_array($group, self::$zones))
			{
				// Initialize the group if necessary.
				if (!isset($groups[$group]))
				{
					$groups[$group] = array();
				}

				// Only add options where a locale exists.
				if (!empty($locale))
				{
                                        switch($locale)
                                        {
                                            case "Anchorage":
                                                $locale = "AKST";
                                                break;
                                            case "New_York":
                                                 $locale = "EST";
                                                break;
                                            case "Vancouver":
                                                $locale="PST";
                                                break;
                                            case "Denver":
                                                $locale="MST";
                                                break;
                                            case "Chicago":
                                                $locale="CST";
                                                    break;
                                            case "Honolulu":
                                                $locale="HAST";
                                                break;
                                            default :
                                                 $locale = $locale;
                                                break;
                                        }
					$groups[$group][$zone] = JHtml::_('select.option', $zone, str_replace('_', ' ', $locale), 'value', 'text', false);
				}
			}
		}

                $groups[""][""] = JHtml::_('select.option', "", str_replace('_', ' ', "Select a timezone"), 'value', 'text', false);
                
		// Sort the group lists.
		ksort($groups);

		foreach ($groups as &$location)
		{
			sort($location);
		}
                
		// Merge any additional groups in the XML definition.
		$groups =  $groups;

		return $groups;
	}

    
}
