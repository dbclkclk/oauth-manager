<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <deanclarke811@yahoo.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');

use Joomla\Utilities\ArrayHelper;
/**
 * Teachpro model.
 *
 * @since  1.6
 */
class TeachproModelTimesheet extends JModelItem
{
	
       
	
	
	protected function populateState()
	{
		$app = JFactory::getApplication('com_teachpro');

		// Load state from the request userState on edit or from the passed variable on default
		if (JFactory::getApplication()->input->get('layout') == 'edit')
		{
			$id = JFactory::getApplication()->getUserState('com_teachpro.edit.timesheet.id');
		}
		else
		{
			$id = JFactory::getApplication()->input->get('id');
			JFactory::getApplication()->setUserState('com_teachpro.edit.timesheet.id', $id);
		}

		$this->setState('payments.id', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
			$this->setState('timesheet.id', $params_array['item_id']);
		}

		$this->setState('params', $params);
	}

	/**
	 * Method to get an object.
	 *
	 * @param   integer  $id  The id of the object to get.
	 *
	 * @return  mixed    Object on success, false on failure.
	 */
	public function &getData($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id))
			{
				$id = $this->getState('timesheet.id');
			}

			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table->load($id))
			{
				// Check published state.
				if ($published = $this->getState('filter.published'))
				{
					if ($table->state != $published)
					{
						return $this->_item;
					}
				}

				// Convert the JTable to a clean JObject.
				$properties  = $table->getProperties(1);
				$this->_item = ArrayHelper::toObject($properties, 'JObject');
			}
		}

		if (isset($this->_item->created_by) )
		{
			$this->_item->created_by_name = JFactory::getUser($this->_item->created_by)->name;
		}if (isset($this->_item->modified_by) )
		{
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
	public function getTable($type = 'Timesheet', $prefix = 'TeachproTable', $config = array())
	{
		$this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_teachpro/tables');

		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Get the id of an item by alias
	 *
	 * @param   string  $alias  Item alias
	 *
	 * @return  mixed
	 */
	public function getItemIdByAlias($alias)
	{
		$table = $this->getTable();

		$table->load(array('alias' => $alias));

		return $table->id;
	}

	/**
	 * Method to check in an item.
	 *
	 * @param   integer  $id  The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function checkin($id = null)
	{
		// Get the id.
		$id = (!empty($id)) ? $id : (int) $this->getState('timesheet.id');

		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Attempt to check the row in.
			if (method_exists($table, 'checkin'))
			{
				if (!$table->checkin($id))
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Method to check out an item for editing.
	 *
	 * @param   integer  $id  The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function checkout($id = null)
	{
		// Get the user id.
		$id = (!empty($id)) ? $id : (int) $this->getState('timesheet.id');

		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Get the current user object.
			$user = JFactory::getUser();

			// Attempt to check the row out.
			if (method_exists($table, 'checkout'))
			{
				if (!$table->checkout($user->get('id'), $id))
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Publish the element
	 *
	 * @param   int  $id     Item id
	 * @param   int  $state  Publish state
	 *
	 * @return  boolean
	 */
	public function publish($id, $state)
	{
		$table = $this->getTable();
		$table->load($id);
		$table->state = $state;

		return $table->store();
	}

	/**
	 * Method to delete an item
	 *
	 * @param   int  $id  Element id
	 *
	 * @return  bool
	 */
	public function delete($id)
	{
		$table = $this->getTable();

		return $table->delete($id);
	}
        
       
        
        public function getNumWeeklySession ($data)
        {
            $num = 0;
            if ($data->signupterms=="commitment")
            {
                $total = JComponentHelper::getParams('com_teachpro')->get('total_committed_sessions', '0');
                if($total==0)
                    throw Exception("Error, total sessions configuration error");

                $num = $total / count($data->timesheet);

            }
            else
            {
                $num = $data->sessionWeek;
            }
            return $num;
        
        }
        
        public function getStartDateEndDate($dates, $num)
        {

            $i = 1;
            
            $startDate = new JDate("now +3 day");
            $endDate = new JDate("now +3 day");
            
            $arrangeDates = array();
            
            $numOfSessionsInWeek=0;
            
            while ($i<=floor($num))
            {
                
                if($i>=2)
                {
                    $startDate = $startDate->modify("+1 week");
                    $endDate = $endDate->modify("+1 week");  
                }
                $n = 0;
                while($n<count($dates))
                {
                    $startDate1 = clone $startDate;
                    $endDate1 = clone  $endDate;
                    
                    $startParseDate = new JDate($dates[$n]['teacherstartdate']);
                    $endParseDate = new JDate($dates[$n]['teacherenddate']);

                
                    
                    $modStartDate = $startDate1->modify("next ".$startParseDate->format("l"));
                    $modStartDate->setTime($startParseDate->hour,$startParseDate->minute);
                    $modStartDate->setTimezone($startParseDate->getTimezone());
                    
                    $modEndDate = $endDate1->modify("next ".$endParseDate->format("l"));
                    $modEndDate->setTime($endParseDate->hour,$endParseDate->minute);
                    $modEndDate->setTimezone($endParseDate->getTimezone());

                    $object = array();
                    
                    $object['available_start_date'] = $modStartDate;
                    $object['available_end_date'] = $modEndDate;


                    array_push($arrangeDates,$object);
                    $n++;
                    $numOfSessionsInWeek++;
                }
                $i++;
            }
            
            //This is  committed purchase and represents days
            if((ceil($num)-  floor($num))>0)
            {
                $total = JComponentHelper::getParams('com_teachpro')->get('total_committed_sessions', '0');
                if($total==0)
                    throw Exception("Error, total sessions configuration error");
                
                $rem = $total - (floor($num)*count($dates));
                $i =0;
                $last = end($arrangeDates);
                while($i<$rem)
                {
                    $startParseDate = new JDate($dates[$i]['teacherstartdate']);
                    $endParseDate = new JDate($dates[$i]['teacherenddate']);
                    
                    $s = clone $last['available_start_date'];
                    $l = clone $last['available_end_date'];
                    
                    $modStartDate = $s->modify("next ".$startParseDate->format("l"));
                    $modStartDate->setTime($startParseDate->hour,$startParseDate->minute);
                    $last['available_start_date']=$modStartDate;
                    
                    $modEndDate = $l->modify("next ".$endParseDate->format("l"));
                    $modEndDate->setTime($endParseDate->hour,$endParseDate->minute);
                    $last['available_end_date']=$modEndDate;
                    
                    
                    $object =array();
                    $object['available_start_date'] = $modStartDate;
                    $object['available_end_date'] = $modEndDate;


                    array_push($arrangeDates,$object);
                    
                    $i++;
                    
                }
            }
            return $arrangeDates;
            
            
        }
        
        
        

	
}