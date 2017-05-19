<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <sajendramanandhar@gmail.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

use Joomla\Utilities\ArrayHelper;

class TeachproModelParent extends JModelItem {
    
    /**
     * Method to get an object.
     *
     * @param   integer  $id  The id of the object to get.
     *
     * @return  mixed    Object on success, false on failure.
     */
    public function &getData($id = null) {

        if ($this->_item === null) {

            $this->_item = false;

            if (empty($id)) {
                $id = $this->getState('parent.id');
            }

            // Get a level row instance.
            $table = $this->getTable();

            // Attempt to load the row.
            if ($table->load($id)) {
                // Check published state.
                if ($published = $this->getState('filter.published')) {
                    if ($table->state != $published) {
                        return $this->_item;
                    }
                }

                // Convert the JTable to a clean JObject.
                $properties = $table->getProperties(1);
                $this->_item = ArrayHelper::toObject($properties, 'JObject');
            }
        }

        if (isset($this->_item->created_by)) {

            $this->_item->created_by_name = JFactory::getUser($this->_item->created_by)->name;
        }

        if (isset($this->_item->modified_by)) {

            $this->_item->modified_by_name = JFactory::getUser($this->_item->modified_by)->name;
        }

        return $this->_item;
    }
    
    /**
     * Get an instance of JTable class
     *
     * @param   string  $type    Name of the JTable class to get an instance of.
     * @param   string  $prefix  Prefix for the table class name. Optional.
     * @param   array   $config  Array of configuration values for the JTable object. Optional.
     *
     * @return  JTable|bool JTable if success, false on failure.
     */
    public function getTable($type = 'Parent', $prefix = 'TeachproTable', $config = array()) {

        $this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_teachpro/tables');

        return JTable::getInstance($type, $prefix, $config);
    }
  

}